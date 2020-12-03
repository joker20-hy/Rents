<template>
  <div id="search-form">
    <div bg-cover></div>
    <div class="row mx-0" style="max-width: 640px;width: 100%;z-index: 1">
      <h1 class="w-100 text-center text-light">Tìm kiếm nhà trọ, phòng trọ trên toàn quốc</h1>
      <div class="col-12 text-center py-3">
        <button class="btn" :class="type==searchTypes.FREE?'btn-primary':'text-light'" @click="type=searchTypes.FREE">Tìm phòng</button>
        &nbsp;
        <button class="btn" :class="type==searchTypes.ROOMMATE?'btn-primary':'text-light'" @click="type=searchTypes.ROOMMATE">Tìm người ở ghép</button>
      </div>
      <div class="col-md-8 px-0" v-click-outside="hide">
        <input type="search" class="search-input" placeholder="Nhập địa chỉ" v-model="keywords" @keyup="getSuggest(keywords)" @search="clear()" @focus="suggest=true">
        <transition name="slide-fade">
          <suggest-box :options="results" :show="suggest" @choose="choose"/>
        </transition>
      </div>
      <form class="col-md-4 px-0 mt-2 mt-md-0" @submit.prevent="search">
        <input type="hidden" name="type" value="">
        <button class="search-input bg-danger fill-light">
          <search-icon :width="'14px'" :height="'14px'"/>
        </button>
      </form>
    </div>
  </div>
</template>
<script>
import ClickOutside from 'vue-click-outside'
import SuggestBox from './Suggest'
import SearchIcon from '../../../icons/search'
export default {
  name: 'search-form',
  components: {
    SuggestBox,
    SearchIcon
  },
  data () {
    return {
      keywords: '',
      results: [],
      address: {},
      type: $config.SEARCH.TYPE.FREE,
      suggest: false,
      searchTypes: $config.SEARCH.TYPE
    }
  },
  methods: {
    getSuggest (keywords) {
      if (keywords.length < 2) return false
      ajax().get(`/api/sg/address?keywords=${keywords}`)
      .then(res => this.results = res.data )
      .catch(err => console.log(err.response.data) )
    },
    choose (address) {
      this.address = address
      this.keywords = address.name
      this.suggest = false
    },
    clear () {
      if (this.keywords=='') this.address={}
    },
    hide() { this.suggest = false },
    search () {
      let query = {}
      if (this.address.name!=undefined) {
        switch (this.address.type) {
          case 1:
            query = {province: this.address.slug}
            break;
          case 2:
            query = {district: this.address.slug}
            break;
          case 3:
            query = {area: this.address.slug}
            break;
          default:
            break;
        }
      } else if (this.keywords!='') {
        query = {address: this.keywords}
      }
      query.type=this.type
      this.$router.push({name: 'search-room', query: query})
    }
  },
  directives: {
    ClickOutside
  }
}
</script>
