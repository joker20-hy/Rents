<template>
  <div class="container mt-3 mb-5 px-0">
    <div class="col-md-10 mx-auto">
      <h4>Danh sách phòng trọ</h4>
      <div class="d-flex py-2">
        <router-link class="ml-auto" style="font-weight: 600" :to="{name: 'owner-create-room', params: {id: this.query.house}}">
          <i class="fa fa-plus"></i> Thêm phòng
        </router-link>
      </div>
      <small class="text-muted" v-if="rooms.length==0">Hiện chưa có phòng nào</small>
      <list-item v-for="room in rooms" :key="room.id" :item="room" @add-pay-method="payMethods" @changestatus="confirmChangeStatus(room)"></list-item>
    </div>
    <confirm-box
      :name="'change-status-confirm'"
      :title="'Xác nhận thay đổi'"
      :message="`Việc thay đổi này sẽ xóa bỏ hoàn toàn thông tin về những người thuê phòng này và phòng sẽ trở lại trạng thái 'còn trống'`"
      @confirm="changeStatus()"
      @cancel="cancelChangeStatus()"
      ></confirm-box>
      <pay-methods :name="'pay-methods'" :chosen="chosen.paymethods" @change="changePayMethods"></pay-methods>
  </div>
</template>
<script>
import { ajax } from '../../../utilities/request/request'
import ConfirmBox from '../../utilities/ConfirmBox'
import ListItem from './ListItem'
import PayMethods from './PayMethods'

export default {
  components: {
    ListItem,
    ConfirmBox,
    PayMethods
  },
  data () {
    return {
      query: {
        page: 1,
        house: this.$route.params.id
      },
      chosen: {}
    }
  },
  watch: {
    "$route.params.id": {
      handler (id) {
        this.query.house = id
        this.list()
      },
      deep: true,
      immediate: true
    }
  },
  computed: {
    rooms () {
      return this.$store.getters['rooms/rooms']
    }
  },
  methods: {
    payMethods (room) {
      this.chosen = room
      this.$modal.show('pay-methods')
    },
    list () {
      $eventHub.$emit('on-loading')
      ajax().get(`/api/room/list?${serialize.fromObj(this.query)}`)
      .then(res => {
        res.data.data.forEach(room => {
          room.images = JSON.parse(room.images)
          room.description = utf8.decode(room.description)  
        })
        this.$store.commit('rooms/rooms', res.data.data)
        $eventHub.$emit('off-loading')
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        console.log(err)
      })
    },
    confirmChangeStatus (room) {
      this.chosen = room
      if (this.chosen.status) {
        this.changeStatus()
      } else {
        this.$modal.show('change-status-confirm')
      }
    },
    cancelChangeStatus () {
      this.chosen.status=this.chosen.status==1?0:1
    },
    changeStatus () {
      $eventHub.$emit('on-loading')
      ajax().put(`/api/room/${this.chosen.id}/status`, {
        status: this.chosen.status
      })
      .then(res => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Đã lưu trạng thái thành công',
          timeout: 3000
        })
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        this.chosen.status=this.chosen.status==1?0:1
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Không thể lưu trạng thái mới',
          timeout: 3000
        })
      })
    },
    changePayMethods(payMethods) {
      $eventHub.$emit('on-loading')
      ajax().post(`/api/room/${this.chosen.id}/pay-methods`, {
        pay_methods: payMethods.map(item => item.id)
      })
      .then(res => {
        $eventHub.$emit('off-loading')
        this.$modal.hide('pay-methods')
        this.chosen.paymethods = payMethods
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Đã lưu thông tin thành công',
          timeout: 3000
        })
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Hiện chưa thể lưu thông tin, hãy thử lại sau',
          timeout: 3000
        })
      })
    }
  }
}
</script>