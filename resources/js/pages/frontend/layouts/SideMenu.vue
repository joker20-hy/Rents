<template>
  <div side-menu :class="isShow?'show':''" v-click-outside="hide">
	<header style="height:150px">
	  <div class="avatar-contain">
	  	<img v-if="auth.profile" :src="auth.profile.image==null?'/images/default.svg':auth.profile.image" alt=""/>
	  </div>
	  {{ auth.name }}
	</header>
	<main class="px-2">
    <div item v-if="auth.id!=undefined">
      <i class="fas fa-user"></i> Tài khoản
    </div>
	  <router-link v-if="admin" :to="{name: 'user-list'}" item>
      <i class="fas fa-users-cog"></i> Quản lý
    </router-link>
     <router-link v-else-if="owner" :to="{name: 'owner-list-house'}" item>
      <i class="fas fa-users-cog"></i> Quản lý
    </router-link>
    <div item v-if="auth.id!=undefined" @click="logout()">
      <i class="fas fa-sign-out-alt"></i> Đăng xuất
    </div>
    <hr>
    <div item class="text-danger" @click="hide">
      <i class="far fa-times-circle"></i> Đóng
    </div>
	</main>
  </div>
</template>
<script>
import ClickOutside from 'vue-click-outside'
export default {
  name: 'side-menu',
  data () {
  	return {
      isShow: false
    }
  },
  created () {
    $eventHub.$on('toggle-side-menu', this.toggle)
  },
  computed: {
    auth () {
      return this.$store.getters['auth/user']
    },
    admin () {
      return this.auth.role==$config.user.ROLE.ADMIN
    },
    owner () {
      return this.auth.role==$config.user.ROLE.OWNER
    }
  },
  methods: {
    toggle () {
      this.isShow=!this.isShow
    },
    hide () {
      this.$emit('close')
      this.isShow = false
    },
    logout () {
      $eventHub.$emit('show-logout-form')
    }
  },
  directives: {
    ClickOutside
  }
}
</script>