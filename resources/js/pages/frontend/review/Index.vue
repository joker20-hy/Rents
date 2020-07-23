<template>
  <div>
    <nav-header/>
    <router-view/>
  </div>
</template>
<script>
import NavHeader from '../layouts/Header'
export default {
  components: {
    NavHeader
  },
  data () {
    return {
      sidemenu: false
    }
  }
}
</script>