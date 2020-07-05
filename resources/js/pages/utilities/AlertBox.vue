<template>
  <modal name="alert-box" class="alert-box" :class="type">
    <div style="font-weight: 600">
      {{ title }}
    </div>
    <div style="padding-top: 10px">
      {{ message }}
    </div>
  </modal>
</template>
<script>
export default {
  name: 'alert-box',
  data () {
    return {
      title: 'title',
      message: 'message',
      type: ''
    }
  },
  created () {
    $eventHub.$on('success-alert', this.success)
    $eventHub.$on('warning-alert', this.warning)
    $eventHub.$on('error-alert', this.error)
  },
  methods: {
    success (data) {
      this.type = 'success'
      this.show(data)
    },
    warning (data) {
      this.type = 'warning'
      this.show(data)
    },
    error (data) {
      this.type = 'error'
      this.show(data)
    },
    show (data) {
      this.title = data.title
      this.message = data.message
      this.$modal.show('alert-box')
      if (data.timeout!=null) {
        setTimeout(() => {
          this.$modal.hide('alert-box')
        }, data.timeout)
      }
    }
  }
}
</script>

<style>
  .alert-box .vm--modal{
    top: 15px!important;
    left: unset!important;
    width: 100%!important;
    max-width: 500px!important;
    padding: 15px 10px;
  }
  .alert-box.success .vm--modal {
    color: var(--success);
    border-left: 6px solid;
  }
  .alert-box.warning .vm--modal {
    color: var(--orange);
    border-left: 6px solid;
  }
  .alert-box.error .vm--modal {
    color: var(--danger);
    border-left: 6px solid;
  }
</style>
