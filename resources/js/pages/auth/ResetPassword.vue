<template>
  <div class="auth-container p-2">
    <form class="login-form px-2 py-3" @submit.prevent="update" v-if="token!=undefined">
      <h2 class="text-center text-primary">Rent</h2>
      <h5 class="text-center mb-2">Khôi phục mật khẩu</h5>
      <div class="form-group">
        <label for="">Mật khẩu mới</label>
        <input type="password" class="form-control" v-model="password" placeholder="Mật khẩu mới" required>
      </div>
      <div class="form-group">
        <label for="">Xác nhận mật khẩu</label>
        <input type="password" class="form-control" v-model="password_confirmation" placeholder="Xác nhận mật khẩu" required>
      </div>
      <div class="text-right">
        <button class="btn btn-primary">Xác nhận</button>
      </div>
    </form>
    <div class="text-center login-form bg-white rounded p-3" v-else>
      <h2 class="text-primary">Rent</h2>
      <h4 class="alert alert-danger">Không thể xác thực người dùng</h4>
      <router-link :to="{name: 'login'}" v-if="!auth">Quay lại trang đăng nhập</router-link>
      <div v-else>
        <button class="btn text-primary" @click="goback()">
          Quay lại
        </button>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data () {
    return {
      id: this.$route.params.id,
      token: this.$route.params.token,
      password: '',
      password_confirmation: ''
    }
  },
  computed: {
    auth() {
      return $auth.check
    },
    user() {
      return $auth.user
    },
    validate() {
      return this.password==this.password_confirmation
    }
  },
  methods: {
    goback() {
      history.go(-1)
    },
    update() {
      if (!this.validate) return false
      $eventHub.$emit('on-loading')
      ajax().put(`/api/change-password/${this.id}`, {
        password: this.password,
        password_confirmation: this.password_confirmation,
        token: this.token
      })
      .then(res => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Mật khẩu đã được thay đổi thành công',
          timeout: 5000
        })
        this.$router.push({name: 'login'})
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Đã có lối',
          message: 'Xin hãy thử lại sau'
        })
      })
    }
  }
}
</script>
<style scoped>
  .auth-container {
    min-height: 100vh;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    background-color: var(--blue);
  }
  form {
    background-color: var(--white);
    border-radius: 10px;
    box-shadow: 0px 1px 3px;
    padding: 10px;
  }
  .login-form {
    width: 400px;
    max-width: 100%;
    margin-left: auto;
    margin-right: auto;
  }
</style>