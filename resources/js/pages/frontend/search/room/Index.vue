<template>
  <div class="bg-light" style="padding-bottom: 60px">
    <nav-header/>
    <div class="container px-2" style="">
      <h4 class="pt-3">Danh sách phòng trọ</h4>
      <!-- toolbar -->
      <tool-bar @sort="sort"/>
      <!-- list item -->
      <list-item v-for="room in rooms" :key="room.id" :room="room" class="bg-white p-2 rounded"/>
      <paginate
        v-if="rooms.length>0"
        v-model="query.page"
        :page-count="page_count"
        :page-range="3"
        :margin-pages="2"
        :click-handler="list"
        :prev-text="'Prev'"
        :next-text="'Next'"
        :container-class="'pagination'"
        :page-class="'page-item'">
      </paginate>
    </div>
    <div v-show="rooms.length==0" class="text-muted text-center py-3">
      Không tìm thấy kết quả phù hợp
    </div>
    <button class="search-btn" @click="toggleSideSearch">
      <i class="fas fa-search"></i>
    </button>
    <!-- side search -->
    <side-search></side-search>
    <!-- filter for room list -->
    <room-filter @apply="filter"></room-filter>
  </div>
</template>
<script>
import NavHeader from '../../layouts/Header'
import SelectBox from '../../../utilities/SelectBox'
import SideSearch from '../../layouts/SideSearch'
import ListItem from './ListItem'
import RoomFilter from './Filter'
import ToolBar from './ToolBar'

export default {
  components: {
    NavHeader,
    ListItem,
    SelectBox,
    SideSearch,
    RoomFilter,
    ToolBar
  },
  data () {
    return {
      rooms: [],
      query: {
        page: 1
      },
      sidemenu: false,
      page_count: 1,
      conditions: {
        price: [],
        criterias: []
      }
    }
  },
  watch: {
    '$route.query': {
      handler (query) {
        this.query = query
        this.list()
      },
      deep: true,
      immediate: true
    }
  },
  methods: {
    list (page = 1) {
      this.query.page = page
      $eventHub.$emit('on-loading')
      ajax().get(`/api/room?${serialize.fromObj(this.query)}`)
      .then(res => {
        $eventHub.$emit('off-loading')
        this.rooms = res.data.data
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        console.log(err.response.data)
      })
    },
    toggleSideSearch (e) {
      e.stopPropagation()
      $eventHub.$emit('toggle-side-search')
    },
    filter (conditions) {
      this.query = merge.objects(conditions, this.query)
      this.list()
    },
    sort (conditions) {
      if (conditions==null) return false
      this.query.sort = conditions
      this.list()
    }
  }
}
</script>