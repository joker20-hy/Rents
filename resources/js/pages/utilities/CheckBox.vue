<template>
  <span class="check-box" @click="check()" :checked="checked">
    <span class="check">
      <i class="fas fa-check" v-show="checked"></i>
    </span>&nbsp;
    <span>{{ label }}</span>
  </span>
</template>
<script>
export default {
  name: 'check-box',
  props: {
    index: {
      required: true
    },
    label: {
      required: true,
      type: String
    }
  },
  data () {
    return {
      checked: false
    }
  },
  methods: {
    check () {
      this.checked=!this.checked
      this.$emit('change', {
        index: this.index,
        checked: this.checked
      })
    }
  }
}
</script>
<style scoped>
  .check-box {
    display: inline-flex;
    align-items: center;
    cursor: pointer;
  }
  .check-box .check {
    min-height: 18px;
    min-width: 18px;
    max-height: 18px;
    max-width: 18px;
    border: 1px solid;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>