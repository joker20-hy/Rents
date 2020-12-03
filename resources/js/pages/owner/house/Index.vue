<template>
  <transition name="slide-left">
    <transition name="slide-left">
      <router-view/>
    </transition>
  </transition>
</template>
<script>
export default {
  components: {},
  data () {
    return {}
  }
}
</script>