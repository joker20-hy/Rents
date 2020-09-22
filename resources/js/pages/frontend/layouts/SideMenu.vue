<template>
  <div side-menu :class="isShow?'show':''" v-click-outside="hide">
	<header>
	  <div class="avatar-contain">
	  	<img v-if="auth.profile" :src="auth.profile.image==null?'/images/default.svg':auth.profile.image" alt=""/>
	  </div>
	  {{ auth.name }}
	</header>
	<main class="px-2">
    <div v-if="auth.id!=undefined" item @click="routeTo({name: 'account'})">
      <i class="fas fa-user"></i> Tài khoản
    </div>
    <div v-if="renter" @click="routeTo({name: 'rented-room'})" item>
      <i class="fas fa-house-user"></i> Phòng trọ
    </div>
	  <div v-if="admin" @click="routeTo({name: 'user-list'})" item>
      <i class="fas fa-users-cog"></i> Quản lý
    </div>
     <div v-else-if="owner" @click="routeTo({name: 'owner-list-house'})" item>
      <i class="fas fa-users-cog"></i> Quản lý
    </div>
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
    },
    renter () {
      return this.auth.role==$config.user.ROLE.RENTER
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
      this.hide()
      $eventHub.$emit('show-logout-form')
    },
    routeTo (route) {
      this.hide()
      this.$router.push(route)
    }
  },
  directives: {
    ClickOutside
  }
}
</script>