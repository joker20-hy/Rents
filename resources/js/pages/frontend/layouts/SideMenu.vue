<template>
  <div side-menu :class="isShow?'show':''" v-click-outside="hide">
	<header>
	  <div class="avatar-contain">
	  	<img v-if="auth.profile" :src="auth.profile.image==null?'/images/default.svg':auth.profile.image" alt=""/>
	  </div>
	  {{ auth.name }}
	</header>
	<main class="px-2">
    <div v-if="auth.id!=undefined" class="c-flex-middle" item @click="routeTo({name: 'account'})">
      <user-icon :width="'16px'" :height="'16px'" style="transform: translateY(-2px)"/>&nbsp;Tài khoản
    </div>
    <div v-if="renter" @click="routeTo({name: 'rented-room'})" item>
      <door-open-icon :width="'16px'" :height="'16px'"/> Phòng trọ
    </div>
    <div v-if="admin" class="c-flex-middle" @click="routeTo({name: 'user-list'})" item>
      <pie-chart-icon :width="'16px'" :height="'16px'" style="transform: translateY(-1px)"/>&nbsp;Quản lý
    </div>
     <div v-else-if="owner" class="c-flex-middle" @click="routeTo({name: 'owner-list-house'})" item>
      <pie-chart-icon :width="'16px'" :height="'16px'" style="transform: translateY(-1px)"/>&nbsp;Quản lý
    </div>
    <div class="c-flex-middle" item v-if="auth.id!=undefined" @click="logout()">
      <signout-icon :width="'16px'" :height="'16px'" style="transform: translateY(-1px)"/>&nbsp;Đăng xuất
    </div>
    <hr>
    <div item class="text-danger" @click="hide">
      Đóng
    </div>
	</main>
  </div>
</template>
<script>
import UserIcon from '../../../icons/User'
import PieChartIcon from '../../../icons/PieChart'
import DoorOpenIcon from '../../../icons/DoorOpen'
import ClickOutside from 'vue-click-outside'
import SignoutIcon from '../../../icons/Signout'
export default {
  name: 'side-menu',
  components: {
    PieChartIcon,
    UserIcon,
    DoorOpenIcon,
    SignoutIcon
  },
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
      return this.auth.role==$config.USER.ROLE.ADMIN
    },
    owner () {
      return this.auth.role==$config.USER.ROLE.OWNER
    },
    renter () {
      return this.auth.role==$config.USER.ROLE.RENTER
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