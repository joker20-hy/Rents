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
  }
}
</script>
<style scoped>
  .dashboard-container {
    background-image:url(/images/wave.svg);
    background-position-x: center;
    background-size:cover;
    background-repeat:no-repeat;
    min-height: 100vh;
  }
</style>