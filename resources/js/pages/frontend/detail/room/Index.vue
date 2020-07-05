<template>
  <div class="container">
    <image-gallary :images="images"/>
    <h3>{{ room.name }}</h3>
    <small class="text-muted" v-if="room.house">{{ room.house.address_detail }}</small>    
    <div>
      <strong>Giá: </strong>
      {{ room.price }} vnđ
    </div>
    <room-criterias v-if="room.criterias" :criterias="room.criterias"/>
    <strong>Mô tả</strong>
    <p v-html="room.description"></p>
  </div>
</template>
<script>
import utf8 from 'utf8'
import { $auth } from '../../../../utilities/request/request'
import ImageGallary from './Images'
import RoomCriterias from './Criterias'
export default {
  components: {
    ImageGallary,
    RoomCriterias
  },
  watch: {
    '$route.params.room': {
      handler: function (room) {
        this.get(room)
      },
      deep: true,
      immediate: true
    }
  },
  data () {
    return {
      room: {},
      images: []
    }
  },
  methods: {
    get (id) {
      $auth.request.get(`/api/room/${id}`)
      .then(res => {
        this.room = res.data
        this.room.description = utf8.decode(this.room.description)
        this.images = JSON.parse(this.room.images)
      })
      .catch(err => {
        console.log(err.response.data)
      })
    }
  }
}
</script>