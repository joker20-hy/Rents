<template>
  <div>
    <nav-header/>
    <side-search/>
    <router-view/>
  </div>
</template>
<script>
import NavHeader from '../layouts/Header'
import SideSearch from '../layouts/SideSearch'
export default {
  components: {
    NavHeader,
    SideSearch
  },
  data () {
    return {}
  }
}
</script>