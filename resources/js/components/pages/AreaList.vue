<template>
  <div class="container">
    <h1 class="d-flex align-items-end mt-5 py-3">
      Danh sách khu vực
      <button class="btn ml-auto text-light" @click="showCreate" style="font-weight: 600">
        <i class="fas fa-plus"></i> Create
      </button>
    </h1>
    <transition name="slide-fade">
      <form @submit.prevent="store" class="bg-light rounded" v-show="create">
        <div class="row form-group px-3 py-2">
          <div class="col-md-3">
            <select class="input" v-model="area.province_id" @change="getDistricts(area.province_id),area.district_id=''" required>
              <option value="" selected>Tỉnh/thành</option>
              <option v-for="prov in provinces" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="input" v-model="area.district_id" required>
              <option value="" selected>Quận/Huyện</option>
              <option v-for="dist in districts" :key="dist.id" :value="dist.id">{{ dist.name }}</option>
            </select>
          </div>
          <div class="col-md-4">
            <input type="text" class="input" v-model="area.name" placeholder="Area name" required>
          </div>
          <div class="col-md-2 d-flex align-items-center">
            <button class="btn text-primary">Create</button>
            <button type="button" class="btn text-danger" @click="hideCreate">Cancel</button>
          </div>
        </div>
      </form>
    </transition>
    <table class="records-list">
      <thead>
        <th>Name</th>
        <th>Slug</th>
        <th>Province</th>
        <th>District</th>
        <th>Actions</th>
      </thead>
      <transition-group name="slide-fade" tag="tbody">
        <tr v-for="area in areas" :key="area.id">
          <td>
            <div class="holder" v-show="!area.edit">{{ area.name }}</div>
            <input type="text" class="input" v-model="area.name" v-show="area.edit">
          </td>
          <td>
            <div class="holder" v-show="!area.edit">{{ area.slug }}</div>
            <input type="text" class="input" v-model="area.slug" v-show="area.edit">
          </td>
          <td>
            <div class="holder">{{ area.province.name }}</div>
          </td>
          <td>
            <div class="holder">{{ area.district.name }}</div>
          </td>
          <td>
            <button class="btn text-primary" @click="choose(area),area.edit=true" v-show="!area.edit">Edit</button>
            <button class="btn text-primary" v-show="area.edit" @click="update(area)">Update</button>
            <button class="btn text-danger" v-show="area.edit" @click="area.edit=false">Cancel</button>
            <button class="btn text-danger" v-show="!area.edit" @click="showDestroy(),choose(area)">Delete</button>
          </td>
        </tr>
      </transition-group>
    </table>
    <div class="py-4 text-center mx-auto" v-if="loading">
      <span class="p-2 bg-light rounded-circle">
        <i class="fas fa-spinner fa-pulse fa-lg text-primary"></i>
      </span>
    </div>
    <paginate
      v-model="page"
      :page-count="page_count"
      :page-range="3"
      :margin-pages="2"
      :click-handler="changePage"
      :prev-text="'Prev'"
      :next-text="'Next'"
      :container-class="'pagination'"
      :page-class="'page-item'">
    </paginate>
    <!-- destroy confirm -->
    <modal name="destroy-form" :width="300" :class="['text-center']">
      <h3 class="px-3 py-4 text-danger">Delete confirm</h3>
      <div>
        Are you sure?
      </div>
      <div class="form-group pt-4">
        <button class="btn text-primary" @click="hideDestroy">Cancel</button>
        <button class="btn text-muted" @click="destroy">Confirm</button>
      </div>
    </modal>
  </div>
</template>
<script>
import { $auth } from '../../auth.js'

export default {
  data () {
    return {
      create: false,
      page: 1,
      page_count: 1,
      per_page: 1,
      chosen: '',
      areas: [],
      districts: [],
      provinces: [],
      loading: false,
      area: {
        province_id: '',
        district_id: '',
        name: ''
      }
    }
  },
  mounted () {
    this.getProvinces()
    this.list()
  },
  methods: {
    showCreate () {
      this.area.name = ''
      this.area.province_id = ''
      this.area.district_id = ''
      this.create = true
    },
    hideCreate () {
      this.create = false
    },
    showDestroy () {
      this.$modal.show('destroy-form')
    },
    hideDestroy () {
      this.$modal.hide('destroy-form')
    },
    getProvinces () {
      $auth.request.get('/api/province/all')
      .then(res => {
        this.provinces = res.data
      })
      .catch(err => {
        console.log(err.response.data.message)
      })
    },
    getDistricts (province_id) {
      $auth.request.get(`/api/district/${province_id}`)
      .then(res => {
        this.districts = res.data.data
      })
      .catch(err => {
        console.log(err.response.data.message)
      })
    },
    changePage (page) {
      this.page = page
      this.list()
    },
    list () {
      this.loading = true
      $auth.request.get(`/api/area?page=${this.page}`)
      .then(res => {
        this.page_count = res.data.last_page
        this.total = res.data.total
        this.per_page = res.data.per_page
        res.data.data.forEach(area => area.edit=false)
        this.areas = res.data.data
      })
      .catch(err => {
        console.log(err.response.data.message)
        this.error('Unable to get area list')
      })
      .finally(() => {
        this.loading = false
      })
    },
    store () {
      $auth.request.post('/api/area', {
        province_id: this.area.province_id,
        district_id: this.area.district_id,
        name: this.area.name
      })
      .then(res => {
        this.total = this.total + 1
        if (this.total % this.per_page==1) {
          this.changePage(this.page + 1)
        } else {
          res.data.edit = false
          this.areas.push(res.data)
        }
        this.success('New area has been created')
      })
      .catch(err => {
        console.log(err.response.data.message)
        this.error('Unable to create new area')
      })
    },
    choose (area) {
      this.chosen = {
        id: area.id,
        name: area.name,
        slug: area.slug,
      }
    },
    update (area) {
      $auth.request.put(`/api/area/${area.id}`, {
        name: area.name,
        slug: area.slug
      })
      .then(res => {
        area.edit = false
        this.success('Update successfully')
      })
      .catch(err => {
        area.name = this.chosen.name
        area.slug = this.chosen.slug
        console.log(err.response.data.message)
        this.error('Unable to update this record')
      })
    },
    destroy () {
      $auth.request.delete(`/api/area/${this.chosen.id}`)
      .then(res => {
        this.areas = this.areas.filter(area => area.id!=this.chosen.id)
        this.success('Delete successfully')
        this.hideDestroy()
      })
      .catch(err => {
        console.log(err.response.data.message)
        this.error("Unable to delete this record")
      })
    },
    success (message) {
      $eventHub.$emit('success-alert', {
        title: 'Thành công',
        message: message,
        timeout: 3600
      })
    },
    error (message) {
      $eventHub.$emit('error-alert', {
        title: 'Không thành công',
        message: message
      })
    }
  }
}
</script>