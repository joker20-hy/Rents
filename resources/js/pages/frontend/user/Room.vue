<template>
  <div contain-box v-if="room">
    <div class="bg-white p-2 mb-2">
      <h1 style="font-size: large">{{ room.name }}</h1>
      <p>Địa chỉ: {{ room.house.address_detail }}</p>
      <p>Giá thuê: {{ range(room.price) }} vnđ</p>
    </div>

    <router-link :to="{name: 'payment-room-list'}" class="bg-white p-2 mb-2 d-flex align-items-center">
      <i class="fas fa-money-check-alt"></i>&nbsp;Hóa đơn phòng ({{ room.payments.length }})
      <button class="btn ml-auto text-primary">
        <i class="fas fa-chevron-right"></i>
      </button>
    </router-link>

    <router-link :to="{name: 'room-pay-method', params: {id: room.id}}" class="bg-white p-2 mb-2 d-flex align-items-center">
      <i class="fas fa-dollar-sign"></i>&nbsp;Phương thức thanh toán
      <button class="btn ml-auto text-primary">
        <i class="fas fa-chevron-right"></i>
      </button>
    </router-link>

    <router-link :to="{name: 'review-room', params: {id: room.id}}" class="bg-white p-2 mb-2 d-flex align-items-center">
      <i class="fas fa-pen"></i>&nbsp;Viết đánh giá
      <button class="btn ml-auto text-primary">
        <i class="fas fa-chevron-right"></i>
      </button>
    </router-link>

    <button class="btn text-danger bg-white w-100" @click="$modal.show('leave-room')">
      Rời khỏi phòng
    </button>
    <confirm-box :name="'leave-room'" :title="'Rời phòng'" :message="'Bạn có chắc muốn rời khỏi phòng này không?'" @confirm="leaveRoom()"/>
  </div>
</template>
<script>
import ConfirmBox from '../../utilities/ConfirmBox'

export default {
  components: {
    ConfirmBox
  },
  data () {
    return {}
  },
  computed: {
    room () {
      return this.$store.getters['rooms/first']
    }
  },
  created () {
    this.getRoom()
  },
  methods: {
    range(number) {
      return $number.range(`${number}`)
    },
    getRoom () {
      $eventHub.$emit('on-loading')
      ajax().get('/api/user/room')
      .then(res => {
        $eventHub.$emit('off-loading')
        res.data.payments.forEach(payment => {
          payment.bill = JSON.parse(payment.bill)
          let time = new Date(payment.time)
          payment.month = time.getMonth() + 1
          payment.year = time.getFullYear()
        });
        this.$store.commit('payments/payments', res.data.payments)
        this.$store.commit('rooms/rooms', [res.data])        
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Không thể lấy dữ liệu về phòng đã thuê',
          timeout: 3000
        })
      })
    },
    leaveRoom () {
      $eventHub.$emit('on-loading')
      ajax().put('/api/user/leave-room')
      .then(res => {
        window.location.href = '/'
        $eventHub.$emit('off-loading')
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Rời phòng thành công',
          timeout: 3000
        })
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('success-alert', {
          title: 'Không thành công',
          message: 'Đã có lỗi xảy ra, hãy thử lại sau',
          timeout: 3000
        })
      })
    }
  }
}
</script>