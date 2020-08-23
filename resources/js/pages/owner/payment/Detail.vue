<template>
<div class="contain">
  <div class="bill-contain" v-if="payment">
    <h4>Hóa đơn tháng {{ payment.month }}/{{ payment.year }}</h4>
    <div class="form-group d-flex" v-for="row in payment.bill.services" :key="row.servince_id">
      <label>{{ row.servince_name }}</label>
      <div class="ml-auto">{{ row.price }} vnđ {{ row.amount?` (${row.amount})`:'' }}</div>
    </div>
    <div class="form-group d-flex">
      <label>Tiền phòng</label>
      <div class="ml-auto">{{ payment.bill.room_price }} vnđ</div>
    </div>
    <hr>
    <div class="form-group d-flex">
      <label>Tổng</label>
      <div class="ml-auto">{{ payment.bill.total }} vnđ</div>
    </div>
  </div>
</div>
</template>
<script>
export default {
  data () {
    return {
      id: this.$route.params.id
    }
  },
  watch: {
    "$route.params.id": {
      handler (id) {
        this.id = id
        if (this.payment==undefined) this.get()
      },
      deep: true,
      immediate: true
    }
  },
  computed: {
    payment () {
      return this.$store.getters['payments/find'](this.id)
    }
  },
  methods: {
    get() {
      $request.get(`/api/payment/${this.id}`)
      .then(res => {
        res.data.bill = JSON.parse(res.data.bill)
        let time = new Date(res.data.time)
        res.data.month = time.getMonth() + 1
        res.data.year = time.getFullYear()
        this.$store.commit('payments/add', res.data)
      })
      .catch(err => console.log(err))
    }
  }
}
</script>
<style scoped>
.bill-contain {
  padding: 20px 15px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0px 1px 2px #ddd;
  margin-bottom: 50px;
}
label {
  font-weight: 600;
}
.contain {
  width: 400px;
  max-width: 100%;
  margin-right: auto;
  margin-left: auto;
  margin-top: 40px;
  padding: 10px;
}
</style>