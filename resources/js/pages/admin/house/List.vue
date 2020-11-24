<template>
  <div class="container">
    <h3 class="d-flex align-items-end mt-5 py-3 text-primary">
      Danh sách nhà
      <router-link class="btn ml-auto text-primary c-flex-middle" :to="{name: 'house-create'}" style="font-weight: 600">
        <add-icon :width="'13px'" :height="'13px'" class="fill-blue" style="transform: translateY(-1px)"/>&nbsp;Thêm mới
      </router-link>
    </h3>
    <table class="records-list">
      <thead>
        <th>Địa chỉ</th>
        <th>Điểm trung bình</th>
        <th>Số đánh giá</th>
        <!-- <th>Rent</th> -->
        <th>Hành động</th>
      </thead>
      <tbody>
        <list-item v-for="house in houses" :key="house.id" :house="house" @destroy="destroyConfirm"/>
      </tbody>
    </table>
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
import serialize from '../../../utilities/serialize'
import ListItem from './ListItem'
import HouseDetail from './Detail'
import ConfirmBox from '../../utilities/ConfirmBox'
import AddIcon from '../../../icons/Add'
export default {
  components: {
    ListItem,
    HouseDetail,
    ConfirmBox,
    AddIcon
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
      $eventHub.$emit('on-loading')
      ajax().get(this.listApi())
      .then(res => {
        $eventHub.$emit('off-loading')
        res.data.data.forEach(dat => {
          dat.images = dat.images==null?[]:JSON.parse(dat.images)
          dat.description = dat.description==null?'':utf8.decode(dat.description)
        })
        this.$store.commit('houses/houses', res.data.data)
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
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
      ajax().delete(`/api/house/${this.chosen}`)
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