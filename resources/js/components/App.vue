<template>
  <div id="app" class="d-flex">
    <side-menu/>
    <section class="float-left w-100">
      <header-bar/>
      <router-view/>
    </section>
  </div>
</template>
<script>
import SideMenu from './layouts/SideMenu'
import HeaderBar from './layouts/HeaderBar'

export default {
  name: 'App',
  components: {
    SideMenu,
    HeaderBar
  },
  data () {
    return {}
  }
}
</script>