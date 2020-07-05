<template>
  <div>
    <nav-bar @side-menu="sidemenu=!sidemenu"/>
    <side-menu :class="sidemenu?'show':''" @close="sidemenu=false"/>
    <div class="container" style="background-color: #f8f9fa;padding: 5px 10px">
      <list-item v-for="room in rooms" :key="room.id" :room="room"/>
    </div>
    <button class="search-btn" @click="toggleSideSearch">
      <i class="fas fa-search"></i>
    </button>
    <side-search @search="search"/>
    <room-filter/>
  </div>
</template>
<script>
import { $auth } from '../../../../utilities/request/request'
import serialize from '../../../../utilities/serialize'
import NavBar from './NavBar'
import SelectBox from '../../../utilities/SelectBox'
import ListItem from './ListItem'
import SideSearch from '../SideSearch'
import RoomFilter from './Filter'
import SideMenu from '../../layouts/SideMenu'
export default {
  components: {
    NavBar,
    SideMenu,
    ListItem,
    SelectBox,
    SideSearch,
    RoomFilter
  },
  data () {
    return {
      sort: [],
      rooms: [],
      params: this.$route.params,
      sidemenu: false
    }
  },
  watch: {
    '$route.params.province': {
      handler: function(province) {
         this.params=this.$route.params
         this.list()
      },
      deep: true,
      immediate: true
    }
  },
  methods: {
    list () {
      $auth.request.get(`/api/room?${serialize.fromObj(this.params)}`)
      .then(res => {
        this.rooms = res.data.data
      })
      .catch(err => {
        console.log(err.response.data)
      })
    },
    toggleSideSearch (e) {
      e.stopPropagation()
      $eventHub.$emit('toggle-side-search')
    },
    search (params) {
      this.$router.push({name: 'search-room', params: params})
    }
  }
}
</script>