<template>
  <div class="container">
    <h1 class="d-flex align-items-end mt-5 py-3">
      Danh sách quận/huyện
      <button class="btn ml-auto text-light" @click="showCreate" style="font-weight: 600">
        <i class="fas fa-plus"></i> Create
      </button>
    </h1>
    <transition name="slide-fade">
      <form @submit.prevent="store" class="bg-light rounded" v-show="create">
        <div class="row form-group px-3 py-2">
          <div class="col-md-4">
            <select class="input" v-model="district.province_id" required>
              <option value="" selected>Tỉnh/thành</option>
              <option v-for="prov in provinces" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
            </select>
          </div>
          <div class="col-md-6">
            <input type="text" class="input" v-model="district.name" placeholder="District name" required>
          </div>
          <div class="col-md-2 d-flex align-items-center">
            <button class="btn text-primary">Create</button>
            <button type="button" class="btn text-danger" @click="hideCreate">Cancel</button>
          </div>
        </div>
      </form>
    </transition>
    <table class="table table-borderless records-list">
      <thead>
        <th>STT</th>
        <th>Tên</th>
        <th>Slug</th>
        <th>Tỉnh thành</th>
        <th>Hành động</th>
      </thead>
      <transition-group name="slide-fade" tag="tbody">
        <tr v-for="dist in districts" :key="dist.id">
          <td>{{ dist.id }}</td>
          <td>
            <div v-show="!dist.edit" class="holder">{{ dist.name }}</div>
            <input type="text" class="input" v-model="dist.name" v-show="dist.edit">
          </td>
          <td>
            <div v-show="!dist.edit" class="holder">{{ dist.slug }}</div>
            <input type="text" class="input" v-model="dist.slug" v-show="dist.edit">
          </td>
          <td>
            <div class="holder">{{ dist.province.name }}</div>
          </td>
          <td>
            <button class="btn text-primary" @click="choose(dist),dist.edit=true" v-show="!dist.edit">Edit</button>
            <button class="btn text-primary" v-show="dist.edit" @click="update(dist)">Update</button>
            <button class="btn text-danger" v-show="dist.edit" @click="dist.edit=false">Cancel</button>
            <button class="btn text-danger" @click="choose(dist),showDestroy()" v-show="!dist.edit">Delete</button>
          </td>
        </tr>
      </transition-group>
    </table>
    <div class="py-4 text-center mx-auto" v-show="loading">
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
    <!--  -->
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
  name: 'district-list',
  data () {
    return {
      chosen: '',
      page: 1,
      page_count: 1,
      per_page: 1,
      provinces: [],
      districts: [],
      create: false,
      loading: false,
      district: {
        name: '',
        province_id: ''
      }
    }
  },
  mounted () {
    this.list()
    this.getProvinces()
  },
  methods: {
    showCreate () {
      this.create = true
      this.district.name = this.district.province_id = ''
    },
    hideCreate () {
      this.create = false
    },
    showDestroy (dist) {
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
    changePage (page) {
      this.page = page
      this.list(page)
    },
    choose (dist) {
      this.chosen = {
        id: dist.id,
        name: dist.name,
        slug: dist.slug
      }
    },
    list (page=1) {
      this.loading = true
      $auth.request.get(`/api/district?page=${page}`)
      .then(res => {
        this.page_count = res.data.last_page
        this.total = res.data.total
        this.per_page = res.data.per_page
        res.data.data.forEach(dist => dist.edit=false)
        this.districts = res.data.data
        this.loading = false
      })
      .catch(err => {
        this.loading = false
        this.error('Unable to get district list')
      })
    },
    store () {
      $auth.request.post('/api/district', this.district)
      .then(res => {
        if (this.districts.length==this.per_page) {
          let target = Math.floor((this.total / this.per_page)) + 1
          this.page_count = target
          this.changePage(target)
        } else {
          res.data.edit = false
          this.districts.push(res.data)
        }
        this.district.name = this.district.province_id = ''
        this.success('District has been created')
      })
      .catch(err => {
        console.log(err.response.data.message)
        this.error('Unable to create new district')
      })
    },
    update (dist) {
      $auth.request.put(`/api/district/${dist.id}`, {
        name: dist.name,
        slug: dist.slug
      })
      .then(res => {
        dist.edit = false
        this.success('District has been updated')
      })
      .catch(err => {
        dist.name = this.chosen.name
        dist.slug = this.chosen.slug
        console.log(err.response.data.message)
        this.error('Unable to update this district')
      })
    },
    destroy () {
      $auth.request.delete('/api/district/'+this.chosen.id)
      .then(res => {
        this.districts = this.districts.filter(prov => prov.id!=this.chosen.id)
        this.success('District has been deleted')
        this.hideDestroy()
      })
      .catch(err => {
        console.log(err.response.data.message)
        this.error('Unable to delete this district')
      })
    },
    success (message) {
      $eventHub.$emit('success-alert', {
        title: 'Success',
        message: message,
        timeout: 3000
      })
    },
    error (message) {
      $eventHub.$emit('error-alert', {
        title: 'Error',
        message: message
      })
    }
  }
}
</script>
