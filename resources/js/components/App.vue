<template>
  <div id="app" class="d-flex">
    <side-menu/>
    <section class="float-left w-100">
      <header-bar/>
      <router-view/>
    </section>
    <!-- utilities components -->
    <alert-box/>
    <logout-form/>
  </div>
</template>
<script>
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
  data () {
    return {}
  }
}
</script>