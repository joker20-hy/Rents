<template>
  <div class="container mt-3 mb-5">
    <h1>Danh sách hóa đơn</h1>
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
  data () {
    return {
      room_id: this.$route.params.room_id
    }
  },
  mounted () {
    this.get()
  },
  computed: {
    payments () {
      return this.$store.getters['payments/payments']
    }
  },
  methods: {
    get () {
      $request.get(`/api/payment/list?room_id=${this.room_id}`)
      .then(res => {
        res.data.data.forEach(payment => {
          payment.bill = JSON.parse(payment.bill)
          let time = new Date(payment.time)
          payment.month = time.getMonth() + 1
          payment.year = time.getFullYear()
        });
        this.$store.commit('payments/payments', res.data.data)
      })
      .catch(err => console.log(err))
    }
  }
}
</script>
<style scoped>
h1 {
  font-size: large;
}
</style>