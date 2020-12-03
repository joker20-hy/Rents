<template>
  <div class="d-flex">
    <side-menu/>
    <section class="float-left w-100 dashboard-container">
      <header-bar/>
      <transition name="slide-left">
        <router-view/>
      </transition>
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
    max-height: 100vh;
    overflow-y: auto;
  }
</style>