<template>
  <div contain-box class="mt-4">
    <form v-if="room" @submit.prevent="update()" class="bg-white p-3" style="box-shadow: 0px 0px 15px #e0e0e0;border-top: 5px solid #3490dc;">
      <div class="text-center">
        <h1 style="font-size: x-large" class="mb-3">Tìm người ở ghép</h1>
      </div>
      <div class="row justify-content-end">
        <button type="button" class="btn" @click="editable=!editable">
          <span v-if="!editable" class="text-primary"><i class="fas fa-pen"></i> Sửa</span>
          <span v-else class="text-danger">Hủy</span>
        </button>
        <button type="button" v-if="!editable" @click="confirmDestroy" class="btn text-danger">Xóa</button>
      </div>
      <div class="d-flex">
        <label>Số người mong muốn <span v-if="editable" class="text-danger">*</span></label> <span v-if="!editable" class="ml-auto">{{ room.roommate_wanted.number }}</span>
      </div>
      <div class="form-group">
        <div v-if="editable" class="text-muted text-small pb-2">
          {{ (`Phòng chỉ có thể có tối đa ${room.limit_renter} người thuê`) }}
        </div>
        <input v-if="editable" type="number" class="form-control" v-model="room.roommate_wanted.number" placeholder="Số người ở cùng phòng mong muốn" required>
      </div>
      <!--  -->
      <label>Thông tin liên hệ</label>
      <div class="form-group">
        <textarea v-if="editable" class="form-control" v-model="room.roommate_wanted.contact" placeholder="vd: số điện thoại 0123456789"></textarea>
        <div v-else class="holder">{{ room.roommate_wanted.contact }}</div>
      </div>
      <!--  -->
      <label>Thông tin thêm</label>
      <div class="form-group">
        <textarea v-if="editable" class="form-control" v-model="room.roommate_wanted.content" placeholder="vd: Phòng hiện đang rất đủ đồ, chỉ cần chuyển đến ở"></textarea>
        <div v-else class="holder">{{ room.roommate_wanted.content }}</div>
      </div>
      <transition name="slide-fade">
        <div class="form-group text-danger text-center" style="padding: 10px;border-radius: 8px;background-color: #e3342f40" v-show="error.status">{{ error.message }}</div>
      </transition>
      <div class="text-right" v-if="editable">
        <button class="btn btn-primary">Cập nhật</button>
      </div>
    </form>
    <div class="text-center text-muted mt-3">
      Phòng sẽ được tìm thấy trong danh sách tìm người ở ghép cùng với những thông tin của người đăng tin này
    </div>
    <div class="text-center">
      <svg width="48px" height="20px" fill="#6c757d" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1640 664">
      <path d="M542.06 215.46L542.06 264.09Q542.06 290.17 535.62 310.68Q529.17 331.18 518.04 347Q507.2 362.53 492.84 373.66Q478.49 384.8 462.67 392.12Q447.14 399.15 431.03 402.38Q415.21 405.6 401.14 405.6L560.23 543L442.45 543L283.66 405.6L228.88 405.6L228.88 329.43L401.14 329.43Q415.5 328.25 427.22 323.57Q439.23 318.59 447.73 310.38Q456.52 302.18 461.2 290.75Q465.89 279.04 465.89 264.09L465.89 216.05Q465.89 209.6 464.13 206.38Q462.67 202.86 460.03 201.4Q457.69 199.64 454.76 199.35Q452.12 199.05 449.78 199.05L198.41 199.05L198.41 543L122.24 543L122.24 161.26Q122.24 153.35 125.17 146.32Q128.1 139.29 133.08 134.02Q138.35 128.74 145.38 125.81Q152.41 122.88 160.62 122.88L449.78 122.88Q475.27 122.88 492.84 132.26Q510.42 141.34 521.26 155.4Q532.39 169.17 537.08 185.29Q542.06 201.4 542.06 215.46ZM947.53 331.18Q947.53 347 941.96 365.46Q936.4 383.63 923.8 399.45Q911.5 414.97 891.28 425.52Q871.36 436.07 842.65 436.07L705.25 436.07L705.25 363.7L842.65 363.7Q858.18 363.7 866.67 354.33Q875.17 344.66 875.17 330.6Q875.17 315.66 865.5 307.16Q856.13 298.66 842.65 298.66L705.25 298.66Q689.72 298.66 681.22 308.33Q672.73 317.71 672.73 331.77L672.73 438.12Q672.73 453.35 682.1 461.85Q691.77 470.34 705.83 470.34L842.65 470.34L842.65 543L705.25 543Q689.43 543 670.97 537.43Q652.8 531.87 636.98 519.56Q621.46 506.96 610.91 487.04Q600.36 466.83 600.36 438.12L600.36 331.18Q600.36 315.36 605.93 297.2Q611.5 278.74 623.8 263.21Q636.4 247.39 656.32 236.85Q676.54 226.3 705.25 226.3L842.65 226.3Q858.47 226.3 876.63 231.87Q895.09 237.43 910.62 250.03Q926.44 262.34 936.98 282.55Q947.53 302.47 947.53 331.18ZM1349.78 367.22L1349.78 543L1273.61 543L1273.61 367.22Q1273.61 352.28 1268.33 340.56Q1263.35 328.55 1254.56 320.05Q1245.77 311.55 1233.76 307.16Q1222.04 302.47 1208.27 302.47L1074.97 302.47L1074.97 543L998.8 543L998.8 264.09Q998.8 256.18 1001.73 249.45Q1004.66 242.41 1009.93 237.43Q1015.21 232.16 1022.24 229.23Q1029.27 226.3 1037.18 226.3L1208.86 226.3Q1223.21 226.3 1239.04 229.52Q1255.15 232.75 1270.68 240.07Q1286.5 247.1 1300.56 258.23Q1314.91 269.07 1325.75 284.89Q1336.89 300.42 1343.33 320.93Q1349.78 341.44 1349.78 367.22ZM1695.19 226.3L1695.19 302.47L1562.18 302.47L1562.18 543L1485.13 543L1485.13 302.47L1386.11 302.47L1386.11 226.3L1485.13 226.3L1485.13 122.88L1562.18 122.88L1562.18 226.3L1695.19 226.3Z"></path></svg>
    </div>
    <confirm-box :name="'delete-house'" :title="'Xóa bài đăng'" :message="'Bài đăng này sẽ bị xóa'" @confirm="destroy()"/>
  </div>
