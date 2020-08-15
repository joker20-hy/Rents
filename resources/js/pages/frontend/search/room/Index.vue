<template>
  <div class="bg-light">
    <nav-header/>
    <div class="container" style="background-color: #f8f9fa;padding: 5px 10px">
      <h4 class="pt-3">Danh sách phòng trọ</h4>
      <tool-bar :sort_conditions="sort_conditions" @sort="sort"/>
      <div class="py-3 text-center mx-auto" v-if="loading">
        <span class="p-2 bg-light rounded-circle">
          <i class="fas fa-spinner fa-pulse fa-lg text-primary"></i>
        </span>
      </div>
      <list-item v-for="room in rooms" :key="room.id" :room="room" class="bg-white p-2 rounded"/>
      <paginate
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
    <button class="search-btn" @click="toggleSideSearch">
      <i class="fas fa-search"></i>
    </button>
    <side-search/>
    <room-filter @apply="filter" :acreage="conditions.acreage" :price="conditions.price" :infras="conditions.infras" :filled="query"/>
  </div>
</template>
<script>
import serialize from '../../../../utilities/serialize'
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
      params: {},
      query: {
        sort: '',
        price: '',
        acreage: '',
        infras: '',
        page: 1
      },
      sidemenu: false,
      loading: false,
      page_count: 1,
      conditions: {
        price: [],
        infras: []
      }
    }
  },
  watch: {
    '$route.params': {
      handler (params) {
        this.list()
      },
      deep: true,
      immediate: true
    },
    '$route.query': {
      handler (query) {
        this.list()
      },
      deep: true,
      immediate: true
    }
  },
  computed: {
    sort_conditions () {
      return $room_sort
    }
  },
  methods: {
    init () {
      this.params = this.$route.params
      this.query.price=this.$route.query.price==undefined?'':this.$route.query.price
      this.query.acreage=this.$route.query.acreage==undefined?'':this.$route.query.acreage
      this.query.infras=this.$route.query.infras==undefined?'':this.$route.query.infras
      this.conditions.price = $room_filter.price
      this.conditions.acreage = $room_filter.acreage
      $request(`/api/criteria`)
      .then(res => {
        this.conditions.infras = res.data
        this.conditions.infras.forEach(infras => {
          infras.checked=this.query.infras.includes(`${infras.id}`)
        })
      })
      .catch(err => console.log(err.response.data))
    },
    listApi () {
      let query = {
        page: this.query.page,
        province: this.params.province,
        district: this.params.district,
        area: this.params.area,
        infras: this.query.infras,
        acreage: this.query.acreage,
        price: this.query.price
      }
      return `/api/room?${serialize.fromObj(query)}`
    },
    list (page = 1) {
      if (this.loading) return true
      this.init()
      this.loading = true
      this.query.page = page
      $request.get(this.listApi())
      .then(res => {
        this.loading = false
        this.rooms = res.data.data
      })
      .catch(err => {
        this.loading = false
        console.log(err.response.data)
      })
    },
    toggleSideSearch (e) {
      e.stopPropagation()
      $eventHub.$emit('toggle-side-search')
    },
    filter (conditions) {
      this.query = {
        price: conditions.price.index,
        acreage: conditions.acreage.index,
        infras: []
      }
      conditions.infras.forEach(infras => this.query.infras.push(infras.id))
      let query = {}
      if (this.query.price!=null) query.price = this.query.price
      if (this.query.acreage!=null) query.acreage = this.query.acreage
      if (this.query.infras.length>0) query.infras = this.query.infras.toString()
      this.$router.push({name: 'search-room', params: this.params, query: query})
    },
    sort (conditions) {
      if (conditions!=null) this.query.sort = conditions
      this.$router.push({name: 'search-room', params: this.params, query: this.query})
    }
  }
}
</script>