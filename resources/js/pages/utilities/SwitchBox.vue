<template>
  <input
    type="range"
    min="0"
    max="1"
    ref="val"
    @input="input()"
    @click="$emit('click')"
    @change="change(value)"
    :value="value"
    switch-box
    :disabled="locked"
  >
</template>
<script>
export default {
  name: 'switch-box',
  props: {
    locked: {
      required: false,
      default: false,
      type: Boolean
    },
    value: {
      required: false,
      default: 0,
      type: Number
    }
  },
  data () {
    return {
      val: ''
    }
  },
  methods: {
    input () {
      if (this.locked) {}
      this.val = parseInt(this.$refs.val.value)
      this.$emit('input', this.val)
      this.$refs.val.value = this.val
    },
    change() {
      this.$emit('change')
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