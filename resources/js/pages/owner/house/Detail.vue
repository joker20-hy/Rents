<template>
  <div contain-box class="bg-white mt-2" v-if="house!=undefined">
    <form @submit.prevent="update()">
      <div class="bg-white d-flex sticky-top py-2">
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
      <div class="form-group" v-if="house.contact">
        <label>Số điện thoại</label>
        <div v-if="!edit" class="holder">{{ house.contact.phone==''?'Chưa rõ':house.contact.phone }}</div>
        <input v-else type="number" class="input" v-model="house.contact.phone" placeholder="vd: 0123456789" required>
      </div>
      <div class="form-group">
        <label>Liên hệ khác</label>
        <div v-if="!edit" class="holder">
          {{ house.contact.others==''?`Hiện chưa rõ`:house.contact.others }}
        </div>
        <textarea v-else class="input" v-model="house.contact.others" placeholder="Liên hệ khác"></textarea>
      </div>
    </form>
    <label class="mt-2">Ảnh</label>
    <image-library
      class="form-group px-3"
      :images="house.images"
      :updateApi="`/api/house/${house.id}/images`"
      :deleteApi="`/api/house/${house.id}/images`"
      @updated="updateImages"
    />
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
      return this.house.house_services.map(item => {
        return {service_id: item.service_id, price: item.price}
      })
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
        remove_services: this.remove_services,
        contact: this.house.contact
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
        res.data.contact=res.data.contact==null?{phone:'',others:''}:JSON.parse(res.data.contact)
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