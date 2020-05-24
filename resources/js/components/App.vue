<template>
  <div id="app" class="d-flex">
    <side-menu/>
    <section class="float-left w-100">
      <header-bar/>
      <router-view/>
    </section>
    <!-- utilities components -->
    <logout-confirm/>
    <notice-modal/>
  </div>
</template>
<script>
import SideMenu from './layouts/SideMenu'
import HeaderBar from './layouts/HeaderBar'
import LogoutConfirm from './layouts/LogoutConfirm'
import NoticeModal from './layouts/NoticeModal'

export default {
  name: 'App',
  components: {
    SideMenu,
    HeaderBar,
    LogoutConfirm,
    NoticeModal
  },
  data () {
    return {}
  }
}
</script>