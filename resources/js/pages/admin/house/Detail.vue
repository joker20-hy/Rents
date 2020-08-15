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
            <div class="holder" v-show="!edit">{{ house.province?house.province.name:'' }}</div>
            <suggest-box :api="'/api/sg/provinces'" :placeholder="'Tỉnh thành'" :value="house.province" :required="true" @change="getProvince" v-show="edit"/>
          </div>
          <div class="col-md-4">
            <label for="">District</label>
            <div class="holder" v-show="!edit">{{ house.district?house.district.name:'' }}</div>
            <suggest-box :api="'/api/sg/districts'" :params="{province: house.province_id}" :value="house.district" :placeholder="'Quận huyện'" :required="true" @change="getDistrict" v-show="edit"/>
          </div>
          <div class="col-md-4">
            <label for="">Area</label>
            <div class="holder" v-show="!edit">{{ house.area?house.area.name:'' }}</div>
            <suggest-box :api="'/api/sg/areas'" :params="{province: house.province_id,district: house.district_id}" :value="house.area" :placeholder="'Khu vực'" @change="getArea" v-show="edit"/>
          </div>
        </div>
        <div class="form-group">
          <label for="">Address detail</label>
          <div class="holder" v-show="!edit">{{ house.address }}</div>
          <input type="text" class="input" v-model="house.address" v-show="edit">
        </div>
        <div class="form-group d-flex">
          <label for="">House for rent?</label>
          <switch-box class="ml-auto" v-model="house.rent" :class="house.rent?'on':''" :locked="!edit"></switch-box>
        </div>
        <div class="form-group" v-show="house.rent">
          <label for="">Direction</label>
          <div class="holder" v-show="!edit">{{ direction?direction.name:'Chưa rõ' }}</div>
          <select class="input" v-model="house.direction" v-show="edit">
            <option value="">Hướng nhà</option>
            <option v-for="dir in directions" :value="dir.id" :key="dir.id">{{ dir.name }}</option>
          </select>
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
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import adapter from '../../../utilities/CKImageAdapter'
import SuggestBox from '../../utilities/SuggestBox'
import ImageLibrary from '../../utilities/ImageLibrary'
import SwitchBox from '../../utilities/SwitchBox'

export default {
  name: 'house-detail',
  components: {
    SuggestBox,
    ImageLibrary,
    SwitchBox
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
      districts: [],
      areas: [],
      house: {},
      description: ''
    }
  },
  created () {
    $eventHub.$on('show-detail-house', this.show)
    this.getDirection()
  },
  computed: {
    TRUE () {
      return $config.TRUE
    },
    FALSE () {
      return $config.FALSE
    },
    direction () {
      return this.directions.find(dir => dir.id==this.house.direction)
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
    getDirection () {
      $request.get('/api/direction')
      .then(res => {
        this.directions = res.data
      })
      .catch(err => {
        console.log(err.response.data.message)
      })
    },
    show (house) {
      this.edit = false
      this.house = house
      this.$modal.show('detail-house')
    },
    update () {
      $request.put(`/api/house/${this.house.id}`, {
        name: this.house.name,
        province_id: this.house.province_id,
        district_id: this.house.district_id,
        area_id: this.house.area_id,
        rent: this.house.rent,
        direction: this.house.direction,
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