<template>
  <div class="bg-light">
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
        :click-handler="changePage"
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
      <search-icon :width="'16px'" :height="'16px'" class="fill-light"/>
    </button>
    <!-- side search -->
    <side-search></side-search>
    <!-- filter for room list -->
    <room-filter @apply="filter"></room-filter>
  </div>
</template>
<script>
import SelectBox from '../../../utilities/SelectBox'
import SideSearch from '../../layouts/SideSearch'
import ListItem from './ListItem'
import RoomFilter from './Filter'
import ToolBar from './ToolBar'
import SearchIcon from '../../../../icons/search'
export default {
  components: {
    ListItem,
    SelectBox,
    SideSearch,
    RoomFilter,
    ToolBar,
    SearchIcon
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
        if(query.page) this.query.page = parseInt(query.page)
        if(query.district) this.query.district = query.district
        if(query.address) this.query.address = query.address
        if(query.type) this.query.type = query.type
        this.list()
      },
      deep: true,
      immediate: true
    }
  },
  methods: {
    list () {
      $eventHub.$emit('on-loading')
      ajax().get(`/api/room?${serialize.fromObj(this.query)}`)
      .then(res => {
        $eventHub.$emit('off-loading')
        this.rooms = res.data.data
        this.page_count = res.data.last_page
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
      this.query.page = 1
      this.$router.push({name: 'search-room', query: this.query})
    },
    sort (conditions) {
      if (conditions==null) return false
      this.query.sort = conditions
      this.query.page = 1
      this.$router.push({name: 'search-room', query: this.query})
    },
    changePage(page) {
      this.query.page = page
      this.$router.push({name: 'search-room', query: this.query})
    }
  }
}
</script>