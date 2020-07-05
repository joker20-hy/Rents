<template>
  <div class="container">
    <h3 class="d-flex align-items-end mt-5 py-3 text-primary">
      House list
      <router-link class="btn ml-auto text-primary" :to="{name: 'house-create'}" style="font-weight: 600">
        <i class="fas fa-plus"></i> Create
      </router-link>
    </h3>
    <table class="records-list">
      <thead>
        <th>Address</th>
        <th>Avg rate</th>
        <th>Rate count</th>
        <th>Rent</th>
        <th>Actions</th>
      </thead>
      <tbody>
        <list-item v-for="house in houses" :key="house.id" :house="house" @destroy="destroyConfirm"/>
      </tbody>
    </table>
    <div class="py-4 text-center mx-auto" v-if="loading">
      <span class="p-2 bg-light rounded-circle">
        <i class="fas fa-spinner fa-pulse fa-lg text-primary"></i>
      </span>
    </div>
    <paginate
      v-model="query.page"
      :page-count="page_count"
      :page-range="3"
      :margin-pages="2"
      :click-handler="changePage"
      :prev-text="'Prev'"
      :next-text="'Next'"
      :container-class="'pagination'"
      :page-class="'page-item'">
    </paginate>
    <house-detail/>
    <confirm-box :name="'delete-house'" :title="'Delete house'" :message="'This house gonna be deleted'" @confirm="destroy()"/>
  </div>
</template>
<script>
import { $auth } from '../../../utilities/request/request'
import serialize from '../../../utilities/serialize'
import ListItem from './ListItem'
import HouseDetail from './Detail'
import ConfirmBox from '../../utilities/ConfirmBox'

export default {
  components: {
    ListItem,
    HouseDetail,
    ConfirmBox
  },
  computed: {
    houses () {
      return this.$store.getters['houses/houses']
    },
    TRUE () {
      return $config.TRUE
    },
    FALSE () {
      return $config.FALSE
    }
  },
  data () {
    return {
      loading: false,
      page_count: 1,
      query: {
        page: 1,
        province: '',
        district: '',
        area: ''
      },
      chosen: ''
    }
  },
  mounted () {
    this.list()
  },
  methods: {
    changePage (page) {
      this.query.page = page
      this.list()
    },
    listApi () {
      return `/api/house/list?${serialize.fromObj(this.query)}`
    },
    list () {
      this.loading = true
      $auth.request.get(this.listApi())
      .then(res => {
        this.loading = false
        res.data.data.forEach(dat => {
          dat.images = dat.images==null?[]:JSON.parse(dat.images)
          dat.rent = dat.rent==this.TRUE
          dat.description = dat.description==null?'':utf8.decode(dat.description)
        })
        this.$store.commit('houses/houses', res.data.data)
      })
      .catch(err => {
        this.loading = false
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to get house list'
        })
      })
    },
    destroyConfirm (id) {
      this.chosen = id
      this.$modal.show('delete-house')
    },
    destroy () {
      $auth.request.delete(`/api/house/${this.chosen}`)
      .then(res => {
        this.$modal.hide('delete-house')
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'The house has been deleted',
          timeout: 3000
        })
        this.list()
      })
      .catch(err => {
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to delete this house, please try again',
          timeout: 3000
        })
      })
    }
  }
}
</script>