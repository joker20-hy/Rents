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
        <div class="pr-2" style="border-right: 2px solid var(--blue)">Đánh giá {{ room.avg_rate }}</div>
        <div class="pl-2">Số đánh giá {{ room.rate_count }}</div>
      </div>
      <div class="pt-2">
        Số lượng người thuê trọ: {{ room.renter_count }}
      </div>
      <div v-if="room.status!=statuses.waiting" class="d-flex align-items-center pt-2">
        <span class="text-success">
          Đã được thuê
        </span>
        &nbsp;&nbsp;
        <switch-box
          v-model="room.status"
          :class="room.status?'bg-success':'bg-danger'"
          @input="$emit('change-status')"
        ></switch-box>
      </div>
      <div v-else class="pt-2">
        <span class="text-bold">Trạng thái phòng: </span> <span class="text-danger">Chưa được thuê</span>
      </div>
      <div class="py-2 text-right text-md-center row mx-0">
        <div class="col-md-3 text-center pt-2" v-if="room.status!=statuses.waiting">
          <router-link class="btn text-primary" :to="{name: 'owner-list-payment', params: {id: room.id}}">
            <i class="fas fa-money-check-alt"></i> Danh sách hóa đơn
          </router-link>
        </div>
        <div class="col-md-3 text-center pt-2" v-if="room.status!=statuses.waiting">
          <router-link class="btn text-primary" :to="{name: 'owner-create-payment', params: {id: room.id}}">
            <i class="fas fa-plus"></i> Tạo hóa đơn
          </router-link>
        </div>
        <div class="col-md-3 text-center pt-2">
          <router-link class="btn text-primary" :to="{name: 'owner-add-renter', params: {room: room.id}}">
            <i class="fas fa-user-plus"></i> Thêm người thuê
          </router-link>
        </div>
        <div class="col-md-3 text-center pt-2">
          <router-link class="btn text-primary" :to="{name: 'owner-list-renter', params: {room: room.id}}">
            <i class="fas fa-user"></i> Người thuê
          </router-link>
        </div>
        <div class="col-md-3 text-center pt-2">
          <button class="btn text-primary" @click="$emit('add-pay-method', item)">
            <i class="fas fa-dollar-sign"></i> Thanh toán
          </button>
        </div>
        <div class="col-md-3 text-center pt-2">
          <button class="btn text-danger" @click="$emit('destroy', room)">
            Xóa
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>
<script>
import SwitchBox from '../../utilities/SwitchBox'
import AddIcon from '../../../icons/Add'
export default {
  components: {
    SwitchBox,
    AddIcon
  },
  props: {
    item: {
      required: true,
      type: Object
    }
  },
  data () {
    return {
      room: this.item,
      status: this.item.status,
      statuses: $config.ROOM.STATUS
    }
  },
  methods: {}
}
</script>
<style scoped>
[item] {
  box-shadow: 0px 1px 2px #ddd;
}
</style>
