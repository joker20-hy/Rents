
<template>
  <svg :style="`width: ${width ? width: ''};height: ${height ? height: ''};`" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
    <path d="M256,0c-74.439,0-135,60.561-135,135s60.561,135,135,135s135-60.561,135-135S330.439,0,256,0z"/>
    <path d="M423.966,358.195C387.006,320.667,338.009,300,286,300h-60c-52.008,0-101.006,20.667-137.966,58.195
    C51.255,395.539,31,444.833,31,497c0,8.284,6.716,15,15,15h420c8.284,0,15-6.716,15-15
    C481,444.833,460.745,395.539,423.966,358.195z"/>
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