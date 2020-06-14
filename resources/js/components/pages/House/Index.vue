<template>
  <div class="container">
    <h3 class="d-flex align-items-end mt-5 py-3">
      House list
      <button class="btn ml-auto text-light" style="font-weight: 600" @click="showCreate()">
        <i class="fas fa-plus"></i> Create
      </button>
    </h3>
    <table class="table table-borderless records-list">
      <thead>
        <th>Address</th>
        <th>Avg rate</th>
        <th>Rate count</th>
        <th>Rent</th>
        <th>Actions</th>
      </thead>
      <transition-group name="slide-fade" tag="tbody">
        <tr v-for="house in houses" :key="house.id">
          <td>{{ house.address }}</td>
          <td>{{ house.avg_rate }}</td>
          <td>{{ house.rate_count }}</td>
          <td>{{ house.rent?'Yes':'No' }}</td>
          <td>
            <div>
              <button class="btn" @click="showDetail(house)">detail</button>
              <button class="btn">delete</button>
            </div>
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
    <house-create/>
    <house-detail/>
  </div>
</template>
<script>
import { $auth } from '../../../auth'
import serialize from '../../../utilities/serialize'
import HouseCreate from './HouseCreate'
import HouseDetail from './HouseDetail'

export default {
  components: {
    HouseCreate,
    HouseDetail
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
      }
    }
  },
  mounted () {
    this.list()
  },
  methods: {
    showDetail (house) {
      $eventHub.$emit('show-detail-house', house)
    },
    showCreate () {
      $eventHub.$emit('show-create-house')
    },
    changePage (page) {
      this.query.page = page
      this.list()
    },
    listApi () {
      return `/api/house?${serialize.fromObj(this.query)}`
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
    }
  }
}
</script>