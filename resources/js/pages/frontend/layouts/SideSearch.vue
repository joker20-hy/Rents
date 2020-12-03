<template>
  <div side-search :class="isShow&&focus ? 'show focus' : (isShow ?'show':'')" v-click-outside="hide">
    <form @submit.prevent="search()">
      <button type="button" close @click="hide()">Đóng</button>
      <div search-box>
        <input type="search" placeholder="Nhập địa chỉ, quận huyện" v-model="keywords" @keyup="suggest()" @focus="focus=true" @blur="focus=false">
        <button class="c-flex-middle" style="justify-content: center">
          <search-icon :height="'15px'" :width="'15px'" class="fill-gray" style="transform: translateY(-1px);"/>
        </button>
      </div>
    </form>
    <div v-show="showSuggest">
      <div v-for="(option, index) in addresses" :key="`${option.id}@${index}`" class="suggest c-flex-middle" @click="choose(option)">
        <location-pin-icon :width="'15px'" :height="'15px'" class="fill-gray"/><div style="width:calc(100% - 20px);padding-left:5px">{{ option.name }}</div>
      </div>
    </div>
  </div>
</template>
<script>
import ClickOutside from 'vue-click-outside'
import SearchIcon from '../../../icons/search'
import LocationPinIcon from '../../../icons/LocationPin'
export default {
  name: 'side-search',
  components: {
    SearchIcon,
    LocationPinIcon
  },
  data () {
    return {
      isShow: false,
      focus: false,
      showSuggest: false,
      keywords: '',
      address: {},
      addresses: []
    }
  },
  created () {
    $eventHub.$on('toggle-side-search', this.toggle)
  },
  methods: {
    toggle () {
      this.isShow ? this.hide() : this.show()
    },
    show () {
      this.isShow = true
    },
    hide () {
      this.isShow = false
      this.keywords = ''
    },
    suggest () {
      if (this.keywords.length < 2) {
        this.showSuggest=false
        return false
      }
      this.showSuggest=true
      ajax().get(`/api/sg/address?keywords=${this.keywords}`)
      .then(res => {
        this.addresses = res.data
      })
      .catch(err => {
        console.log(err.response.data.message)
      })
    },
    choose (address) {
      this.address = address
      this.keywords = address.name
      this.showSuggest = false
    },
    search () {
      this.hide()
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