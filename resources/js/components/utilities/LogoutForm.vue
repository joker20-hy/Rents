<template>
  <modal name="logout-form" :width="300" :class="['text-center']">
    <h3 class="pt-4 pb-3 mb-3 text-light bg-danger">Logout confirm</h3>
    <div>
      Are you sure?
    </div>
    <div class="form-group pt-4">
      <button class="btn text-primary" @click="hide()">Cancel</button>
      <button class="btn text-danger" @click="logout()">Confirm</button>
    </div>
  </modal>
</template>
<script>
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
      // actual action
      console.log('logout')
    }
  }
}
</script>
