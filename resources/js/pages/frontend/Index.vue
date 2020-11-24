<template>
  <div style="min-height: 100vh">
    <nav-header/>
    <transition name="slide-left">
      <router-view/>
    </transition>
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