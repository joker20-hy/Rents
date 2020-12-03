<template>
  <div contain-box>
      <form page-section @submit.prevent="store()">
        <h1 class="row py-3 px-2 bg-primary text-light">$$ Thêm phương thức thanh toán</h1>
        <div class="form-group">
          <label>Tên phương thức thanh toán<span class="text-danger">*</span></label>
          <input type="text" class="input" placeholder="Vd: Vietcombank hoặc ViettelPay" v-model="name" required>
        </div>
        <div class="form-group">
          <label>Số tài khoản <span class="text-danger">*</span></label>
          <input type="number" class="input" placeholder="Số tài khoản" v-model="account" required>
        </div>
        <div class="form-group">
          <label>Ghi chú</label>
          <textarea type="number" class="input" placeholder="Ghi chú" v-model="note"></textarea>
        </div>
        <div class="form-group text-right">
          <button class="btn btn-outline-primary">Thêm</button>
        </div>
      </form>
  </div>
</template>
<script>
export default {
  data () {
    return {
      name: '',
      account: '',
      note: ''
    }
  },
  computed: {
    paymethodtype () {
      return this.$store.getters['paymethods/types']
    }
  },
  methods: {
    store () {
      $eventHub.$emit('on-loading')
      ajax().post(`/api/pay-method`, {
        name: this.name,
        account: this.account,
        note: this.note
      })
      .then(res => {
        this.$store.commit('paymethods/addtype', res.data)
        $eventHub.$emit('off-loading')
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Phương thức thanh toán đã được thêm thành công',
          timeout: 3000
        })
        this.$router.push({name: 'owner-list-paymethod'})
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Đã có lỗi xảy ra, hãy thử lại'
        })
      })
    }
  }
}
</script>
<style scoped>
h1 {
  font-size: large;
}
.form-contain {
  width: 500px;
  max-width: 100%;
  padding: 0px 10px;
  margin-right: auto;
  margin-left: auto;
}
form {
  width: 100%
}
</style>