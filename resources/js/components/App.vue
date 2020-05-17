<template>
  <div id="app" class="d-flex">
    <side-menu/>
    <section class="float-left w-100">
      <header-bar/>
      <router-view/>
    </section>
    <logout-confirm/>
  </div>
</template>
<script>
import SideMenu from './layouts/SideMenu'
import HeaderBar from './layouts/HeaderBar'
import LogoutConfirm from './layouts/LogoutConfirm'

export default {
  name: 'App',
  components: {
    SideMenu,
    HeaderBar,
    LogoutConfirm
  },
  data () {
    return {}
  }
}
</script>