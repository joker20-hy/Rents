<template>
  <span class="switch-input" :class="on ? 'on' : ''" @click="toggle">
    <span class="nipple"></span>
  </span>
</template>
<script>
export default {
  name: 'switch-input',
  data () {
    return {
      on: this.status
    }
  },
  props: {
    status: {
      required: true,
      type: Boolean
    },
    locked: {
      required: false,
      type: Boolean
    }
  },
  watch: {
    locked (val) {
      this.locked=val
    },
    status (val) {
      if (this.locked) return false
      this.on = val
    }
  },
  methods: {
    toggle () {
      if (this.locked) return false
      this.$emit('click')
    }
  }
}
</script>
<style scoped>
  .switch-input {
    width: 42px;
    height: 24px;
    display: flex;
    border-radius: 12px;
    background-color: var(--gray);
    padding: 2px;
  }
  .switch-input.on {
    background-color: var(--blue);
  }
  .switch-input .nipple {
    display: block;
    width: 20px;
    height: 20px;
    transition: .2s;
    border-radius: 50%;
    transform: translateX(0px);
    background-color: var(--light);
  }
  .switch-input.on .nipple {
    transform: translateX(18px);
  }
</style>
