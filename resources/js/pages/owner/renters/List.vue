<template>
  <div class="contain-sm mt-3">
    <h1 style="font-size: large">Danh sách người thuê</h1>
    <div v-if="renters.length==0" class="text-center text-muted">Hiện phòng chưa có người thuê</div>
    <list-item v-else v-for="renter in renters" :key="renter.id" :item="renter" @remove="confirmRemove"/>
    <confirm-box
      :name="'remove-renter-confirm'"
      :title="'Xác nhận thay đổi'"
      :message="`Việc này sẽ xóa bỏ thông tin thuê phòng của người này`"
      @confirm="removeRenter()"
      ></confirm-box>
  </div>
</template>
<script>
import ListItem from './ListItem'
import ConfirmBox from '../../utilities/ConfirmBox'
export default {
  components: {
    ConfirmBox,
    ListItem
  },
  data () {
    return {
      id: this.$route.params.room,
      chosen: {}
    }
  },
  computed: {
    renters () {
      return this.$store.getters['users/users']
    }
  },
  mounted () {
    this.list()
  },
  methods: {
    list () {
      ajax().get(`/api/room/${this.id}/renters`)
      .then(res => {
        this.$store.commit('users/users', res.data)
      })
      .catch(err => {
        console.log(err)
      })
    },
    confirmRemove(renter) {
      this.chosen = renter
      this.$modal.show('remove-renter-confirm')
    },
    removeRenter() {
      $eventHub.$emit('on-loading')
      ajax().put(`/api/renter/leave-room/${this.chosen.id}`, {})
      .then(res => {
        $eventHub.$emit('off-loading')
        this.$modal.hide('remove-renter-confirm')
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Đã xóa thông tin thuê phòng',
          timeout: 3000
        })
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        this.$modal.hide('remove-renter-confirm')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Không thể xóa thông tin thuê phòngS',
          timeout: 3000
        })
      })
    }
  }
}
</script>