<template>
  <div class="d-flex">
    <side-menu/>
    <section class="float-left w-100 dashboard-container">
      <header-bar/>
      <router-view/>
    </section>
  </div>
</template>
<script>
import { $request } from '../../auth'
import SideMenu from '../layouts/SideMenu'
import HeaderBar from '../layouts/HeaderBar'

export default {
  name: 'dash-board',
  components: {
    SideMenu,
    HeaderBar
  },
  data () {
    return {}
  },
  mounted () {
    this.authUser()
  },
  methods: {
    authUser () {
      ajax().get('/api/user/find')
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
    background-color: #f0f0f0;
    min-height: 100vh;
  }
</style>