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
      <div class="d-flex align-items-center pt-2">
        <switch-box
          v-model="room.status"
          :class="room.status?'bg-success':'bg-danger'"
          @change="$emit('changestatus')"
        ></switch-box>&nbsp;&nbsp;
        <span v-if="room.status==statuses.waiting" class="text-danger">
          <i class="fas fa-caret-right"></i> Chưa được thuê
        </span>
        <span v-else-if="room.status!=statuses.waiting" class="text-success">
          <i class="fas fa-check"></i> Đã được thuê
        </span>
      </div>
      <div class="py-2 text-right text-md-center row mx-0">
        <div class="col-md-3 pt-2" v-if="room.status!=statuses.waiting">
          <router-link :to="{name: 'owner-list-payment', params: {id: room.id}}">
            <i class="fas fa-money-check-alt"></i> Danh sách hóa đơn
          </router-link>
        </div>
        <div class="col-md-3 pt-2" v-if="room.status!=statuses.waiting">
          <router-link :to="{name: 'owner-create-payment', params: {id: room.id}}">
            <i class="fas fa-plus"></i> Tạo hóa đơn
          </router-link>
        </div>
        <div class="col-md-3 pt-2">
          <router-link :to="{name: 'owner-add-renter', params: {room: room.id}}" class="c-flex-middle">
            <i class="fas fa-user-plus"></i> Thêm người thuê
          </router-link>
        </div>
        <div class="col-md-3 pt-2">
          <router-link :to="{name: 'owner-list-renter', params: {room: room.id}}" class="c-flex-middle">
            <i class="fas fa-user"></i> Người thuê
          </router-link>
        </div>
        <div class="col-md-3 pt-2">
          <button class="btn text-primary c-flex-middle" @click="$emit('add-pay-method', item)">
            <coin-icon :width="'15px'" :height="'15px'" class="fill-blue"/>&nbsp;Thanh toán
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>
<script>
import SwitchBox from '../../utilities/SwitchBox'
import AddIcon from '../../../icons/Add'
import CoinIcon from '../../../icons/Coin'
export default {
  components: {
    SwitchBox,
    AddIcon,
    CoinIcon
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
