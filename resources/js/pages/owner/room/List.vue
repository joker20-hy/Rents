<template>
  <div class="container mt-3 mb-5 px-0">
    <div class="col-md-10 mx-auto">
      <h4>Danh sách phòng trọ</h4>
      <div class="d-flex py-2">
        <router-link class="ml-auto" style="font-weight: 600" :to="{name: 'owner-create-room', params: {id: this.query.house}}">
          <i class="fa fa-plus"></i> Thêm phòng
        </router-link>
      </div>
      <small class="text-muted" v-if="rooms.length==0">Hiện chưa có phòng nào</small>
      <list-item v-for="room in rooms" :key="room.id" :item="room"></list-item>
    </div>
  </div>
</template>
<script>
import ListItem from './ListItem'
export default {
  components: {
    ListItem
  },
  data () {
    return {
      query: {
        page: 1,
        house: this.$route.params.id
      }
    }
  },
  watch: {
    "$route.params.id": {
      handler (id) {
        this.query.house = id
        this.list()
      },
      deep: true,
      immediate: true
    }
  },
  computed: {
    rooms () {
      return this.$store.getters['rooms/rooms']
    }
  },
  methods: {
    list () {
      $eventHub.$emit('on-loading')
      $request.get(`/api/room/list?${serialize.fromObj(this.query)}`)
      .then(res => {
        res.data.data.forEach(room => {
          room.images = JSON.parse(room.images)
          room.description = utf8.decode(room.description)  
        })
        this.$store.commit('rooms/rooms', res.data.data)
        $eventHub.$emit('off-loading')
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        console.log(err)
      })
    }
  }
}
</script>