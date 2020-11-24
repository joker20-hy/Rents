<template>
  <div class="container">
    <h3 class="c-flex-middle mt-3">
      Danh sách tỉnh thành
      <button class="btn ml-auto text-primary c-flex-middle" @click="create=true" style="font-weight: 600">
        <add-icon :width="'13px'" :height="'13px'" class="fill-blue" style="transform: translateY(-1px)"/>&nbsp;Thêm mới
      </button>
    </h3>
    <province-create v-show="create" @created="created" @cancel="create=false"/>
    <table class="records-list">
      <thead>
        <th>Tên</th>
        <th>Slug</th>
        <th>Hành động</th>
      </thead>
      <tbody>
        <list-item v-for="province in provinces" :key="province.id" :province="province" @destroy="showDestroy"/>
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
    <confirm-box :name="'delete-province'" :title="'Delete province'" :message="'Province gonna be deleted'" @confirm="destroy()"/>
  </div>
</template>
<script>
import ConfirmBox from '../../utilities/ConfirmBox'
import ProvinceCreate from './Create'
import ListItem from './ListItem'
import AddIcon from '../../../icons/Add'
export default {
  name: 'province-list',
  components: {
    ListItem,
    ConfirmBox,
    ProvinceCreate,
    AddIcon
  },
  data () {
    return {
      loading: false,
      create: false,
      chosen: '',
      page: 1,
      page_count: 1,
      per_page: 1
    }
  },
  mounted () {
    this.list()
  },
  computed:{
    provinces () {
     return this.$store.getters['provinces/provinces']
    }
  },
  methods: {
    showDestroy (province) {
      this.chosen = province
      this.$modal.show('delete-province')
    },
    changePage (page) {
      this.page = page
      this.list()
    },
    list () {
      this.loading = true
      ajax().get(`/api/province?page=${this.page}`)
      .then(res => {
        this.per_page = res.data.per_page
        this.page_count = res.data.last_page
        this.loading = false
        this.$store.commit('provinces/provinces', res.data.data)
      })
      .catch(err => {
        this.loading = false
        this.error('Unable to get province list')
      })
    },
    created (province) {
      if (this.provinces.length<this.per_page) {
        this.$store.commit('provinces/add', province)
      } else {
        let target = Math.floor((this.total / this.per_page)) + 1
        this.changePage(target)
      }
    },
    destroy () {
      ajax().delete(`/api/province/${this.chosen.id}`)
      .then(res => {
        this.$store.commit('provinces/remove', this.chosen.id)
        this.$modal.hide('delete-province')
        this.success('The province has been deleted successfully')
      })
      .catch(err => {
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
