<template>
  <modal :name="name" :classes="['confirm-box']" @before-close="beforeClose">
    <div class="mx-2 p-2 text-center">
      <h4 class="py-2">{{ title }}</h4>
      <div v-html="message"></div>
      <div>
        <button type="button" class="btn text-primary" @click="confirm()">Xác nhận</button>
        <button type="button" class="btn text-danger" @click="cancel()">Hủy</button>
      </div>
    </div>
  </modal>
</template>
<script>
export default {
  name: 'confirm-box',
  props: {
    name: {
      required: true,
      type: String
    },
    title: {
      required: true,
      type: String
    },
    message: {
      required: true,
      type: String
    }
  },
  data () {
    return {}
  },
  methods: {
    confirm () {
      this.$emit('confirm')
    },
    cancel () {
      this.$modal.hide(this.name)
      this.$emit('cancel')
    },
    beforeClose () {
     this.$emit('before-close')
    }
  }
}
</script>