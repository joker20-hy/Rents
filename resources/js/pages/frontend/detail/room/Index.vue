<template>
  <div class="container" v-if="room!=null">
    <image-gallary :images="images" @detail="slideshow"/>
    
    <div class="d-flex" v-if="hasRight">
      <h3>{{ room.name }}</h3>
      <router-link class="ml-auto btn text-primary" :to="{name: 'owner-detail-room', params: {id: room.id}}">
        <i class="fas fa-pen"></i> Chỉnh sửa
      </router-link>
    </div>

    <small class="text-muted" v-if="room.house">{{ room.house.address_detail }}</small>    
    <div class="d-flex my-3">
      <div>
        <strong>Giá: </strong>
        {{ range(room.price) }} vnđ
      </div>
      <div class="ml-auto">
        <strong>Đóng tiền:</strong> {{ room.cycle }} tháng / lần
      </div>
    </div>
    <div page-section>
      <h2>Cơ Sở vật chất</h2>
      <room-criterias v-if="room.criterias" :criterias="room.criterias" class="mb-3"/>
    </div>
    <div page-section>
      <h2>Mô tả</h2>
      <p v-html="room.description"></p>
    </div>
    <review-list :id="id"></review-list>
    <button class="search-btn" @click="toggleSideSearch">
      <i class="fas fa-search"></i>
    </button>
    <modal name="image-carousel" :classes="['mb-auto']">
      <carousel :per-page="1" :mouse-drag="true" v-model="carousel_index">
        <slide v-for="(image, index) in images" :key="index" :value="index">
          <img :src="image" alt="" class="mw-100 mh-100">
        </slide>
      </carousel>
    </modal>
  </div>
</template>
<script>
import { Carousel, Slide } from 'vue-carousel';
import ImageGallary from './Images'
import RoomCriterias from './Criterias'
import ReviewList from './Reviews'
export default {
  components: {
    ImageGallary,
    RoomCriterias,
    ReviewList,
    Carousel,
    Slide
  },
  watch: {
    '$route.params.id': {
      handler (id) {
        this.id = id
        this.get()
      },
      deep: true,
      immediate: true
    }
  },
  data () {
    return {
      id: '',
      images: [],
      carousel_index: 0
    }
  },
  computed: {
    room () {
      return this.$store.getters['rooms/first']
    },
    admin () {
      return this.$store.getters['auth/admin']
    },
    owner () {
      return this.$store.getters['auth/owner']
    },
    hasRight() {
      return this.owner||this.admin
    }
  },
  methods: {
    toggleSideSearch (e) {
      e.stopPropagation()
      $eventHub.$emit('toggle-side-search')
    },
    range(number) {
      return $number.range(`${number}`)
    },
    get () {
      $eventHub.$emit('on-loading')
      ajax().get(`/api/room/${this.id}`)
      .then(res => {
        res.data.description = utf8.decode(res.data.description)
        this.images = JSON.parse(res.data.images)
        this.$store.commit('rooms/rooms', [res.data])
        $eventHub.$emit('off-loading')
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        console.log(err.response.data)
      })
    },
    slideshow (index) {
      this.carousel_index = index
      console.log(this.carousel_index)
      this.$modal.show('image-carousel')
    }
  }
}
</script>
<style scoped>
h2 {
  font-size: medium;
}
@media(min-width: 992px) {
  h2 {
    font-size: large;
  }
}
</style>