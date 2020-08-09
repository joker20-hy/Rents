<template>
  <div class="auth-container">
    <div class="login-form">
      <form action="" class="px-3 pt-4 pb-3" @submit.prevent="login()">
        <div class="text-center text-muted mb-4">
          <h2 class="mb-0">Login</h2>
        </div>
        <div class="form-group mb-4">
          <input type="email" class="input" placeholder="Email" v-model="credentials.username" required>
        </div>
        <div class="form-group mb-4">
          <input type="password" class="input" placeholder="Password" v-model="credentials.password" required>
        </div>
        <button class="btn btn-primary w-100 text-center">
          Login
        </button>
      </form>
      <div class="text-center" v-show="logining_in">
        <i class="fas fa-spinner fa-spin"></i>
      </div>
      <div class="bg-light text-center pt-2 pb-4" style="border-radius: 0px 0px 10px 10px">
        <a href="" class="text-muted">Forgot password ?</a>
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
      redirectTo: '/',
      credentials: {
        username: '',
        password: ''
      }
    }
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
  .login-form {
    min-width: 360px;
    max-width: 100%;
    margin-left: auto;
    margin-right: auto;
    background-color: var(--white);
    border-radius: 10px;
    box-shadow: 0px 1px 3px;
  }
</style>