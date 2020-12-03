<template>
  <div class="login-form">
    <form action="" class="px-3 pt-4 pb-3" @submit.prevent="login()">
      <div class="text-center">
        <router-link :to="{name: 'home'}">
          <rent-logo-icon :height="'50px'" :width="'auto'" class="fill-blue"/>
        </router-link>
      </div>
      <div class="text-center my-2">
        <h5 class="mb-0">Đăng nhập</h5>
      </div>
      <div class="form-group mb-4">
        <input type="email" class="input" placeholder="Email" v-model="credentials.username" required>
      </div>
      <div class="form-group mb-4">
        <input type="password" class="input" placeholder="Mật khẩu" v-model="credentials.password" required>
      </div>
      <div class="form-group mb-4">
        <button class="btn btn-primary w-100 text-center" :disabled="logining_in">
          Đăng nhập
        </button>
      </div>
      <div class="text-center pb-2">
        <router-link :to="{name: 'verify-email', query: {next: 'reset-password'}}" class="text-dark">Quên mật khẩu ?</router-link>
      </div>
    </form>
    <div class="w-100 text-center pt-3">
      <router-link :to="{name: 'register'}" class="text-light">
        <h6>Đăng ký tài khoản</h6>
      </router-link>
    </div>
  </div>
</template>
<script>
import { login, user } from '../../utilities/request/request'
import RentLogoIcon from '../../icons/RentLogo'
export default {
  name: 'login-form',
  components: {
    RentLogoIcon
  },
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
  methods: {
    login () {
      $eventHub.$emit('on-loading')
      this.logining_in = true
      login(this.credentials, this.loginSuccess, this.loginError)
    },
    loginSuccess () {
      user(res => {
        $eventHub.$emit('off-loading')
        this.logining_in = false
        $auth = $auth.init(res.data)
        this.$store.commit('auth/user', $auth.user)
        this.$router.push(this.redirectTo)
      }, err => {
        console.log(err)
      })
    },
    loginError (err) {
      $eventHub.$emit('off-loading')
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
    box-shadow: 0px 0px 30px #0003;
  }
  .login-form {
    width: 400px;
    max-width: 100%;
    margin-left: auto;
    margin-right: auto;
  }
</style>