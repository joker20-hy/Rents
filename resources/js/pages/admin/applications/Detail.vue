<template>
  <div class="mt-2" page-section contain-box v-if="application">
    <h3>Đơn đăng ký chủ nhà</h3>
    <div class="form-group">
      <span class="text-bold">Họ và tên: </span> {{ application.user.profile.lastname }} {{ application.user.profile.firstname }}
    </div>
    <div class="form-group">
      <span class="text-bold">Số điện thoại: </span> {{ application.user.profile.phone }}
    </div>
    <div class="form-group">
      <span class="text-bold">Địa chỉ: </span> {{ application.user.profile.address }}
    </div>
    <div class="form-group">
      <span class="text-bold">Ảnh CMNN/CCCD: </span>
      <img :src="application.image" class="mw-100 rounded" alt="">
    </div>
    <div>
      <div class="text-bold">Lý do từ chối</div>
      {{ application.decline_reasons }}
    </div>
    <transition name="slide-fade">
      <form class="form-group" v-show="declining" @submit.prevent="decline">
        <div class="form-group">
          <label class="text-bold" for="">Nhập lý do từ chối</label>
          <textarea rows="5" class="form-control" placeholder="Lý do từ chối" v-model="application.decline_reasons"></textarea>
        </div>
        <button class="btn btn-primary">
          Hoàn thành
        </button>&nbsp;
        <button type="button" @click="declining=false" class="btn text-danger">
          Hủy
        </button>
      </form>
    </transition>
    <!-- Status check need -->
    <div class="text-right" v-if="application.status!=statuses.DECLINED" v-show="!declining">
      <button @click="declining=true" class="btn btn-primary">
        Từ chối
      </button>
      &nbsp;
      <button class="btn btn-success" @click="approve()">
        Xác nhận
      </button>
    </div>
    <!--  -->
  </div>
</template>
<script>
export default {
  data() {
    return {
      id: '',
      declining: false,
      statuses: $config.OWNER_APPLICATION_STATUS
    }
  },
  watch: {
    "$route.params.id": {
      handler(id) {
        this.id = id
        if (this.application==undefined) this.get() 
      },
      immediate: true,
      deep: true
    }
  },
  computed: {
    application() {
      return this.$store.getters['applications/first'](this.id)
    }
  },
  methods: {
    get() {
      $eventHub.$emit('on-loading')
      ajax().get(`/api/owner/application/${this.id}`)
      .then(res => {
        $eventHub.$emit('off-loading')
        this.$store.commit('applications/applications', [res.data])
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: err.response.data.message,
          timeout: 4000
        })
      })
    },
    approve() {
      $eventHub.$emit('on-loading')
      ajax().put(`/api/owner/application/${this.id}/approve`)
      .then(res => {
        this.$router.push({name: 'application-list'})
        $eventHub.$emit('off-loading')
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Đơn đăng ký đã được phê duyệt thành công',
          timeout: 5000
        })
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: err.response.data.message,
          timeout: 4000
        })
      })
    },
    decline() {
      $eventHub.$emit('on-loading')
      ajax().put(`/api/owner/application/${this.id}/decline`, {
        decline_reasons: this.application.decline_reasons
      })
      .then(res => {
        this.declining = false
        $eventHub.$emit('off-loading')
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Đã từ chối đơn đăng ký thành công',
          timeout: 5000
        })
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: err.response.data.message,
          timeout: 4000
        })
      })
    }
  }
}
</script>