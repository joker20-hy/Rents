<template>
  <div id="app">
    <transition name="slide-left">
      <router-view/>
    </transition>
    <alert-box/>
    <logout-form/>
    <modal name="loading" :classes="'loading-box'">
      <div class="loader lg m-auto"></div>
    </modal>
  </div>
</template>
<script>
import AlertBox from './utilities/AlertBox'
import LogoutForm from './auth/Logout'
import { user } from '../utilities/request/request'

export default {
  name: 'App',
  components: {
    LogoutForm,
    AlertBox
  },
  data () {
    return {}
  },
  mounted () {
    $eventHub.$on('on-loading', this.onloading)
    $eventHub.$on('off-loading', this.offloading)
    if (!$auth.check) return false
    user(res => {
      $auth = $auth.init(res.data)
      this.$store.commit('auth/user', $auth.user)
    }, err => {
      err.response.status==401?this.$router.push({name: 'login'}):''
    })
  },
  methods: {
    onloading() {
      this.$modal.show('loading')
    },
    offloading() {
      this.$modal.hide('loading')
    }
  }
}
</script>
<style scoped>
  .dashboard-container {
    background-image:url(/images/wave.svg);
    background-position-x: center;
    background-size:cover;
    background-repeat:no-repeat;
  }
</style>