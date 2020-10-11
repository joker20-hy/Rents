<template>
  <div style="min-height: 100vh">
    <nav-header/>
    <router-view></router-view>
  </div>
</template>
<script>
import NavHeader from './layouts/Header'
export default {
  components: {
    NavHeader
  },
  data() {
    return {}
  }
}
</script>