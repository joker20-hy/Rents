<template>
  <transition name="slide-fade">
    <div class="list-item row mx-0">
      <div class="col-md-4 px-0 py-2 d-flex align-items-center" style="font-weight: 600">
        {{ `Hóa đơn tháng ${payment.month}/${payment.year}` }}
      </div>
      <div class="col-6 col-md-4 px-1 d-flex justify-content-center align-items-center">
        <switch-box
          v-model="payment.status"
          :class="payment.status?'bg-primary':''"
          :locked="payment.status!=0"
          @input="changeStatus"
        ></switch-box>&nbsp;
        <span v-if="payment.status">Đã thanh toán</span>
        <span v-else>Chưa thanh toán</span>
      </div>
      <div class="col-6 col-md-4 px-0 text-right">
        <router-link :to="{name: 'owner-detail-payment', params: {id: payment.id}}" class="btn text-primary">
          <i class="fas fa-info-circle"></i> Chi tiết
        </router-link>
        <button class="btn text-danger" @click="$emit('delete', payment.id)">
          <i class="fas fa-trash"></i> Xóa
        </button>
      </div>
      <div v-if="payment.status" class="text-danger">
        <span class="text-bold">Lưu ý: </span> Bạn không thể thay đổi trạng thái sau khi xác nhận 'Đã thanh toán'
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
  name: 'list-item',
  props: {
    payment: {
      required: true,
      type: Object
    }
  },
  data () {
    return {}
  },
  methods: {
    alertPayment () {
      $eventHub.$emit('warning-alert', {
        title: 'Thông báo',
        message: "Bạn không thể thay đổi trạng thái sau khi xác nhận 'Đã thanh toán'",
        timeout: 3000
      })
    },
    changeStatus () {
      if (!this.payment.status) {
        return this.alertPayment()
      }
      $eventHub.$emit('on-loading')
      ajax().put(`/api/payment/room/${this.payment.id}/status`, {status: this.payment.status})
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
        this.payment.status=this.payment.status?0:1
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Không thể lưu trạng thái',
          timeout: 3000
        })
      })
    }
  }
}
</script>