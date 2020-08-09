<template>
  <div>
    <header-bar></header-bar>
    <router-view></router-view>
    <div owner-bottom-nav>
      <router-link :to="{name: 'owner-list-house'}" class="mx-2">Danh sách nhà</router-link>
      <!-- <router-link :to="{name: 'list-payment'}" class="mx-2">Danh sách hóa đơn</router-link> -->
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