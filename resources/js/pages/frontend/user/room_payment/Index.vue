<template>
<div>
  <nav-header></nav-header>
  <router-view></router-view>
</div>
</template>
<script>
import NavHeader from '../../layouts/Header'
export default {
  components: {
    NavHeader
  },
  data () {
    return {}
  }
}
</script>