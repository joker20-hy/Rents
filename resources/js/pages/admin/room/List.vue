<template>
  <div class="container">
    <h3 class="d-flex align-items-end mt-5 py-3 text-primary">
      Room list
      <router-link class="btn ml-auto text-primary" style="font-weight: 600" :to="{name:'room-create'}">
        <i class="fas fa-plus"></i> Create
      </router-link>
    </h3>
    <table class="records-list">
      <thead>
        <th>Name</th>
        <th>Address</th>
        <th>Avg rate</th>
        <th>Rate count</th>
        <th>Actions</th>
      </thead>
      <tbody>
        <list-item v-for="room in rooms" :key="room.id" :room="room" @destroy="destroyConfirm"></list-item>
      </tbody>
    </table>
    <room-detail/>
    <confirm-box :name="'delete-room'" :title="'Delete room'" :message="'Room gonna be deleted'" @confirm="destroy()"/>
  </div>
</template>
<script>
import serialize from '../../../utilities/serialize'
import RoomDetail from './Detail'
import ListItem from './ListItem'
import ConfirmBox from '../../utilities/ConfirmBox'

export default {
  name: 'room-list',
  components: {
    RoomDetail,
    ListItem,
    ConfirmBox
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
    rooms () {
      return this.$store.getters['rooms/rooms']
    }
  },
  methods: {
    showCreate () {
      $eventHub.$emit('show-create-room')
    },
    list () {
      $auth.request.get('/api/room/list')
      .then(res => {
        res.data.data.forEach(dat => {
          dat.images = dat.images==null||dat.images==''?[]:JSON.parse(dat.images)
          dat.description = dat.description==null?'':utf8.decode(dat.description)
        })
        this.$store.commit('rooms/rooms', res.data.data)
      })
      .catch(err => {
        console.log(err.response.data)
      })
    },
    destroyConfirm (id) {
      this.chosen = id
      this.$modal.show('delete-room')
    },
    destroy () {
      $auth.request.delete(`/api/room/${this.chosen}`)
      .then(res => {
        this.$modal.hide('delete-room')
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'The room has been deleted',
          timeout: 3000
        })
        this.list()
      })
      .catch(err => {
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to delete room, please try again',
          timeout: 3000
        })
      })
    }
  }
}
</script>