<template>
  <div class="container mt-3 mb-5">
    <h1 class="py-3 px-2">Danh sách hóa đơn</h1>
    <div v-if="payments.length==0" class='text-center text-muted'>
      Hiện chưa có hóa đơn nào
    </div>
    <transition-group name="slide-fade">
      <div v-for="payment in payments" :key="payment.id" class="list-item row mx-0">
        <div class="col-12 px-0 d-flex align-items-center" style="font-weight: 600">
          {{ `Hóa đơn tháng ${payment.month}/${payment.year}` }}
        </div>
        <div class="col-8 px-1 d-flex align-items-center">
          <span v-if="payment.status" class="text-muted" style="text-decoration: line-through">Đã thanh toán</span>
          <span v-else class="text-primary">Chưa thanh toán</span>
        </div>
        <div class="col-4 px-0 text-right">
          <router-link :to="{name: 'payment-room-detail', params: {id: payment.id}}" class="btn btn-outline-primary">Chi tiết</router-link>
        </div>
      </div>
    </transition-group>
  </div>
</template>
<script>
export default {
  data() {
    return {
      id: this.$route.params.id
    }
  },
  mounted () {
    if (this.payments.length==0) this.get()
  },
  computed: {
    user () {
      return this.$store.getters['auth/user']
    },
    payments () {
      return this.$store.getters['payments/payments']
    }
  },
  methods: {
    get () {
      $eventHub.$emit('on-loading')
      ajax().get(`/api/payment/room/list?room_id=${this.id}`)
      .then(res => {
        $eventHub.$emit('off-loading')
        res.data.data.forEach(payment => {
          payment.bill = JSON.parse(payment.bill)
          let time = new Date(payment.time)
          payment.month = time.getMonth() + 1
          payment.year = time.getFullYear()
        });
        this.$store.commit('payments/payments', res.data.data)
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        console.log(err)
      })
    }
  }
}
</script>
<style scoped>
h1 {
  font-size: large;
}
</style>