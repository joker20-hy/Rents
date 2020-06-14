<template>
  <div class="container">
    <h1 class="d-flex align-items-end mt-3 py-3">
      Danh sách tỉnh thành
      <button class="btn ml-auto text-light" @click="showCreate" style="font-weight: 600">
        <i class="fas fa-plus"></i> Create
      </button>
    </h1>
    <transition name="slide-fade">
      <form class="row mx-0 my-4 justify-content-center" @submit.prevent="store" v-if="create">
        <div class="col-12 py-2 d-flex bg-white rounded">
          <input type="text" class="text-left input" v-model="province.name" placeholder="Province name">
          <button class="btn text-primary">Create</button>
          <button type="button" class="btn text-danger" @click="hideCreate">Cancel</button>
        </div>
      </form>
    </transition>
    <table class="table table-borderless records-list">
      <thead>
        <th>STT</th>
        <th>Tên</th>
        <th>Slug</th>
        <th>Hành động</th>
      </thead>
      <transition-group name="slide-fade" tag="tbody" class="record-list">
        <tr v-for="prov in provinces" :key="prov.id">
          <td>{{ prov.id }}</td>
          <td>
            <div v-show="!prov.edit" class="holder">{{ prov.name }}</div>
            <input type="text" class="input" v-model="prov.name" v-show="prov.edit">
          </td>
          <td>
            <div v-show="!prov.edit" class="holder">{{ prov.slug }}</div>
            <input type="text" class="input" v-model="prov.slug" v-show="prov.edit">
          </td>
          <td>
            <button class="btn text-primary" @click="choose(prov),prov.edit=true" v-show="!prov.edit">Edit</button>
            <button class="btn text-primary" v-show="prov.edit" @click="update(prov)">Update</button>
            <button class="btn text-danger" v-show="prov.edit" @click="prov.edit=false">Cancel</button>
            <button class="btn text-danger" @click="showDestroy(),choose(prov)" v-show="!prov.edit">Delete</button>
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
    <!-- destroy form -->
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
  name: 'province-list',
  data () {
    return {
      loading: false,
      create: false,
      chosen: '',
      page: 1,
      page_count: 1,
      per_page: 1,
      provinces: [],
      province: {
        name: ''
      }
    }
  },
  mounted () {
    this.list()
  },
  methods: {
    showCreate () {
      this.province.name = ''
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
    changePage (page) {
      this.page = page
      this.list()
    },
    list () {
      this.loading = true
      $auth.request.get(`/api/province?page=${this.page}`)
      .then(res => {
        this.total = res.data.total
        this.per_page = res.data.per_page
        this.page_count = res.data.last_page
        this.loading = false
        res.data.data.forEach(prov => prov.edit = false)
        this.provinces = res.data.data
      })
      .catch(err => {
        this.loading = false
        this.error('Unable to get province list')
      })
    },
    store () {
      $auth.request.post('/api/province', {
        name: this.province.name
      })
      .then(res => {
        this.province.name = ''
        if (this.provinces.length==this.per_page) {
          let target = Math.floor((this.total / this.per_page)) + 1
          this.page_count = target
          this.changePage(target)
        } else {
          res.data.edit = false
          this.provinces.push(res.data)
        }
        this.hideCreate()
        this.success('Province has been created')
      })
      .catch(err => {
        console.log(err.response.data.message)
        this.error('Unable to create new province')
      })
    },
    choose (prov) {
      this.chosen = {
        id: prov.id,
        name: prov.name,
        slug: prov.slug
      }
    },
    update (prov) {
      $auth.request.put(`/api/province/${prov.id}`, {
        name: prov.name,
        slug: prov.slug
      })
      .then(res => {
        prov.edit = false
        this.success('Update successfully')
      })
      .catch(err => {
        prov.name = this.chosen.name
        prov.slug = this.chosen.slug
        console.log(err.response.data.message)
        this.error('Unable to update this.record')
      })
    },
    destroy () {
      $auth.request.delete(`/api/province/${this.chosen.id}`)
      .then(res => {
        this.provinces = this.provinces.filter(prov => prov.id!=this.chosen.id)
        this.hideDestroy()
      })
      .catch(err => {
        console.log(err.response.data.message)
        this.error('Unable to delete this record')
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
