<template>
  <div id="header" nav-bar :class="isAuth?'':'py-2'">
  	<div class="logo">
  	  <router-link :to="{name: 'home'}" class="text-primary text-bold">
        <rent-logo-icon/>
      </router-link>
  	</div>
  	<div class="tools" v-if="isAuth">
  		<router-link :to="{name: 'home'}" class="tool w-50" title="Trang chủ">
        <home-icon/>
	  	</router-link>
	  	<div class="w-50 tool" @click="sidemenu" title="Menu">
        <list-icon/>
	  	</div>
  	</div>
  	<div class="user" :class="isAuth?'':'ml-auto'">
	    <button class="btn text-light">
        Đăng phòng
      </button>
  	  <router-link :to="{name: 'login'}" v-if="!isAuth" class="btn btn-auth">
        Đăng nhập
      </router-link>
      <button class="btn btn-auth" v-if="isAuth" @click="logout()">
        Đăng xuất
      </button>
  	</div>
    <side-menu v-if="isAuth"/>
  </div>
</template>
<script>
import RentLogoIcon from '../../../icons/RentLogo'
import HomeIcon from '../../../icons/Home'
import ListIcon from '../../../icons/List'
import SideMenu from './SideMenu'
export default {
  name: "nav-header",
  components: {
    SideMenu,
    HomeIcon,
    ListIcon,
    RentLogoIcon
  },
  data () {
  	return {}
  },
  computed: {
  	isAuth () {
  	  return $auth.check
  	},
    auth () {
      return this.$store.getters['auth/user']
    }
  },
  methods: {
  	sidemenu (e) {
  	  e.stopPropagation()
      $eventHub.$emit('toggle-side-menu')
  	},
  	logout () {
      $eventHub.$emit('show-logout-form')
    }
  }
}
</script>