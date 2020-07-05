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
import SideMenu from './layouts/SideMenu'
import HeaderBar from './layouts/HeaderBar'

export default {
  name: 'dashboard',
  components: {
    SideMenu,
    HeaderBar
  },
  data () {
    return {}
  }
}
</script>
<style scoped>
  .dashboard-container {
    background-color: #f0f0f0;
    min-height: 100vh;
  }
</style>