<template>
  <div class="container" v-if="room!=null">
    <image-gallary :images="images"/>
    <h3>{{ room.name }}</h3>
    <small class="text-muted" v-if="room.house">{{ room.house.address_detail }}</small>    
    <div class="d-flex my-3">
      <div>
        <strong>Giá: </strong>
        {{ room.price }} vnđ
      </div>
      <div class="ml-auto">
        <strong>Đóng tiền:</strong> {{ room.cycle }} tháng / lần
      </div>
    </div>
    <strong>Cơ Sở vật chất</strong>
    <room-criterias v-if="room.criterias" :criterias="room.criterias" class="mb-3"/>
    <strong>Mô tả</strong>
    <p v-html="room.description"></p>
    <button class="search-btn" @click="toggleSideSearch">
      <i class="fas fa-search"></i>
    </button>
    <div class="text-center">
      <router-link class="btn text-primary" :to="{name:'review-room', params: {id: this.id}}">review</router-link>
    </div>
  </div>
</template>
<script>
import ImageGallary from './Images'
import RoomCriterias from './Criterias'
export default {
  components: {
    ImageGallary,
    RoomCriterias
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
      images: []
    }
  },
  computed: {
    room () {
      return this.$store.getters['rooms/first']
    }
  },
  methods: {
    toggleSideSearch (e) {
      e.stopPropagation()
      $eventHub.$emit('toggle-side-search')
    },
    get () {
      $auth.request.get(`/api/room/${this.id}`)
      .then(res => {
        res.data.description = utf8.decode(res.data.description)
        this.images = JSON.parse(res.data.images)
        this.$store.commit('rooms/rooms', [res.data])
      })
      .catch(err => {
        console.log(err.response.data)
      })
    }
  }
}
</script>