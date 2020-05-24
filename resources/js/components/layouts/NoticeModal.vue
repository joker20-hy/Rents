<template>
  <b-modal id="notification" hide-header hide-footer>
    <div class="pb-2" :class="colors.title" style="font-weight: 600">
      {{ title }}
    </div>
    <div :class="colors.message">
      {{ message }}
    </div>
  </b-modal>
</template>
<script>
export default {
  name: 'notice-modal',
  data () {
    return {
      color: {
        title: '',
        message: ''
      }
    }
  },
  computed: {
    title () {
      return this.$store.state.notification.title
    },
    message () {
      return this.$store.state.notification.message
    },
    colors () {
      return this.$store.state.notification.colors
    }
  },
  methods: {
    showModal () {
      this.$bvModal.show('notification')
    }
  }
}
</script>
