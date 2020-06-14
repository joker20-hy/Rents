<template>
  <modal name="create-house" :width="720">
    <div class="p-3" style="max-height: 80vh;overflow-y: scroll;overflow-x: hidden;">
      <form @submit.prevent="store">
        <h3 class="d-flex sticky-top bg-white text-primary">
          Create house
          <div class="ml-auto">
            <button class="btn text-primary">Create</button>
            <button type="button" class="btn text-danger" @click="hide">Cancel</button>
          </div>
        </h3>
        <label for="">
          House Name
          <span class="text-danger" title="Required feild">*</span>
        </label>
        <div class="form-group">
          <input type="text" class="input" v-model="house.name" placeholder="House name" required>
        </div>
        <div class="form-group row">
          <div class="col-md-4">
            <label for="">
              Province
              <span class="text-danger" title="Required feild">*</span>
            </label>
            <suggest-box :api="'/api/sg/provinces'" :placeholder="'Tỉnh thành'" @change="getProvince" :required="true"/>
          </div>
          <div class="col-md-4">
            <label for="">
              District
              <span class="text-danger" title="Required feild">*</span>
            </label>
            <suggest-box :api="'/api/sg/districts'" :params="{province: house.province_id}" :placeholder="'Quận huyện'" @change="getDistrict" :required="true"/>
          </div>
          <div class="col-md-4">
            <label for="">Area</label>
            <suggest-box :api="'/api/sg/areas'" :params="{province: house.province_id,district: house.district_id}" :placeholder="'Khu vực'" @change="getArea"/>
          </div>
        </div>
        <div class="form-group">
          <label for="">
            Address detail
            <span class="text-danger" title="Required feild">*</span>
          </label>
          <input type="text" class="input" v-model="house.address" placeholder="House Address" required>
        </div>
        <div class="form-group d-flex align-items-center py-2">
          <label for="" class="m-0">Houses images</label>
          <div class="position-relative pl-3">
            <button type="button" class="btn text-primary" onclick="clickTarget('#images')">
              <i class="fas fa-cloud-upload-alt fa-lg"></i>
            </button>
            <input type="file" id="images" class="d-none" ref="images" @change="getImages" accept="image/*" multiple>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-4" v-for="(temp, index) in bucket" :key="index">
            <img :src="temp" alt="" class="w-100">
          </div>
        </div>

        <div class="form-group d-flex">
          <label for="">This is a house for rent ?</label>
          <switch-input class="ml-auto" :status="house.rent" @click="house.rent=!house.rent"/>
        </div>
        <div class="form-group" v-show="house.rent">
          <label for="">Price</label>
          <input type="number" class="input" v-model="house.price" placeholder="Price of house">
        </div>
        <div class="form-group" v-show="house.rent">
          <label for="">Direction</label>
          <select class="input" v-model="house.direction">
            <option value="">Direction of the house</option>
            <option v-for="dir in directions" :value="dir.id" :key="dir.id">{{ dir.name }}</option>
          </select>
        </div>
        <div class="form-group" v-show="house.rent">
          <label for="">House description</label>
          <ckeditor :editor="editor" v-model="house.description" :config="editorConfig"></ckeditor>
        </div>
      </form>
    </div>
  </modal>
</template>
<script>
import { $auth } from '../../../auth'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import adapter from '../../../utilities/CKImageAdapter'
import SuggestBox from '../../utilities/SuggestBox'
import SwitchInput from '../../utilities/SwitchInput'

export default {
  name: 'create-house',
  components: {
    SuggestBox,
    SwitchInput
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
        rent: false,
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
    $eventHub.$on('show-create-house', this.show)
    this.getDirection()
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
    getDirection () {
      $auth.request.get('/api/direction')
      .then(res => {
        this.directions = res.data
      })
      .catch(err => {
        console.log(err.response.data.message)
      })
    },
    show () {
      this.$modal.show('create-house')
    },
    hide () {
      this.$modal.hide('create-house')
    },
    required (feilds) {
      for(let feild of feilds) {
        if(this.house[feild] == '') return false
      }
      return true
    },
    store () {
      let data = new FormData();
      for( var i = 0; i < this.images.length; i++ ){
        data.append(`images[${i}]`, this.images[i]);
      }
      data.append('name', this.house.name)
      data.append('province_id', this.house.province_id)
      data.append('district_id', this.house.district_id)
      data.append('area_id', this.house.area_id)
      data.append('address', this.house.address)
      data.append('rent', this.house.rent ? $config.TRUE : $config.FALSE)
      data.append('price', this.house.price)
      data.append('direction', this.house.direction)
      data.append('description', this.house.description)
      $auth.request.post('/api/house', data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(res => {
        this.hide()
        this.$store.commit('houses/add', res.data)
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
<style scoped>
  label {
    font-weight: 600;
  }
</style>