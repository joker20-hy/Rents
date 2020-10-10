<template>
  <div class="container" style="margin-top: 15px;padding-bottom: 60px" v-if="house!=undefined">
    <image-library class="form-group" :images="house.images" :updateApi="`/api/house/${house.id}/images`" :deleteApi="`/api/house/${house.id}/images`" @updated="updateImages"/>
    <form class="col-lg-10 col-xl-8" detail-form @submit.prevent="update()">
      <div class="d-flex">
        <button class="btn text-primary" v-show="edit">
          <i class="far fa-save"></i> Lưu
        </button>
        <button type="button" class="btn ml-auto" :class="edit?'text-danger':'text-primary'" @click="edit=!edit">
          <span v-if="!edit" style="font-size: small"><i class="fas fa-pen"></i> Chỉnh sửa</span><span v-else>Hủy</span>
        </button>
      </div>
      <div class="form-group">
        <label for="">Tên</label>
        <div class="holder" v-show="!edit">{{ house.name }}</div>
        <input type="text" class="input" v-model="house.name" v-show="edit">
      </div>
      <div class="form-group" v-show="!edit">
        <label for="">Địa chỉ</label>
        <div class="holder">{{ house.address_detail }}</div>
      </div>
      <div class="form-group row" :class="edit?'d-flex':'d-none'">
        <div class="col-md-4">
          <label for="">Tỉnh thành</label>
          <suggest-box :api="'/api/sg/provinces'" :placeholder="'Tỉnh thành'" :value="house.province" @change="getProvince" :required="true"/>
        </div>
        <div class="col-md-4">
          <label for="">Quận huyện</label>
          <suggest-box :api="'/api/sg/districts'" :params="{province: house.province_id}" :value="house.district" :placeholder="'Quận huyện'" @change="getDistrict" :required="true"/>
        </div>
        <div class="col-md-4">
          <label for="">Khu vực</label>
          <suggest-box :api="'/api/sg/areas'" :params="{province: house.province_id,district: house.district_id}" :value="house.area" :placeholder="'Khu vực'" @change="getArea"/>
        </div>
      </div>
      <div class="form-group" v-show="edit">
        <label for="">Chi tiết địa chỉ</label>
        <input type="text" class="input" v-model="house.address" placeholder="Địa chỉ chi tiết">
      </div>
      <!--  -->
      <label>Dịch vụ của nhà trọ <span class="text-danger" title="Required feild">*</span></label>
      <small>(Những dịch vụ mà người thuê trọ cần trả trong quá trình thuê nhà)</small>
      <choose-service :list="services" :chosens="serviceIds" @delete="deleteService" @add="addService" :editable="edit"/>
      <!--  -->
      <div class="form-group d-flex">
        <label for="">Cho thuê nguyên căn?</label>
        <switch-box v-model="house.rent" class="ml-auto" :class="house.rent==1?'on':''" :locked="!edit"/>
      </div>
      <div class="form-group" v-show="house.rent==1">
        <label for="">Hướng nhà</label>
        <div class="holder" v-show="!edit">{{ direction?direction.name:'Chưa rõ' }}</div>
        <select class="input" v-model="house.direction" v-show="edit">
          <option value="">Hướng nhà</option>
          <option v-for="dir in directions" :value="dir.id" :key="dir.id">{{ dir.name }}</option>
        </select>
      </div>
      <div class="form-group" v-show="house.rent==1">
        <label for="">Price</label>
        <div class="holder" v-show="!edit">{{ house.price?`${house.price} vnđ`:`Chưa rõ` }}</div>
        <input type="number" class="input" v-model="house.price" v-show="edit" placeholder="Price of the house">
      </div>
      <div class="form-group" v-show="house.rent==1">
        <label for="">Description</label>
        <div class="holder content-html" v-show="!edit" v-html="house.description"></div>
        <div v-show="edit">
          <ckeditor :editor="editor" v-model="house.description" :config="editorConfig"></ckeditor>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import adapter from '../../../utilities/CKImageAdapter'
import SuggestBox from '../../utilities/SuggestBox'
import SwitchBox from '../../utilities/SwitchBox'
import ImageLibrary from '../../utilities/ImageLibrary'
import ChooseService from '../../admin/house/Services'
export default {
  components: {
    SuggestBox,
    SwitchBox,
    ImageLibrary,
    ChooseService
  },
  data () {
    return {
      id: '',
      edit: false,
      editor: ClassicEditor,
      editorConfig: {
        extraPlugins: [ adapter ],
      },
      directions: [],
      provinces: [],
      districts: [],
      areas: [],
      description: '',
      add_services: [],
      remove_services: []
    }
  },
  watch: {
    "$route.params.id": {
      handler (id) {
        this.id = id
        if (this.house==undefined) this.getHouse()
      },
      deep: true,
      immediate: true
    }
  },
  computed: {
    house () {
      return this.$store.getters['houses/find'](this.id)
    },
    direction () {
      return this.directions.find(dir => dir.id==this.house.direction)
    },
    services () {
      return this.$store.getters['services/services']
    },
    serviceIds () {
      return select(this.house.house_services, ['service_id', 'price'])
    },
    data() {
      return {
        id: this.house.id,
        name: this.house.name,
        province_id: this.house.province_id,
        district_id: this.house.district_id,
        area_id: this.house.area_id,
        address: this.house.address,
        rent: this.house.rent,
        price: this.house.price,
        acreage: this.house.acreage,
        direction: this.house.direction,
        description: this.house.description,
        add_services: this.add_services,
        remove_services: this.remove_services
      }
    }
  },
  created () {
    this.getDirection()
    this.getServices()
  },
  methods: {
    getDirection () {
      ajax().get('/api/direction')
      .then(res => this.directions = res.data )
      .catch(err => console.log(err.response.data.message))
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
      .catch(err => console.log(err.response.data.message))
    },
    getProvince (province) {
      this.house.province_id = province.id
    },
    getDistrict (district) {
      this.house.district_id = district.id
    },
    getArea (area) {
      this.house.area_id = area.id
    },
    getHouse () {
      ajax().get(`/api/house/${this.id}`)
      .then(res => {
        res.data.images = res.data.images==null?[]:JSON.parse(res.data.images)
        res.data.description = res.data.description==null?'':utf8.decode(res.data.description)
        this.$store.commit('houses/houses', [res.data])
      })
      .catch(err => console.log(err.response.data.message) )
    },
    updateImages (images) {
      this.house.images = images
    },
    update () {
      ajax().put(`/api/house/${this.house.id}`, this.data)
      .then(res => {
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Đã chỉnh sửa thông tin thành công',
          timeout: 3000
        })
        this.$router.push({name: 'owner-list-house'})
      })
      .catch(err => console.log(err))
    },
    addService(ids) {
      this.add_services = ids
    },
    deleteService(ids) {
      this.remove_services = ids
    }
  }
}
</script>
<style scoped>
  label {
    font-weight: 600;
  }
</style>