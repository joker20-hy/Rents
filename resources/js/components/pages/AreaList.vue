<template>
  <div class="container">
    <h1 class="d-flex align-items-end mt-5">
      Danh sách khu vực
      <button class="btn ml-auto" @click="showCreate">Create</button>
    </h1>
    <table class="table table-borderless text-center">
      <thead>
        <th>STT</th>
        <th>Tên</th>
        <th>Slug</th>
        <th>Tỉnh/Thành</th>
        <th>Quận/Huyện</th>
        <th>Hành động</th>
      </thead>
      <transition-group name="slide-fade" tag="tbody">
        <tr v-for="area in areas" :key="area.id">
          <td>{{ area.id }}</td>
          <td>{{ area.name }}</td>
          <td>{{ area.slug }}</td>
          <td>{{ area.province.name }}</td>
          <td>{{ area.district.name }}</td>
          <td>
            <button class="btn text-primary" @click="showEdit(),choose(area)">Edit</button>
            <button class="btn text-danger" @click="showDestroy(),choose(area)">Delete</button>
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
    <!-- create form -->
    <modal name="create-form" :width="500">
      <h3 class="px-3 py-4 text-primary">Create area</h3>
      <form action="" class="p-2" @submit.prevent="store">
        <div class="row form-group">
          <div class="col-md-6">
            <select class="input" v-model="area.province_id" @change="getDistricts(area.province_id)" required>
              <option value="" selected>Tỉnh/thành</option>
              <option v-for="prov in provinces" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
            </select>
          </div>
          <div class="col-md-6">
            <select class="input" v-model="area.district_id">
              <option value="" selected>Quận/Huyện</option>
              <option v-for="dist in districts" :key="dist.id" :value="dist.id">
                {{ dist.name }}
              </option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <input type="text" class="input" v-model="area.name" placeholder="Area name" required>
        </div>
        <div class="form-group pt-2 text-right">
          <button type="button" class="btn text-danger" @click="hideCreate">Cancel</button>
          <button class="btn text-primary">Create</button>
        </div>
      </form>
    </modal>
    <!-- edit form -->
    <modal name="edit-form" :width="500">
      <h3 class="px-3 py-4 text-primary">Edit area</h3>
      <form action="" class="p-2" @submit.prevent="update">
        <div class="row form-group">
          <div class="col-md-6">
            <select class="input" v-model="chosen.province_id" @change="getDistricts(chosen.province_id)" required>
              <option value="" selected>Tỉnh/thành</option>
              <option v-for="prov in provinces" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
            </select>
          </div>
          <div class="col-md-6">
            <select class="input" v-model="chosen.district_id">
              <option value="" selected>Quận/Huyện</option>
              <option v-for="dist in districts" :key="dist.id" :value="dist.id">
                {{ dist.name }}
              </option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <input type="text" class="input" v-model="chosen.name" placeholder="Area name" required>
        </div>
        <div class="form-group">
          <input type="text" class="input" v-model="chosen.slug" placeholder="Area slug name" required>
        </div>
        <div class="form-group pt-2 text-right">
          <button type="button" class="btn text-danger" @click="hideEdit">Cancel</button>
          <button class="btn text-primary">Update</button>
        </div>
      </form>
    </modal>
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
      this.area.province_id = ''
      this.area.district_id = ''
      this.area.name = ''
      this.$modal.show('create-form')
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
    getDistricts (province_id=null) {
      $auth.request.get(`/api/district/${province_id==null ? this.area.province_id : province_id}`)
      .then(res => {
        this.districts = res.data.data
      })
      .catch(err => {
        this.error(err.response.data.message)
      })
    },
    changePage (page) {
      this.page = page
    },
    list (page=1) {
      this.loading = true
      $auth.request.get(`/api/area?page=${page}`)
      .then(res => {
        this.page_count = res.data.last_page
        this.total = res.data.total
        this.per_page = res.data.per_page
        this.areas = res.data.data
        this.loading = false
      })
      .catch(err => {
        this.loading = false
        this.error(err.response.data.message)
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
          this.areas.push(res.data)
        }
      })
      .catch(err => {
        this.error(err.response.data.message)
      })
    },
    choose (area) {
      this.chosen = {
        id: area.id,
        name: area.name,
        slug: area.slug,
        province_id: area.province_id,
        district_id: area.district_id
      }
      if (this.edit) this.getDistricts(area.province_id)
    },
    update () {
      $auth.request.put(`/api/area/${this.chosen.id}`, {
        name: this.chosen.name,
        slug: this.chosen.slug,
        province_id: this.chosen.province_id,
        district_id: this.chosen.district_id
      })
      .then(res => {
        this.areas.forEach(area => {
          if (area.id == this.chosen.id) {
            area.name = this.chosen.name
            area.slug = this.chosen.slug
            area.province_id = this.chosen.province_id
            area.district_id = this.chosen.district_id
          }
        })
        this.edit = false
        this.success('Update successfully')
      })
      .catch(err => {
        this.error(err.response.data.message)
      })
    },
    destroy () {
      $auth.request.delete(`/api/area/${this.chosen.id}`)
      .then(res => {
        this.areas = this.areas.filter(area => area.id!=this.chosen.id)
        this.success('Delete successfully')
        this.remove = false
      })
      .catch(err => {
        this.error(err.response.data.message)
        this.remove = false
      })
    },
    /**
     * Success notification
     * @param {String} message
     */
    success (message) {
      $eventHub.$emit('success-alert', {
        title: 'Thành công',
        message: message,
        timeout: 3600
      })
    },
    /**
     * Error notification
     * @param {String} message
     */
    error (message) {
      $eventHub.$emit('error-alert', {
        title: 'Không thành công',
        message: message
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
