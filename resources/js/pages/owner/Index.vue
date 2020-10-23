<template>
  <div style="min-height: 100vh">
    <header-bar></header-bar>
    <router-view></router-view>
    <div owner-bottom-nav>
      <div bar>
        <router-link :to="{name: 'account'}">Tài khoản</router-link>
        <router-link :to="{name: 'owner-list-house'}" class="mx-2">Danh sách nhà</router-link>
        <router-link :to="{name: 'owner-list-paymethod'}" class="mx-2">Thanh toán</router-link>
      </div>
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