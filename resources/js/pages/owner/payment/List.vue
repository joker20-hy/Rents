<template>
  <div class="container mt-3">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-xl-6">
        <div class="d-flex">
          <h3>Danh sách hóa đơn</h3> 
          <router-link style="font-weight: 600" :to="{name: 'owner-create-payment', params: {id: query.room_id}}" class="ml-auto">
            <i class="fas fa-money-check-alt"></i> Tạo hóa đơn
          </router-link>
        </div>
        <div class="text-muted text-center" v-if="payments.length==0">
          Hiện chưa có hóa đơn nào
        </div>
        <list-item v-else v-for="payment in payments" :key="payment.id" :payment="payment" @delete="confirmDelete"/>
        <paginate
          v-if="payments.length>0"
          v-model="query.page"
          :page-count="page_count"
          :page-range="3"
          :margin-pages="2"
          :click-handler="changePage"
          :prev-text="'Prev'"
          :next-text="'Next'"
          :container-class="'pagination'"
          :page-class="'page-item'">
        </paginate>
      </div>
    </div>
    <confirm-box :name="'delete-payment'" :title="'Xóa hóa đơn'" :message="'Bạn có chăc muốn xóa hóa đơn này?'" @confirm="destroy()"></confirm-box>
  </div>
</template>
<script>
import ConfirmBox from '../../utilities/ConfirmBox'
import ListItem from './ListItem'
export default {
  components: {
    ListItem,
    ConfirmBox
  },
  data () {
    return {
      chosen: '',
      page_count: 1,
      query: {
        page: this.$route.query.page,
        room_id: this.$route.params.id
      }
    }
  },
  watch: {
    "$route.params.page": {
      handler (page) {
        this.query.page = page
        this.list()
      },
      deep: true,
      immediate: true
    }
  },
  computed: {
    payments () {
      return this.$store.getters['payments/payments']
    }
  },
  methods: {
    list () {
      $eventHub.$emit('on-loading')
      ajax().get(`/api/payment/room/list?${serialize.fromObj(this.query)}`)
      .then(res => {
        $eventHub.$emit('off-loading')
        res.data.data.forEach(payment => {
          payment.bill = JSON.parse(payment.bill)
          let time = new Date(payment.time)
          payment.month = time.getMonth() + 1
          payment.year = time.getFullYear()
        })
        this.$store.commit('payments/payments', res.data.data)
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
      })
    },
    changePage (page) {
      this.$router.push({name: 'owner-list-payment', params: {id: this.query.room_id}, query: {page: page}})
    },
    confirmDelete (id) {
      this.chosen = id
      this.$modal.show('delete-payment')
    },
    destroy () {
      this.$modal.hide('delete-payment')
      $eventHub.$emit('on-loading')
      ajax().delete(`/api/payment/room/${this.chosen}`)
      .then(res => {
        this.$store.commit('payments/delete', this.chosen)
        $eventHub.$emit('off-loading')
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Hóa đơn đã được xóa thành công',
          timeout: 5000
        })
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Hóa đơn chưa thể được xóa, xin hãy thử lại sau'
        })
      })
    }
  }
}
</script>