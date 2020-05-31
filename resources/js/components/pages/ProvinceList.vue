<template>
  <div class="container">
    <h1 class="d-flex align-items-end mt-5">
      Danh sách tỉnh thành
      <button class="btn ml-auto" @click="showCreate">Create</button>
    </h1>
    <!-- create form -->
    <table class="table table-borderless text-center">
      <thead>
        <th>STT</th>
        <th>Tên</th>
        <th>Slug</th>
        <th>Hành động</th>
      </thead>
      <transition-group name="slide-fade" tag="tbody" class="record-list">
        <tr v-for="prov in provinces" :key="prov.id">
          <td>{{ prov.id }}</td>
          <td>{{ prov.name }}</td>
          <td>{{ prov.slug }}</td>
          <td>
            <button class="btn text-primary" @click="showEdit(),choose(prov)">Edit</button>
            <button class="btn text-danger" @click="showDestroy(),choose(prov)">Delete</button>
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
      <h3 class="px-3 py-4 text-primary">Create province</h3>
      <form action="" class="p-2" @submit.prevent="store">
        <div class="form-group">
          <input type="text" class="input" v-model="province.name" placeholder="Province name" required>
        </div>
        <div class="form-group pt-2 text-right">
          <button type="button" class="btn text-danger" @click="hideCreate">Cancel</button>
          <button class="btn text-primary">Create</button>
        </div>
      </form>
    </modal>
    <!-- edit form -->
    <modal name="edit-form" :width="500">
      <h3 class="px-3 py-4 text-primary">Edit province</h3>
      <form action="" class="p-2" @submit.prevent="update">
        <div class="form-group">
          <input type="text" class="input" v-model="chosen.name">
        </div>
        <div class="form-group">
          <input type="text" class="input" v-model="chosen.slug">
        </div>
        <div class="form-group pt-2 text-right">
          <button type="button" class="btn text-danger" @click="hideEdit">Cancel</button>
          <button class="btn text-primary">Update</button>
        </div>
      </form>
    </modal>
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
      chosen: '',
      page: 1,
      page_count: 1,
      per_page: 1,
      provinces: [],
      loading: false,
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
    changePage (page) {
      this.page = page
      this.list()
    },
    choose (province) {
      this.chosen = {
        id: province.id,
        name: province.name,
        slug: province.slug
      }
    },
    list () {
      this.loading = true
      $auth.request.get('/api/province?page='+this.page)
      .then(res => {
        this.total = res.data.total
        this.provinces = res.data.data
        this.per_page = res.data.per_page
        this.page_count = res.data.last_page
        this.loading = false
      })
      .catch(err => {
        this.loading = false
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to province list'
        })
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
          this.provinces.push(res.data)
        }
        this.hideCreate()
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'Province has been created',
          timeout: 3000
        })
      })
      .catch(err => {
        const errorMessage = err.response.data.message
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: errorMessage==undefined ? 'Something went wrong' : errorMessage
        })
      })
    },
    update () {
      $auth.request.put('/api/province/'+this.chosen.id, {
        name: this.chosen.name,
        slug: this.chosen.slug
      })
      .then(res => {
        this.provinces.forEach(prov => {
          if (prov.id == this.chosen.id) {
            prov.name = this.chosen.name
            prov.slug = this.chosen.slug
          }
        })
        this.hideEdit()
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'Update successfully',
          timeout: 3000
        })
      })
      .catch(err => {
        const errorMessage = err.response.data.message
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: errorMessage==undefined ? 'Something went wrong' : errorMessage
        })
      })
    },
    destroy () {
      $auth.request.delete('/api/province/'+this.chosen.id)
      .then(res => {
        this.hideDestroy()
        this.provinces = this.provinces.filter(prov => prov.id!=this.chosen.id)
      })
      .catch(err => {
        $eventHub.$emit('error-alert', {
          title: 'Something went wrong',
          message: 'Unable to delete this record'
        })
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
    transform: translateY(10px);
    opacity: 0;
  }
</style>