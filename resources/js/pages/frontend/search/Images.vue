<template>
<div images-preview>
    <div img-contain primary :style="`background-image: url(${images.length>0?images[0]:default_image})`"></div>
    <div class="d-flex">
        <div img-contain secondary :style="`background-image: url(${images.length>1?images[1]:default_image})`"></div>
        <div img-contain secondary :style="`background-image: url(${images.length>2?images[2]:default_image})`"></div>
        <div img-contain secondary :style="`background-image: url(${images.length>3?images[3]:default_image})`">
          <div v-if="images.length>4" cover>
            <!--  -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z"/></svg>
          </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
  name: 'image-preview',
  props: {
    images: {
      required: false,
      default: [],
      type: Array
    }
  },
  computed: {
    default_image () {
      return $config.IMAGES.DEFAULT
    }
  },
  data () {
    return {}
  }
}
</script>