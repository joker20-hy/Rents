<template>
  <b-modal id="notification" hide-header hide-footer>
    <div class="pb-2" style="font-weight: 600">
      {{ title }}
    </div>
    <div>
      {{ message }}
    </div>
  </b-modal>
</template>
<script>
export default {
  name: 'notice-modal',
  data () {
    return {}
  },
  computed: {
    title () {
      return this.$store.state.notification.title
    },
    message () {
      return this.$store.state.notification.message
    }
  },
  methods: {
    showModal () {
      this.$bvModal.show('notification')
    }
  }
}
</script>
