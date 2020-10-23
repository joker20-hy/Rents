<template>
  <div id="app">
    <router-view/>
    <alert-box/>
    <logout-form/>
  </div>
</template>
<script>
import SideMenu from './layouts/SideMenu'
import HeaderBar from './layouts/HeaderBar'
import AlertBox from './utilities/AlertBox'
import LogoutForm from './pages/Auth/Logout'

export default {
  name: 'App',
  components: {
    SideMenu,
    HeaderBar,
    LogoutForm,
    AlertBox
  },
  data () {
    return {}
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