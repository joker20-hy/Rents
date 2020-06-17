<template>
  <modal name="detail-house" :width="720" @before-close="leaveEdit">
    <form @submit.prevent="update()" style="max-height: 80vh;overflow-y: scroll;overflow-x: hidden;">
      <h3 class="d-flex sticky-top bg-white text-primary p-3 mb-0">
        House detail
        <div class="ml-auto">
          <button type="button" class="btn text-primary" @click="enterEdit()" v-show="!edit">Edit</button>
          <button class="btn text-primary" v-show="edit">Update</button>
          <button type="button" class="btn text-danger" v-show="edit" @click="leaveEdit()">Cancel</button>
          <button type="button" class="btn text-danger" @click="$modal.hide('detail-house')">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </h3>
      <div class="pb-2 px-3">
        <image-library class="form-group" :images="house.images" :updateApi="`/api/house/${house.id}/images`" :deleteApi="`/api/house/${house.id}/images`" @updated="updateImages"/>
        <div class="form-group">
          <label for="">House name</label>
          <div class="holder" v-show="!edit">{{ house.name }}</div>
          <input type="text" class="input" v-model="house.name" v-show="edit">
        </div>
        <div class="form-group row">
          <div class="col-md-4">
            <label for="">Province</label>
            <div class="holder" v-show="!edit">{{ chosen_province.name }}</div>
            <suggest-box :api="'/api/sg/provinces'" :placeholder="'Tỉnh thành'" :value="chosen_province" :required="true" @change="getProvince" v-show="edit"/>
          </div>
          <div class="col-md-4">
            <label for="">District</label>
            <div class="holder" v-show="!edit">{{ chosen_district.name }}</div>
            <suggest-box :api="'/api/sg/districts'" :params="{province: house.province_id}" :value="chosen_district" :placeholder="'Quận huyện'" :required="true" @change="getDistrict" v-show="edit"/>
          </div>
          <div class="col-md-4">
            <label for="">Area</label>
            <div class="holder" :class="chosen_area.name==''?'text-muted':''" v-show="!edit">{{ chosen_area.name==''? 'Chưa rõ':chosen_area.name }}</div>
            <suggest-box :api="'/api/sg/areas'" :params="{province: house.province_id,district: house.district_id}" :value="chosen_area" :placeholder="'Khu vực'" @change="getArea" v-show="edit"/>
          </div>
        </div>
        <div class="form-group">
          <label for="">Address detail</label>
          <div class="holder" v-show="!edit">{{ house.address }}</div>
          <input type="text" class="input" v-model="house.address" v-show="edit">
        </div>
        <div class="form-group d-flex">
          <label for="">House for rent?</label>
          <switch-input class="ml-auto" :status="house.rent" @click="house.rent=!house.rent" :locked="!edit"/>
        </div>
        <div class="form-group" v-show="house.rent">
          <label for="">Price</label>
          <div class="holder" v-show="!edit&&house.price">{{ `${house.price} vnđ` }}</div>
          <input type="number" class="input" v-model="house.price" v-show="edit" placeholder="Price of the house">
        </div>
        <div class="form-group" v-show="house.rent">
          <label for="">Description</label>
          <div class="holder content-html" v-show="!edit" v-html="house.description"></div>
          <div v-show="edit">
            <ckeditor :editor="editor" v-model="house.description" :config="editorConfig"></ckeditor>
          </div>
        </div>
      </div>
    </form>
  </modal>
</template>
<script>
import { $auth } from '../../../auth'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import adapter from '../../../utilities/CKImageAdapter'
import SuggestBox from '../../utilities/SuggestBox'
import SwitchInput from '../../utilities/SwitchInput'
import ImageLibrary from '../../utilities/ImageLibrary'

export default {
  name: 'house-detail',
  components: {
    SuggestBox,
    SwitchInput,
    ImageLibrary
  },
  data () {
    return {
      edit: false,
      editor: ClassicEditor,
      editorConfig: {
        extraPlugins: [ adapter ],
      },
      directions: [],
      provinces: [],
      chosen_province: {
        id: '',
        name: ''
      },
      districts: [],
      chosen_district: {
        id: '',
        name: ''
      },
      areas: [],
      chosen_area: {
        id: '',
        name: ''
      },
      house: {},
      description: ''
    }
  },
  created () {
    $eventHub.$on('show-detail-house', this.show)
  },
  computed: {
    TRUE () {
      return $config.TRUE
    },
    FALSE () {
      return $config.FALSE
    }
  },
  methods: {
    enterEdit () {
      this.edit=true
    },
    leaveEdit () {
      this.edit=false
    },
    getProvince (obj) {
      this.house.province_id = obj.id
    },
    getDistrict (obj) {
      this.house.district_id = obj.id
    },
    getArea (obj) {
      this.house.area_id = obj.id
    },
    updateImages (images) {
      this.house.images = images
    },
    show (house) {
      this.edit = false
      this.house = house
      this.chosen_province.id = house.province.id
      this.chosen_province.name = house.province.name
      this.chosen_district.id = house.district.id
      this.chosen_district.name = house.district.name

      this.chosen_area = house.area==null? this.chosen_area : {
        id: house.area.id,
        name: house.area.name
      }
      this.$modal.show('detail-house')
    },
    update () {
      $auth.request.put(`/api/house/${this.house.id}`, {
        name: this.house.name,
        province_id: this.house.province_id,
        district_id: this.house.district_id,
        area_id: this.house.area_id,
        rent: this.house.rent?this.TRUE:this.FALSE,
        price: this.house.price==null?'':this.house.price,
        description: this.house.description
      })
      .then(res => {
        this.leaveEdit()
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'Update info of this house successfully'
        })
      })
      .catch(err => {
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to update info of this house'
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