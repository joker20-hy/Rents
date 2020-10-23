<template>
  <div side-search :class="isShow&&focus ? 'show focus' : (isShow ?'show':'')" v-click-outside="hide">
    <form @submit.prevent="search()">
      <button type="button" close @click="hide()">Đóng</button>
      <div search-box>
        <input type="search" placeholder="Nhập địa chỉ, quận huyện" v-model="keywords" @keyup="suggest()" @focus="focus=true" @blur="focus=false">
        <button><i class="fas fa-search"></i></button>
      </div>
    </form>
    <div v-show="showSuggest">
      <div v-for="(option, index) in addresses" :key="`${option.id}@${index}`" class="suggest" @click="choose(option)">
        <i class="fas fa-map-marker-alt"></i> {{ option.name }}
      </div>
    </div>
  </div>
</template>
<script>
import ClickOutside from 'vue-click-outside'
export default {
  name: 'side-search',
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