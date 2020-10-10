<template>
  <div class="container">
    <h1 class="d-flex align-items-end mt-5 py-3">
      Danh sách quận/huyện
      <button class="btn ml-auto text-primary" @click="create=true" style="font-weight: 600">
        <i class="fas fa-plus"></i> Create
      </button>
    </h1>
    <district-create v-show="create" @created="created" @cancel="create=false" @success="success" @error="error"/>
    <table class="records-list">
      <thead>
        <th>Name</th>
        <th>Slug</th>
        <th>Province</th>
        <th>Actions</th>
      </thead>
      <tbody>
        <list-item v-for="district in districts" :key="district.id" :district="district" @destroy="showDestroy" @success="success" @error="error"/>
      </tbody>
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
    <confirm-box :name="'delete-district'" :title="'Delete district'" :message="'District gonna be deleted'" @confirm="destroy()"/>
  </div>
</template>
<script>
import DistrictCreate from './Create'
import ListItem from './ListItem'
import ConfirmBox from '../../utilities/ConfirmBox'
export default {
  name: 'district-list',
  components: {
    ListItem,
    DistrictCreate,
    ConfirmBox
  },
  data () {
    return {
      chosen: '',
      page: 1,
      page_count: 1,
      per_page: 1,
      total: 1,
      create: false,
      loading: false
    }
  },
  computed: {
    districts () {
      return this.$store.getters['districts/districts']
    }
  },
  mounted () {
    this.list()
  },
  methods: {
    showDestroy (district) {
      this.chosen = district
      this.$modal.show('delete-district')
    },
    changePage (page) {
      this.page = page
      this.list()
    },
    list () {
      this.loading = true
      ajax().get(`/api/district/list?page=${this.page}`)
      .then(res => {
        this.page_count = res.data.last_page
        this.per_page = res.data.per_page
        this.total = res.data.total
        this.$store.commit('districts/districts', res.data.data)
        this.loading = false
      })
      .catch(err => {
        this.loading = false
        this.error('Unable to get district list')
      })
    },
    created (district) {
      if (this.districts.length<this.per_page) {
        this.$store.commit('districts/add', district)
      } else {
        let target = Math.floor((this.total / this.per_page)) + 1
        this.changePage(target)
      }
    },
    destroy () {
      ajax().delete('/api/district/'+this.chosen.id)
      .then(res => {
        this.$store.commit('districts/remove', this.chosen.id)
        this.$modal.hide('delete-district')
        this.success('District has been deleted')
      })
      .catch(err => {
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
