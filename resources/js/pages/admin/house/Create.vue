<template>
  <div class="container">
    <form @submit.prevent="store()" id="create-house">
      <h3 class="py-3 px-2 bg-primary text-light" style="margin-left: -25px;margin-right: -25px;">
        Create house
      </h3>
      <div class="row" v-if="bucket.length>0">
        <div class="col-4" v-for="(temp, index) in bucket" :key="index">
          <img :src="temp" alt="" class="w-100">
        </div>
      </div>
      <div class="form-group d-flex align-items-center py-2">
        <label for="" class="m-0">Images</label>
        <div class="position-relative pl-3">
          <button type="button" class="btn text-primary" onclick="clickTarget('#images')">
            <i class="far fa-images fa-lg"></i>
          </button>
          <input type="file" id="images" class="d-none" ref="images" @change="getImages" accept="image/*" multiple>
        </div>
      </div>
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
            Province <span class="text-danger" title="Required feild">*</span>
          </label>
          <suggest-box :api="'/api/sg/provinces'" :placeholder="'Tỉnh thành'" @change="getProvince" :required="true"/>
        </div>
        <div class="col-md-4">
          <label for="">
            District <span class="text-danger" title="Required feild">*</span>
          </label>
          <suggest-box :api="'/api/sg/districts'" :params="{province: house.province_id}" :placeholder="'Quận huyện'" @change="getDistrict" :required="true"/>
        </div>
        <div class="col-md-4">
          <label for="">Area <span class="text-danger" title="Required feild">*</span></label>
          <suggest-box :api="'/api/sg/areas'" :params="{province: house.province_id,district: house.district_id}" :placeholder="'Khu vực, đường, phố'" @change="getArea"/>
        </div>
      </div>
      <div class="form-group">
        <label for="">
          Address detail
          <span class="text-danger" title="Required feild">*</span>
        </label>
        <input type="text" class="input" v-model="house.address" placeholder="vd: số 20 ngõ 20" required>
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
          <option value="">Hướng nhà</option>
          <option v-for="dir in directions" :value="dir.id" :key="dir.id">{{ dir.name }}</option>
        </select>
      </div>
      <div class="form-group" v-show="house.rent">
        <label for="">House description</label>
        <ckeditor :editor="editor" v-model="house.description" :config="editorConfig"></ckeditor>
      </div>
      <div class="form-group d-flex">
        <button class="btn btn-outline-primary ml-auto">Create</button>
      </div>
    </form>
  </div>
</template>
<script>
import { $auth } from '../../../utilities/request/request'
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
        this.$router.push({name: 'house-list'})
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
    font-weight: 600
  }
  #create-house {
    margin: auto;
    margin-top: 15px;
    padding: 20px 15px;
    max-width: 100%;
    width: 720px;
    background-color: var(--light);
    box-shadow: 0px 1px 3px #aaa;
  }
</style>