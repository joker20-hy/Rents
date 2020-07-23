<template>
  <nav header-bar>
    <router-link class="rent-logo" to="/">Rent</router-link>
    <div class="ml-auto">
      <router-link :to="{name: 'login'}" v-if="!isAuth" class="btn btn-auth">
        <i class="fas fa-sign-in-alt"></i> Đăng nhập
      </router-link>
      <button class="btn btn-auth" v-if="isAuth" @click="logout()">
        <i class="fas fa-sign-out-alt"></i> Đăng xuất
      </button>
    </div>
  </nav>
</template>
<script>
import { $auth } from '../../../utilities/request/request'
export default {
  name: 'header-bar',
  data () {
    return {}
  },
  computed: {
    isAuth () {
  	  return $auth.check
  	}
  },
  methods: {
    logout () {
      $eventHub.$emit('show-logout-form')
    }
  }
}
</script>