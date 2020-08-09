<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-xl-6">
        <div class="d-flex">
          <h3>Danh sách hóa đơn</h3> <router-link :to="{name: 'owner-create-payment', params: {id: query.room_id}}" class="ml-auto"><i class="fas fa-money-check"></i> Tạo hóa đơn</router-link>
        </div>
        <div class="text-muted text-center" v-if="payments.length==0">
          Hiện chưa có hóa đơn nào
        </div>
        <list-item v-else v-for="payment in payments" :key="payment.id" :payment="payment"/>
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
  </div>
</template>
<script>
import ListItem from './ListItem'
export default {
  components: {
    ListItem
  },
  data () {
    return {
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
      $request.get(`/api/payment/list?${serialize.fromObj(this.query)}`)
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
    }
  }
}
</script>