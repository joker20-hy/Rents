<template>
  <div class="container mb-5">
    <form @submit.prevent="store()" class="col-lg-10 col-xl-8" detail-form id="create-house">
      <h3 class="py-3 px-2 bg-primary text-light" style="margin-left: -25px;margin-right: -25px;">
        Thêm nhà
      </h3>
      <div class="row" v-if="bucket.length>0">
        <div class="col-4" v-for="(temp, index) in bucket" :key="index">
          <img :src="temp" alt="" class="w-100">
        </div>
      </div>
      <div class="form-group d-flex align-items-center py-2">
        <label for="" class="m-0">Ảnh nhà</label>
        <div class="position-relative pl-3">
          <button type="button" class="btn text-primary" onclick="clickTarget('#images')">
            <i class="far fa-images fa-lg"></i>
          </button>
          <input type="file" id="images" class="d-none" ref="images" @change="getImages" accept="image/*" multiple>
        </div>
      </div>
      <label for="">
        Tên nhà
        <span class="text-danger" title="Required feild">*</span>
      </label>
      <div class="form-group">
        <input type="text" class="input" v-model="house.name" placeholder="House name" required>
      </div>
      <div class="form-group row">
        <div class="col-md-4">
          <label for="">
            Tỉnh thành <span class="text-danger" title="Required feild">*</span>
          </label>
          <suggest-box :api="'/api/sg/provinces'" :placeholder="'Tỉnh thành'" @change="getProvince" :required="true"/>
        </div>
        <div class="col-md-4">
          <label for="">
            Quận huyện <span class="text-danger" title="Required feild">*</span>
          </label>
          <suggest-box :api="'/api/sg/districts'" :params="{province: house.province_id}" :placeholder="'Quận huyện'" @change="getDistrict" :required="true"/>
        </div>
        <div class="col-md-4">
          <label for="">Khu vực <span class="text-danger" title="Required feild">*</span></label>
          <suggest-box :api="'/api/sg/areas'" :params="{province: house.province_id,district: house.district_id}" :placeholder="'Khu vực, đường, phố'" @change="getArea"/>
        </div>
      </div>
      <div class="form-group">
        <label for="">
          Địa chỉ chi tiết
          <span class="text-danger" title="Required feild">*</span>
        </label>
        <input type="text" class="input" v-model="house.address" placeholder="vd: số 20 ngõ 20" required>
      </div>
      <label>Dịch vụ của nhà trọ <span class="text-danger" title="Required feild">*</span></label>
      <small>(Những dịch vụ mà người thuê trọ cần trả trong quá trình thuê nhà)</small>
      <choose-service :list="services"/>
      <div class="form-group d-flex">
        <label for="">Nhà để cho thuê?</label>
        <switch-box v-model="house.rent" class="ml-auto" :class="house.rent==1?'on':''"/>
      </div>
      <div class="form-group" v-show="house.rent">
        <label for="">Giá</label>
        <input type="number" class="input" v-model="house.price" placeholder="Price of house">
      </div>
      <div class="form-group" v-show="house.rent">
        <label for="">Hướng nhà</label>
        <select class="input" v-model="house.direction">
          <option value="">Hướng nhà</option>
          <option v-for="dir in directions" :value="dir.id" :key="dir.id">{{ dir.name }}</option>
        </select>
      </div>
      <div class="form-group" v-show="house.rent">
        <label for="">Mô tả về ngôi nhà</label>
        <ckeditor :editor="editor" v-model="house.description" :config="editorConfig"></ckeditor>
      </div>
      <div class="form-group d-flex">
        <button class="btn btn-outline-primary ml-auto">Thêm</button>
      </div>
    </form>
  </div>
</template>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import adapter from '../../../utilities/CKImageAdapter'
import SuggestBox from '../../utilities/SuggestBox'
import SwitchBox from '../../utilities/SwitchBox'
import ChooseService from '../../admin/house/Services'
export default {
  components: {
    SuggestBox,
    SwitchBox,
    ChooseService
  },
  data () {
    return {
      editor: ClassicEditor,
      editorConfig: {
        extraPlugins: [ adapter ],
      },
      directions: [],
      provinces: [],
      districts: [],
      areas: [],
      bucket: [],
      images: [],
      house: {
        name: '',
        rent: 0,
        province_id: '',
        district_id: '',
        area_id: '',
        price: '',
        direction: '',
        description: '',
        address: ''
      }
    }
  },
  created () {
    this.getDirection()
    this.getServices()
  },
  computed: {
    services () {
      return this.$store.getters['services/services']
    },
    data () {
      let data = new FormData();
      for( var i = 0; i < this.images.length; i++ ){
        data.append(`images[${i}]`, this.images[i]);
      }
      data.append('name', this.house.name)
      data.append('province_id', this.house.province_id)
      data.append('district_id', this.house.district_id)
      data.append('area_id', this.house.area_id)
      data.append('address', this.house.address)
      data.append('rent', parseInt(this.house.rent))
      data.append('price', this.house.price)
      data.append('direction', this.house.direction)
      data.append('description', this.house.description)
      let count = 0
      this.services.forEach(serv => {
        if (serv.checked) data.append(`services[${count++}]`, JSON.stringify({
          id: serv.id,
          price: serv.price
        }))
      })
      return data
    }
  },
  methods: {
    getProvince (obj) {
      this.house.province_id = obj.id
    },
    getDistrict (obj) {
      this.house.district_id = obj.id
    },
    getArea (obj) {
      this.house.area_id = obj.id
    },
    getImages (e) {
      this.images = e.target.files
      this.bucket = []
      for( var i = 0; i < this.images.length; i++ ){
        this.bucket.push(URL.createObjectURL(this.images[i]))
      }
    },
    getServices() {
      ajax().get('/api/service')
      .then(res => {
        res.data.forEach(item => {
          item.price = ''
          item.checked = false
        })
        this.$store.commit('services/services', res.data)
      })
      .catch(err => {console.log(err)})
    },
    getDirection () {
      ajax().get('/api/direction')
      .then(res => {
        this.directions = res.data
      })
      .catch(err => console.log("unable to get direction list"))
    },
    store () {
      ajax().post('/api/house', this.data, {
        headers: {'Content-Type': 'multipart/form-data'}
      })
      .then(res => {
        this.$router.push({name: 'owner-list-house'})
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'Create house successfully',
          timeout: 3000
        })
      })
      .catch(err => {
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to store house',
          timeout: 3000
        })
      })
    }
  }
}
</script>