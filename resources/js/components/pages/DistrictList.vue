<template>
  <div class="container">
    <h1 class="d-flex align-items-end mt-5">
      Danh sách quận/huyện
      <button class="btn ml-auto" @click="showCreate">Create</button>
    </h1>
    <table class="table table-borderless text-center">
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
          <td>{{ dist.name }}</td>
          <td>{{ dist.slug }}</td>
          <td>{{ dist.province.name }}</td>
          <td>
            <button class="btn text-primary" @click="showEdit(),choose(dist)">Edit</button>
            <button class="btn text-danger" @click="showDestroy(),choose(dist)">Delete</button>
          </td>
        </tr>
      </transition-group>
    </table>
    <div class="py-4 text-center" v-show="loading">
      <i class="fas fa-spinner fa-pulse fa-lg text-primary"></i>
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
    <modal name="create-form" :width="500">
      <h3 class="px-3 py-4 text-primary">Create district</h3>
      <form action="" class="p-2" @submit.prevent="store">
        <div class="row form-group">
          <div class="col-md-6">
            <select class="input" v-model="district.province_id" required>
              <option value="" selected>Tỉnh/thành</option>
              <option v-for="prov in provinces" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
            </select>
          </div>
          <div class="col-md-6">
            <input type="text" class="input" v-model="district.name" placeholder="Province name" required>
          </div>
        </div>
        <div class="form-group pt-2 text-right">
          <button type="button" class="btn text-danger" @click="hideCreate">Cancel</button>
          <button class="btn text-primary">Create</button>
        </div>
      </form>
    </modal>
    <!--  -->
    <modal name="edit-form" :width="500">
      <h3 class="px-3 py-4 text-primary">Create district</h3>
      <form action="" class="p-2" @submit.prevent="update">
        <div class="row form-group">
          <div class="col-md-6">
            <select class="input" v-model="chosen.province_id" required>
              <option value="" selected>Tỉnh/thành</option>
              <option v-for="prov in provinces" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
            </select>
          </div>
          <div class="col-md-6">
            <input type="text" class="input" v-model="chosen.name" placeholder="District name" required>
          </div>
        </div>
        <div class="form-group">
          <input type="text" class="input" v-model="chosen.slug" placeholder="District slug name" required>
        </div>
        <div class="form-group pt-2 text-right">
          <button type="button" class="btn text-danger" @click="hideEdit">Cancel</button>
          <button class="btn text-primary">Create</button>
        </div>
      </form>
    </modal>
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
      loading: true,
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
      this.$modal.show('create-form')
      this.district = {
        name: '',
        province_id: ''
      }
    },
    hideCreate () {
      this.$modal.hide('create-form')
    },
    showEdit () {
      this.$modal.show('edit-form')
    },
    hideEdit () {
      this.$modal.hide('edit-form')
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
        this.error(err.response.data.message)
      })
    },
    changePage (page) {
      this.page = page
      this.list(page)
    },
    choose (district) {
      this.chosen = {
        id: district.id,
        name: district.name,
        slug: district.slug,
        province_id: district.province_id
      }
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
    },
    store () {
      $auth.request.post('/api/district', this.district)
      .then(res => {
        if (this.districts.length==this.per_page) {
          let target = Math.floor((this.total / this.per_page)) + 1
          this.page_count = target
          this.changePage(target)
        } else {
          this.districts.push(res.data)
        }
        this.district.name = this.district.province_id = ''
        this.hideCreate()
        this.success('District has been created')
      })
      .catch(err => {
        const errorMessage = err.response.data.message
        errorMessage = errorMessage==undefined ? 'Unable to create new district' : errorMessage
        this.error(errorMessage)
      })
    },
    list (page=1) {
      this.loading = true
      $auth.request.get(`/api/district?page=${page}`)
      .then(res => {
        this.page_count = res.data.last_page
        this.total = res.data.total
        this.per_page = res.data.per_page
        this.districts = res.data.data
        this.loading = false
      })
      .catch(err => {
        this.loading = false
        this.error('Unable to get district list')
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
        const errorMessage = err.response.data.message
        errorMessage = errorMessage==undefined ? 'Unable to delete this district' : errorMessage
        this.error(errorMessage)
      })
    },
    update () {
      $auth.request.put('/api/district/'+this.chosen.id, this.chosen)
      .then(res => {
        this.districts.forEach(prov => {
          if (prov.id == this.chosen.id) {
            prov.name = this.chosen.name
            prov.slug = this.chosen.slug
            prov.province_id = this.chosen.province_id
          }
        })
        this.success('District has been updated')
        this.hideEdit()
      })
      .catch(err => {
        const errorMessage = err.response.data.message
        errorMessage = errorMessage==undefined ? 'Unable to update this district' : errorMessage
        this.error(errorMessage)
      })
    }
  }
}
</script>
<style scoped>
  .input {
    display: block;
    width: 100%;
    border: none;
    border-bottom: 2px solid #ccc;
    padding: 8px 15px;
    outline: unset!important;
  }
  .slide-fade-enter-active {
    transition: all .3s ease;
  }
  .slide-fade-leave-active {
    transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
  }
  .slide-fade-enter, .slide-fade-leave-to {
    transform: translateX(10px);
    opacity: 0;
  }
</style>
