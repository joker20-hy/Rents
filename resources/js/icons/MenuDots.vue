<template>
  <svg :style="`width: ${width ? width: ''};height: ${height ? height: ''};`" viewBox="-192 0 512 512" xmlns="http://www.w3.org/2000/svg">
	<path d="m128 256c0 35.347656-28.652344 64-64 64s-64-28.652344-64-64 28.652344-64 64-64 64 28.652344 64 64zm0 0"/>
	<path d="m128 64c0 35.347656-28.652344 64-64 64s-64-28.652344-64-64 28.652344-64 64-64 64 28.652344 64 64zm0 0"/>
	<path d="m128 448c0 35.347656-28.652344 64-64 64s-64-28.652344-64-64 28.652344-64 64-64 64 28.652344 64 64zm0 0"/>
  </svg>
</template>
<script>
export default {
  props: {
    width: {
      required: false,
      default: ''
    },
    height: {
      required: false,
      default: ''
    }
  }
}
</script>