</template>
<script>
import ConfirmBox from '../../utilities/ConfirmBox'
export default {
  components: {
    ConfirmBox
  },
  name: 'roommate-wanted-list',
  data() {
    return {
      editable: false,
      error: {
        status: false,
        message: ''
      }
    }
  },
  computed: {
    room () {
      return this.$store.getters['rooms/first']
    }
  },
  created() {
    if (this.room==undefined) this.get()
  },
  methods: {
    update() {
      $eventHub.$emit('on-loading')
      ajax().put('/api/renter/wanted-roommate', {
        number: this.room.roommate_wanted.number,
        contact: this.room.roommate_wanted.contact,
        content: this.room.roommate_wanted.content,
      })
      .then(res => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Tin được cập nhật thành công',
          timeout: 3000
        })
        this.editable = false
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Hiện chưa thể cập nhật thông tin, hãy thử lại',
          timeout: 3000
        })
      })
    },
    get () {
      $eventHub.$emit('on-loading')
      ajax().get('/api/user/room')
      .then(res => {
        $eventHub.$emit('off-loading')
        this.$store.commit('rooms/rooms', [res.data])        
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        this.$router.push({name: 'rented-room'})
      })
    },
    confirmDestroy() {
      this.$modal.show('delete-house')
    },
    destroy() {
      $eventHub.$emit('on-loading')
      ajax().delete('/api/renter/wanted-roommate')
      .then(res => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Tin được xóa thành công',
          timeout: 3000
        })
        this.$router.push({name: 'rented-room'})
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Hiện chưa thể xóa thông tin, hãy thử lại',
          timeout: 3000
        })
      })
    }
  }
}
</script>
<style scoped>
label {
  font-weight: 600;
}
</style>