<template>
  <div>
    <image-gallary :images="images"/>
    <h3>Tên nhà</h3>
  </div>
</template>
<script>
import ImageGallary from './Images'
export default {
  components: {
    ImageGallary
  },
  data () {
    return {
      images: []
    }
  }
}
</script>