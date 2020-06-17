<template>
  <div id="app">
    <router-view/>
    <alert-box/>
    <logout-form/>
  </div>
</template>
<script>
import { $auth } from '../auth'
import SideMenu from './layouts/SideMenu'
import HeaderBar from './layouts/HeaderBar'
import AlertBox from './utilities/AlertBox'
import LogoutForm from './utilities/LogoutForm'

export default {
  name: 'App',
  components: {
    SideMenu,
    HeaderBar,
    LogoutForm,
    AlertBox
  },
  computed: {
    auth () {
      return $auth.check()
    }
  },
  mounted () {
    this.authUser()
  },
  data () {
    return {}
  },
  methods: {
    authUser () {
      $auth.request.get('/api/user/find')
      .then(res => {
        this.$store.commit('auth/user', res.data)
      })
      .catch(err => {
        console.log(err)
      })
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