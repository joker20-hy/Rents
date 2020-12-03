<template>
  <div class="container mt-3 mb-5 px-0">
    <div class="col-md-10 mx-auto">
      <h4>Danh sách nhà</h4>
      <div class="p-2 bg-white mb-3 d-flex">
        <router-link :to="{name: 'owner-create-house'}" class="btn ml-auto c-flex-middle">
          <add-icon :height="'12px'" :width="'12px'"></add-icon>&nbsp;Thêm nhà
        </router-link>
      </div>
      <list-item v-for="house in houses" :key="house.id" :house="house" @destroy="confirmDestroy"/>
      <div class="py-3 text-center mx-auto" v-if="loading">
        <i class="fas fa-spinner fa-pulse fa-lg text-primary"></i>
      </div>
      <confirm-box :name="'delete-house'" :title="'Xóa nhà'" :message="'Nhà này sẽ bị xóa'" @confirm="destroy()"/>
    </div>
  </div>
</template>
<script>
import ConfirmBox from '../../utilities/ConfirmBox'
import ListItem from './ListItem'
import AddIcon from '../../../icons/Add'
export default {
  components: {
    ConfirmBox,
    ListItem,
    AddIcon
  },
  data () {
    return {
      chosen: '',
      loading: false
    }
  },
  created () {
    this.list()
  },
  computed: {
    houses () {
      return this.$store.getters['houses/houses']
    }
  },
  methods: {
    list () {
      this.loading = true
      ajax().get(`/api/house/list`)
      .then(res => {
        this.loading = false
        res.data.data.forEach(house => {
          house.images=house.images==null?[]:JSON.parse(house.images)
          house.description = house.description==null?'':utf8.decode(house.description)
          house.contact=house.contact==null?{phone:'',others:''}:JSON.parse(house.contact)
        });
        this.$store.commit('houses/houses', res.data.data)
      })
      .catch(err => {
        this.loading = false
        console.log(err)
      })
    },
    confirmDestroy (id) {
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