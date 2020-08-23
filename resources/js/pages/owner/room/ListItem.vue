<template>
  <transition name="slide-fade">
    <div class="bg-white p-2 rounded mb-3" item>
      <div class="pb-2" style="font-weight: 600">
        <router-link :to="{name: 'owner-detail-room', params: {id: room.id}}">
          {{ room.name }}
        </router-link>
      </div>
      <div class="pb-2" style="font-size: small">Địa chỉ: {{ room.address }}</div>
      <div class="d-flex" style="font-size: small">
        <div class="pr-2" style="border-right: 2px solid var(--blue)">
            Đánh giá {{ room.avg_rate }}
        </div>
        <div class="pl-2">
            Số đánh giá {{ room.rate_count }}
        </div>
      </div>
      <div class="pt-2">
        Số lượng người thuê trọ: {{ room.renters_count }}
      </div>
      <div class="d-flex align-items-center pt-2">
        <switch-box v-model="room.status" :class="room.status?'bg-success':'bg-danger'" @change="changeStatus()"></switch-box>&nbsp;&nbsp;
        <span v-if="status_waiting" class="text-danger">
          <i class="fas fa-caret-right"></i> Nhà chưa được thuê
        </span>
        <span v-else-if="status_rented" class="text-success">
          <i class="fas fa-check"></i> Nhà đã được thuê
        </span>
      </div>
      <div class="py-2 text-right text-md-center row mx-0">
        <div class="col-md-4">
          <router-link v-if="status_rented" :to="{name: 'owner-list-payment', params: {id: room.id}}"><i class="fas fa-list-ul"></i> Danh sách hóa đơn</router-link>
        </div>
        <div class="col-md-4">
          <router-link v-if="status_rented" :to="{name: 'owner-create-payment', params: {id: room.id}}"><i class="fas fa-money-check"></i> Tạo hóa đơn</router-link>
        </div>
        <div class="col-md-4">
          <router-link :to="{name: 'owner-join-room-qr', params: {id: room.id}}">Thêm người thuê</router-link>
        </div>
      </div>
    </div>
  </transition>
</template>
<script>
import SwitchBox from '../../utilities/SwitchBox'
export default {
  components: {
    SwitchBox
  },
  props: {
    item: {
      required: true,
      type: Object
    }
  },
  computed: {
    status_waiting () {
      return this.room.status==$config.ROOM_STATUS.waiting
    },
    status_rented () {
      return this.room.status==$config.ROOM_STATUS.rented
    }
  },
  data () {
    return {
      room: this.item
    }
  },
  methods: {
    changeStatus () {
      $request.put(`/api/room/${this.room.id}/status`, {
        status: this.room.status
      })
      .then(res => {
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Đã lưu trạng thái thành công',
          timeout: 3000
        })
      })
      .catch(err => {
        this.room.status=this.room.status==1?0:1
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Không thể lưu trạng thái mới',
          timeout: 3000
        })
      })
    }
  }
}
</script>
<style scoped>
[item] {
  box-shadow: 0px 1px 2px #ddd;
}
</style>
