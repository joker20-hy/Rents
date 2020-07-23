<template>
  <div id="app">
    <router-view/>
    <alert-box/>
    <logout-form/>
  </div>
</template>
<script>
import AlertBox from './utilities/AlertBox'
import LogoutForm from './auth/Logout'

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
    this.authUser()
  },
  methods: {
    authUser () {
      if (!$auth.check) return false
      $auth.request.get('/api/user/find')
      .then(res => {
        this.$store.commit('auth/user', res.data)
      })
      .catch(err => {
        if (err.response.status==401) this.$router.push({name: 'login'})
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