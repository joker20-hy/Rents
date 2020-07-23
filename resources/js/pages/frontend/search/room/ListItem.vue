<template>
  <div class="row mx-auto mt-2 mb-3" search-item>
    <div class="col-4 col-md-3">
      <div class="row">
        <div class="col-12 image-contain primary px-0">
          <img :src="images[0]" alt="">
        </div>
        <div class="col-4 image-contain secondary">
          <img :src="images.length>=2?images[1]:'/images/photo.svg'" alt="">
        </div>
        <div class="col-4 image-contain secondary">
          <img :src="images.length>=3?images[3]:'/images/photo.svg'" alt="">
        </div>
        <div class="col-4 image-contain secondary">
          <img :src="images.length>=4?images[4]:'/images/photo.svg'" alt="">
          <div v-if="images.length>4" class="cover">
            <i class="fas fa-plus"></i>
          </div>
          <div class="cover" v-if="images.length>4">
            <svg viewBox="0 0 469.33333 469.33333" xmlns="http://www.w3.org/2000/svg">
            <path d="m437.332031 192h-160v-160c0-17.664062-14.335937-32-32-32h-21.332031c-17.664062 0-32 14.335938-32 32v160h-160c-17.664062 0-32 14.335938-32 32v21.332031c0 17.664063 14.335938 32 32 32h160v160c0 17.664063 14.335938 32 32 32h21.332031c17.664063 0 32-14.335937 32-32v-160h160c17.664063 0 32-14.335937 32-32v-21.332031c0-17.664062-14.335937-32-32-32zm0 0"/>
            </svg>
          </div>
        </div>
      </div>
    </div>
    <div class="col-8 col-md-9 pr-0">
      <h5>
        <router-link :to="{name: 'room-for-rent', params: {id: room.id}}">{{ room.name }}</router-link>
      </h5>
      <div class="address">
        {{ room.address }}
      </div>
      <div class="rate">
        <span class="avg">
          {{ room.avg_rate }}
        </span>
        <span class="count">
          {{ room.rate_count }} Đánh giá
        </span>
      </div>
      <div class="row mx-0" v-if="room.criterias">
        <div class="col-4 col-lg-2 p-1 text-center text-primary" style="font-size: 13px" v-for="criteria in room.criterias" :key="criteria.id">
          <u>{{ criteria.name }}</u>
        </div>
      </div>
      <div class="row pt-3 pb-2">
        <div class="col-4">
          {{ room.acreage }} m2
        </div>
        <div class="col-8 text-right">
          {{ room.price }} vnđ/tháng
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'list-item',
  props: {
    room: {
      required: true,
      type: Object
    }
  },
  mounted () {
    console.log(this.room.id)
  },
  computed: {
    images () {
      return JSON.parse(this.room.images)
    }
  },
  data () {
    return {}
  }
}
</script>