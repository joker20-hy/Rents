<template>
  <div class="search-form">
    <form @submit.prevent="search">
      <h3 class="text-center py-3 text-light">Tìm kiếm nhà trọ, phòng trọ trên toàn quốc</h3>
      <div class="row position-relative mb-3">
        <div class="col-12" v-click-outside="hideAddressSuggest">
          <input type="search" class="search-input" placeholder="Nhập địa chỉ, quận huyện" v-model="address.name" @keyup="suggestAddress()" @focus="suggest.address=true">
          <transition name="slide-fade">
            <suggest-box :options="addresses" :show="suggest.address" @choose="chooseAddress"/>
          </transition>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-6 pr-0" v-click-outside="hideProvinceSuggest">
          <div class="d-flex align-items-center search-input" @click="suggest.province=!suggest.province" style="cursor: pointer">
            <span class="text-dark">{{ province.name }}</span>
            <i class="fas fa-chevron-down ml-auto"></i>
          </div>
          <transition name="slide-fade">
            <suggest-box :options="provinces" :show="suggest.province" @choose="chooseProvince"/>
          </transition>
        </div>
        <div class="col-6 pl-0" v-click-outside="hideDistrictSuggest">
          <div class="d-flex align-items-center search-input" @click="suggest.district=!suggest.district" style="cursor: pointer">
            <span class="text-dark">{{ district.name }}</span>
            <i class="fas fa-chevron-down ml-auto"></i>
          </div>
          <transition name="slide-fade">
            <suggest-box :options="districts" :show="suggest.district" @choose="chooseDistrict"/>
          </transition>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-8 pr-0">
          <button type="button" class="search-input bg-light text-dark text-bold">
            <i class="fas fa-street-view"></i> Tìm xung quanh
          </button>
        </div>
        <div class="col-4">
          <button class="search-input bg-danger text-light">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import { $auth } from '../../../utilities/request/request'
import ClickOutside from 'vue-click-outside'
import SuggestBox from './Suggest'
export default {
  name: 'search-form',
  components: {
    SuggestBox
  },
  data () {
    return {
      provinces: [],
      districts: [],
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
        address: false,
        province: false,
        district: false
      }
    }
  },
  mounted () {
    this.getProvinces()
  },
  methods: {
    suggestAddress (address) {
      if (this.address.name.length < 2) return false
      $auth.request.get(`/api/sg/address?keywords=${this.address.name}`)
      .then(res => {
        this.addresses = res.data
      })
      .catch(err => {
        console.log(err.response.data)
      })
    },
    chooseAddress (address) {
      this.address = address
      this.suggest.address = false
    },
    hideAddressSuggest() {
      this.suggest.address = false
    },
    chooseProvince (province) {
      this.province = province
      this.getDistricts(province.id)
      this.suggest.province = false
    },
    getProvinces () {
      $auth.request.get('/api/sg/provinces')
      .then(res => {
        this.provinces = res.data
      })
      .catch(err => {
        console.log(err.response.data)
      })
    },
    hideProvinceSuggest() {
      this.suggest.province = false
    },
    chooseDistrict (district) {
      this.district = district
      this.suggest.district = false
    },
    getDistricts (provinceId) {
      this.district = {
        name: 'Quận huyện'
      }
      $auth.request.get(`/api/district/${provinceId}`)
      .then(res => {
        this.districts = res.data
      })
      .catch(err => {
        console.log(err.response.data)
      })
    },
    hideDistrictSuggest () {
      this.suggest.district = false
    },
    search () {
      let params = {}
      if (this.address.name!=''&&this.address.name!=undefined) {
        if (this.address.slug!=undefined) {
          if (this.address.d_slug!=undefined) {
            params = {
              province: this.address.p_slug,
              district: this.address.d_slug,
              area: this.address.slug
            }
          } else {
            params = {
              province: this.address.p_slug,
              district: this.address.slug
            }
          }
        }
      } else if (this.province.slug!=undefined) {
        if (this.district.slug!=undefined) {
          params = {
            province: this.province.slug,
            district: this.district.slug
          }
        } else {
          params = {
            province: this.province.slug
          }
        }
      }
      if (params.province!='') {
        this.$router.push({name: 'search-room', params: params})
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
<style scoped>
  form {
    width: 100%;
    max-width: 600px;
  }
</style>