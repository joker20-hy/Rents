<template>
  <div id="search-form">
    <div bg-cover></div>
    <form @submit.prevent="search">
      <h1 class="text-center py-3 text-light">Tìm kiếm nhà trọ, phòng trọ trên toàn quốc</h1>
      <div class="row position-relative mb-3">
        <div class="col-12" v-click-outside="hideAddressSuggest">
          <input type="search" class="search-input" @search="clear()" placeholder="Nhập địa chỉ, quận huyện" v-model="keywords" @keyup="suggestAddress(keywords)" @focus="suggest.address=true">
          <transition name="slide-fade">
            <suggest-box :options="addresses" :show="suggest.address" @choose="chooseAddress"/>
          </transition>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-7 pr-0">
          <button type="button" class="search-input bg-light text-dark text-bold">
            <i class="fas fa-street-view"></i> Tìm xung quanh
          </button>
        </div>
        <div class="col-5">
          <button class="search-input bg-danger text-light">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import ClickOutside from 'vue-click-outside'
import SuggestBox from './Suggest'
export default {
  name: 'search-form',
  components: {
    SuggestBox
  },
  data () {
    return {
      keywords: '',
      addresses: [],
      address: {},
      district: {
        id: '',
        slug: '',
        name: 'Quận huyện'
      },
      province: {
        id: '',
        slug: '',
        name: 'Tỉnh thành'
      },
      suggest: {
        address: false
      }
    }
  },
  methods: {
    suggestAddress (keywords) {
      if (keywords.length < 2) return false
      ajax().get(`/api/sg/address?keywords=${keywords}`)
      .then(res => this.addresses = res.data )
      .catch(err => console.log(err.response.data) )
    },
    chooseAddress (address) {
      this.address = address
      this.keywords = address.name
      this.suggest.address = false
    },
    clear () {
      if (this.keywords=='') this.address={}
    },
    hideAddressSuggest() {
      this.suggest.address = false
    },
    search () {
      if (this.address.name!=undefined) {
        let query = {}
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
        this.$router.push({name: 'search-room', query: query})
      } else if (this.keywords!='') {
        this.$router.push({name: 'search-room', query: {address: this.keywords}})
      } else {
        this.$router.push({name: 'search-room'})
      }
    }
  },
  directives: {
    ClickOutside
  }
}
</script>
