<template>
  <div detail-preview-image>
    <div big>
      <div img-contain primary :style="`background-image: url(${images.length>0?images[0]:default_image})`" @click="$emit('detail', 0)"></div>
    </div>
    <div medium>
      <div img-contain secondary :style="`background-image: url(${images.length>1?images[1]:default_image})`" @click="$emit('detail', 1)"></div>
      <div img-contain tertiary :style="`background-image: url(${images.length>2?images[2]:default_image})`" @click="$emit('detail', 2)"></div>
      <div img-contain tertiary :style="`background-image: url(${images.length>3?images[3]:default_image})`" @click="$emit('detail', 3)">
        <div v-if="images.length>4" cover>
          <!-- v-if="images.length>4" -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z"/></svg>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'image-gallary',
  props: {
    images: {
      required: true,
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