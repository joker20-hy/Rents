<template>
  <div class="contain-sm mt-3">
    <h1 style="font-size: large" class="d-flex align-items-center">
      Phương thức thanh toán
      <router-link :to="{name: 'owner-create-paymethod'}" class="btn ml-auto text-primary text-bold">
        <i class="fas fa-plus"></i> Thêm
      </router-link>
    </h1>
    <div class="text-muted text-center" v-if="paymethods.length==0">Hiện chưa có bản ghi nào</div>
    <list-item v-for="paymethod in paymethods" :key="paymethod.id" :item="paymethod" @destroy="destroyConfirm"></list-item>
    <confirm-box :name="'destroy-confirm'" :title="'Xóa phương thức thanh toán'" :message="'Bạn có chăc muốn xóa?'" @confirm="destroy()"></confirm-box>
  </div>
</template>
<script>
import ListItem from './ListItem'
import ConfirmBox from '../../utilities/ConfirmBox'
export default {
  components: {
    ListItem,
    ConfirmBox
  },
  data () {
    return {
      chosen: ''
    }
  },
  computed: {
    paymethods () {
      return this.$store.getters['paymethods/paymethods']
    }
  },
  mounted () {
    this.get()
  },
  methods: {
    get () {
      $eventHub.$emit('on-loading')
      ajax().get('/api/pay-method')
      .then(res => {
        $eventHub.$emit('off-loading')
        this.$store.commit('paymethods/paymethods', res.data)
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        console.log(err)
      })
    },
    destroyConfirm (id) {
      this.chosen = id
      this.$modal.show('destroy-confirm')
    },
    destroy () {
      $eventHub.$emit('on-loading')
      ajax().delete(`/api/pay-method/${this.chosen}`)
      .then(res => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Đã lưu trạng thái thành công',
          timeout: 3000
        })
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Đã có lỗi xảy ra, hãy thử lại',
          timeout: 3000
        })
      })
    }
  }
}
</script>