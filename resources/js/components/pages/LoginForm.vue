<template>
  <div class="auth-container">
    <div class="login-form">
      <form action="" class="bg-white px-3 py-4" @submit.prevent="login()">
        <div class="text-center text-muted mb-4">
          <h2 class="mb-0">Login</h2>
          <small>for Admin</small>
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
      <div class="bg-light text-center py-4">
        <a href="" class="text-muted">Forgot password ?</a>
      </div>
    </div>
  </div>
</template>
<script>
import { $auth } from '../../auth'

export default {
  name: 'login-form',
  data () {
    return {
      redirectTo: '/a/dashboard',
      credentials: {
        username: '',
        password: ''
      }
    }
  },
  methods: {
    login () {
      $auth.login(this.credentials, this.loginSuccess, this.loginError)
    },
    loginSuccess () {
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
    box-shadow: 0px 1px 3px;
  }
</style>