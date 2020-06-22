<template>
  <div class="container">
    <h1 class="d-flex align-items-end mt-5 py-3">
      Danh sách khu vực
      <button class="btn ml-auto text-primary" @click="create=true" style="font-weight: 600">
        <i class="fas fa-plus"></i> Create
      </button>
    </h1>
    <area-create v-show="create" @created="created" @success="success" @error="error" @cancel="create=false"/>
    <table class="records-list">
      <thead>
        <th>Name</th>
        <th>Slug</th>
        <th>Province</th>
        <th>District</th>
        <th>Actions</th>
      </thead>
      <tbody>
        <list-item v-for="area in areas" :key="area.id" :area="area" @success="success" @error="error" @destroy="showDestroy"/>
      </tbody>
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
    <confirm-box :name="'delete-area'" :title="'Delete area'" :message="'Area gonna be deleted'" @confirm="destroy()"/>
  </div>
</template>
<script>
import { $request } from '../../../auth.js'
import ListItem from './ListItem'
import AreaCreate from './Create'
import ConfirmBox from '../../utilities/ConfirmBox'
export default {
  components: {
    ListItem,
    AreaCreate,
    ConfirmBox
  },
  data () {
    return {
      create: false,
      page: 1,
      page_count: 1,
      per_page: 1,
      total: 1,
      chosen: '',
      loading: false
    }
  },
  mounted () {
    this.list()
  },
  computed: {
    areas () {
      return this.$store.getters['areas/areas']
    }
  },
  methods: {
    list () {
      this.loading = true
      $request.get(`/api/area?page=${this.page}`)
      .then(res => {
        this.loading = false
        this.per_page = res.data.per_page
        this.page_count = res.data.last_page
        this.total = res.data.total
        this.$store.commit('areas/areas', res.data.data)
      })
      .catch(err => {
        this.loading = false
        this.error('Unable to get area list')
      })
    },
    changePage (page) {
      this.page = page
      this.list()
    },
    created (area) {
      if (this.areas.length<this.per_page) {
        this.$store.commit('areas/add', area)
      } else {
        let target = Math.floor((this.total / this.per_page)) + 1
        this.changePage(target)
      }
    },
    showDestroy (area) {
      this.chosen = area
      this.$modal.show('delete-area')
    },
    destroy () {
      $request.delete(`/api/area/${this.chosen.id}`)
      .then(res => {
        this.$modal.hide('delete-area')
        this.$store.commit('areas/remove', this.chosen.id)
        this.success('The area has been deleted successfully')
      })
      .catch(err => {
        this.error('Unable to delete the area')
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