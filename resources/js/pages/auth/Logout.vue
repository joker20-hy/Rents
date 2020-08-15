<template>
  <modal name="logout-form" :width="300" :class="['text-center']">
    <h3 class="pt-4 pb-3 mb-3 text-danger">Xác nhận đăng xuất</h3>
    <div>
      Bạn có chắc?
    </div>
    <div class="form-group pt-4">
      <button class="btn text-primary" @click="hide()">Hủy</button>
      <button class="btn text-danger" @click="logout()">Xác nhận</button>
    </div>
  </modal>
</template>
<script>
import { logout } from '../../utilities/request/request'
export default {
  name: 'logout-form',
  data () {
    return {}
  },
  created () {
    $eventHub.$on('show-logout-form', this.show)
  },
  methods: {
    show () {
      this.$modal.show('logout-form')
    },
    hide () {
      this.$modal.hide('logout-form')
    },
    logout () {
      logout(this.logoutSuccess, this.logoutError)
    },
    logoutSuccess () {
      this.hide()
      window.location.href = '/'
    },
    logoutError (err) {
      $eventHub.$emit('success-alert', {
        title: 'Error',
        message: err.response.data.message
      })
    }
  }
}
</script>
