<template>
  <div>
    <header-bar></header-bar>
    <router-view></router-view>
    <div owner-bottom-nav>
      <router-link :to="{name: 'owner-list-house'}">Danh sách nhà</router-link>
    </div>
  </div>
</template>
<script>
import HeaderBar from './layouts/Header'
export default {
  components: {
    HeaderBar
  },
  data () {
    return {}
  }
}
</script>