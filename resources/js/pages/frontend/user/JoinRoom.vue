<template>
  <div class="row mx-0 justify-content-center" style="min-height: 100vh">
    <div class="col-md-8 col-lg-6 col-xl-5 text-center py-3 bg-white m-auto">
      <h1 style="font-size: large" class="px-2 py-3 text-light bg-primary text-left">Xác nhận thuê phòng</h1>
      <p>{{ room_name }}</p>
      <button class="btn btn-outline-primary" @click="confirm()" :disabled="confirming">Xác nhận</button>
    </div>
  </div>
</template>
<script>
export default {
  data () {
    return {
      room_id: this.$route.params.room_id,
      room_name: '',
      confirming: false
    }
  },
  created () {
    this.getRoom()
  },
  computed: {
    user () {
      return this.$store.getters['auth/user']
    }
  },
  methods: {
    getRoom () {
      $eventHub.$emit('on-loading')
      $request.get(`/api/room/${this.room_id}`)
      .then(res => {
        this.room_name = `${res.data.name}, ${res.data.house.address_detail}`
        $eventHub.$emit('off-loading')
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
      })
    },
    confirm () {
      this.confirming = true
      $eventHub.$emit('on-loading')
      $request.post(`/api/user/rent-room/${this.room_id}`, {})
      .then(res => {
        this.confirming = false
        $eventHub.$emit('off-loading')
        this.user.room_id = this.room_id
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Đã xác nhận thành công',
          timeout: 3000
        })
        this.$route.push({name: 'home'})
      })
      .catch(err => {
        this.confirming = false
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Chưa thể xác nhận, hãy thử lại',
          timeout: 3000
        })
      })
    }
  }
}
</script>