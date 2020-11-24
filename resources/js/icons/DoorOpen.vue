
<template>
  <svg :style="`width: ${width ? width: ''};height: ${height ? height: ''};`" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
    <path d="M460.19,438.857V36.571L167.619,0v512l292.571-36.571h24.381v-36.571H460.19z M259.048,292.571h-36.571v-73.143h36.571
    V292.571z"/>
    <polygon points="131.048,73.143 131.048,36.571 51.809,36.571 51.809,438.857 27.429,438.857 27.429,475.429 131.048,475.429 
    131.048,438.857 88.381,438.857 88.381,73.143"/>
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
