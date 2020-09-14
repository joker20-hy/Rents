<template>
  <div>
    <header-bar></header-bar>
    <router-view></router-view>
    <div owner-bottom-nav>
      <div bar>
        <router-link :to="{name: 'owner-list-house'}" class="mx-2">Danh sách nhà</router-link>
        <router-link :to="{name: 'owner-list-paymethod'}" class="mx-2">Phương thức thanh toán</router-link>
      </div>
      <!-- <router-link :to="{name: 'owner-list-paymethod'}" class="mx-2">Danh sách hóa đơn</router-link> -->
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