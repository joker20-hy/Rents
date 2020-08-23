<template>
  <div class="auth-container p-2">
    <div class="login-form">
      <form action="" class="px-3 pt-4 pb-3" @submit.prevent="login()">
        <div class="text-center text-muted mb-4">
          <h2 class="mb-0">Đăng nhập</h2>
        </div>
        <div class="form-group mb-4">
          <input type="email" class="input" placeholder="Email" v-model="credentials.username" required>
        </div>
        <div class="form-group mb-4">
          <input type="password" class="input" placeholder="Mật khẩu" v-model="credentials.password" required>
        </div>
        <transition name="slide-fade">
          <div class="py-2 text-center" v-show="logining_in">
            <i class="fas fa-spinner fa-pulse fa-lg text-primary"></i>
          </div>
        </transition>
        <div class="form-group mb-4">
          <button class="btn btn-primary w-100 text-center" :disabled="logining_in">
            Đăng nhập
          </button>
        </div>
        <div class="text-center pb-2">
          <a href="" class="text-dark">Quên mật khẩu ?</a>
        </div>
      </form>
      <div class="w-100 text-center pt-3">
        <router-link :to="{name: 'register'}" class="text-light">
          <h6>Đăng ký tài khoản</h6>
        </router-link>
      </div>
    </div>
  </div>
</template>
<script>
import { login } from '../../utilities/request/request'

export default {
  name: 'login-form',
  data () {
    return {
      logining_in: false,
      redirectTo: $auth.data('old_route')==undefined?'/':$auth.data('old_route').fullPath,
      credentials: {
        username: '',
        password: ''
      }
    }
  },
  created () {
    $eventHub.$emit('on-loading')
  },
  methods: {
    login () {
      this.logining_in = true
      login(this.credentials, this.loginSuccess, this.loginError)
    },
    loginSuccess () {
      this.logining_in = false
      window.location.href = this.redirectTo
    },
    loginError (err) {
      this.logining_in = false
      $eventHub.$emit('error-alert', {
        title: `${err.response.status} ${err.response.statusText}`,
        message: err.response.data
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
  .input {
    display: block;
    width: 100%;
    border: none;
    border-bottom: 2px solid #ccc;
    padding: 8px 15px;
    outline: unset!important;
  }
  form {
    background-color: var(--white);
    border-radius: 10px;
    box-shadow: 0px 1px 3px;
  }
  .login-form {
    width: 400px;
    max-width: 100%;
    margin-left: auto;
    margin-right: auto;
  }
</style>