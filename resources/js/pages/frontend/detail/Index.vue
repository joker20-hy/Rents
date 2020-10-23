<template>
  <div>
    <side-search></side-search>
    <router-view></router-view>
  </div>
</template>
<script>
import SideSearch from '../layouts/SideSearch'
export default {
  components: {
    SideSearch
  },
  data () {
    return {}
  }
}
</script>