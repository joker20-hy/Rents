<template>
  <div class="container px-0">
    <div class="col-md-10 mx-auto">
      <h4>Danh sách nhà</h4>
      <div class="p-2 bg-white mb-3 d-flex">
        <router-link :to="{name: 'owner-create-house'}" class="btn ml-auto">
          <i class="fas fa-plus"></i> Thêm nhà
        </router-link>
      </div>
      <list-item v-for="house in houses" :key="house.id" :house="house" @destroy="confirmDestroy"/>
      <confirm-box :name="'delete-house'" :title="'Xóa nhà'" :message="'Bản ghi nhà này sẽ bị xóa'" @confirm="destroy()"/>
    </div>
  </div>
</template>
<script>
import ConfirmBox from '../../utilities/ConfirmBox'
import ListItem from './ListItem'
export default {
  components: {
    ConfirmBox,
    ListItem
  },
  data () {
    return {
      chosen: ''
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
      $auth.request.get(`/api/house/list`)
      .then(res => {
        res.data.data.forEach(house => {
          house.images=house.images==null?[]:JSON.parse(house.images)
          house.description = house.description==null?'':utf8.decode(house.description)
        });
        this.$store.commit('houses/houses', res.data.data)
      })
      .catch(err => {
        console.log(err)
      })
    },
    confirmDestroy (id) {
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