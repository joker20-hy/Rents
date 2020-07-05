<template>
  <header>
    <router-link :to="{name: 'home'}" class="text-primary text-bold" style="font-size: 24px;text-decoration: none;">Rent</router-link>
    <div class="ml-auto">
      <router-link v-if="admin" :to="{name: 'user-list'}" class="btn text-bold text-muted">
        <i class="fas fa-users-cog"></i> Quản lý  
      </router-link>
      <button class="btn text-bold text-muted" v-if="auth.id!=undefined" @click="logout()">
        <i class="fas fa-sign-out-alt"></i> Đăng xuất
      </button>
      <router-link :to="{name: 'login'}" v-if="auth.id==undefined" class="btn text-bold text-muted">
        <i class="fas fa-sign-in-alt"></i> Đăng nhập
      </router-link>
    </div>
  </header>
</template>
<script>
export default {
  name: 'nav-header',
  computed: {
    auth () {
      return this.$store.getters['auth/user']
    },
    admin () {
      return this.auth.role==$config.user.ROLE.ADMIN
    }
  },
  data () {
    return {}
  },
  methods: {
    logout () {
      $eventHub.$emit('show-logout-form')
    }
  }
}
</script>