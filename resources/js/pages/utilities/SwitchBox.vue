<template>
  <input type="range" min="0" max="1" ref="val" @input="input()" :disabled="locked" switch-box>
</template>
<script>
export default {
  name: 'switch-box',
  props: {
    locked: {
      required: false,
      default: false,
      type: Boolean
    }
  },
  data () {
    return {}
  },
  methods: {
    input () {
      this.$emit('input', parseInt(this.$refs.val.value))
    }
  }
}
</script>
<style scoped>
[switch-box] {
  -webkit-appearance: none;
  width: 44px;
  height: 24px;
  background: #6c757d;
  outline: none;
  -webkit-transition: .2s;
  transition: opacity .2s;
  padding: 2px;
  border-radius: 14px;
}
[switch-box].on {
  background: #3490dc;
}
[switch-box]::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 20px;
  height: 20px;
  background: #f8f9fa;
  cursor: pointer;
  border-radius: 50%;
}
[switch-box]::-moz-range-thumb {
  width: 20px;
  height: 20px;
  background: #f8f9fa;
  cursor: pointer;
  border-radius: 50%;
}
</style>