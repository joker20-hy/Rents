<template>
  <div contain-box v-if="room">
    <div class="bg-white p-2 mb-2" style="border-radius: 8px;box-shadow: 0px 0px 10px #80808040">
      <h1 style="font-size: large">{{ room.name }}</h1>
      <p><span style="font-weight: 600">Địa chỉ:</span> {{ room.house.address_detail }}</p>
      <p><span style="font-weight: 600">Giá thuê:</span> {{ range(room.price) }} vnđ</p>
      <p><span style="font-weight: 600">Số người tối đa:</span> {{ room.limit_renter }}</p>
    </div>

    <router-link :to="{name: 'payment-room-list', params: {id: room.id}}" class="bg-white p-2 mb-2 c-flex-middle">
      <credit-card-icon :width="'16px'" :height="'16px'" class="fill-blue" style="transform: translateY(-1px)"/> &nbsp;Hóa đơn phòng ({{ room.payments.length }})
      <button class="btn ml-auto text-primary">
        <chevron-right-icon :width="'15px'" :height="'15px'" class="fill-blue"/>
      </button>
    </router-link>
    <router-link :to="{name: 'room-pay-method', params: {id: room.id}}" class="bg-white p-2 mb-2 c-flex-middle">
      <coin-icon :width="'16px'" :height="'16px'" class="fill-blue" style="transform: translateY(-1px)"/>&nbsp;Phương thức thanh toán
      <button class="btn ml-auto text-primary">
        <chevron-right-icon :width="'15px'" :height="'15px'" class="fill-blue"/>
      </button>
    </router-link>
    <router-link :to="{name: 'review-room', params: {id: room.id}}" class="bg-white p-2 mb-2 c-flex-middle">
      <edit-icon :width="'16px'" :height="'16px'" class="fill-blue"/>&nbsp;Viết đánh giá
      <button class="btn ml-auto text-primary">
        <chevron-right-icon :width="'15px'" :height="'15px'" class="fill-blue"/>
      </button>
    </router-link>

    <router-link :to="{name: 'wanted-roommate'}" v-if="wantedRoommate" class="bg-white p-2 mb-2 d-flex align-items-center" style="box-shadow: 0px 0px 2px #80808040;border-radius: 6px">
      <i class="fas fa-user-plus"></i>&nbsp;Tìm người ở ghép
      <button class="btn ml-auto text-primary">
        <i class="fas fa-chevron-right"></i>
      </button>
    </router-link>
    <router-link :to="{name: 'roommate-wanted-detail'}" v-else-if="room.status==halfStatus" class="bg-white p-2 mb-2 d-flex align-items-center" style="box-shadow: 0px 0px 2px #80808040;border-radius: 6px">
      <i class="fas fa-user-plus"></i>&nbsp;Tìm người ở ghép
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
import CreditCardIcon from '../../../icons/CreditCard'
import CoinIcon from '../../../icons/Coin'
import EditIcon from '../../../icons/Edit'
import ChevronRightIcon from '../../../icons/ChevronRight'
export default {
  components: {
    ConfirmBox,
    CreditCardIcon,
    CoinIcon,
    EditIcon,
    ChevronRightIcon
  },
  data () {
    return {}
  },
  computed: {
    room () {
      return this.$store.getters['rooms/first']
    },
    wantedRoommate() {
      return (this.room.status!=$config.ROOM.STATUS.half)&&(this.room.renter_count<this.room.limit_renter)
    },
    halfStatus() {
      return $config.ROOM.STATUS.half
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
        this.$store.commit('users/roommate_wanted', res.data.roommate_wanted)
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
      ajax().put('/api/renter/leave-room')
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