<template>
  <div contain-box class="mt-2">
    <form v-if="application" page-section @submit.prevent="update">
      <h2 class="text-center py-3">Đăng ký chủ trọ</h2>
      <div v-if="application.status==statuses.DECLINED" class="c-toolbar c-flex-middle form-group">
        <button v-if="editable" class="btn btn-primary c-flex-middle">
          Lưu thay đổi
        </button>
        <button type="button" v-show="editable" class="btn btn-danger ml-auto" @click="cancelEdit()">
          Hủy
        </button>
        <button type="button" v-show="!editable" class="btn btn-primary ml-auto" @click="enterEdit()">
          Chỉnh sửa
        </button>
      </div>
      <div class="row form-group">
        <div class="col-6">
          <label for="" class="text-bold">Họ <span class="text-danger">*</span></label>
          <input v-if="editable" type="text" class="form-control" placeholder="Họ" v-model="application.user.profile.lastname" required>
          <div v-else class="holder">{{ application.user.profile.lastname }}</div>
        </div>
         <div class="col-6">
          <label for="" class="text-bold">Tên <span class="text-danger">*</span></label>
          <input v-if="editable" type="text" class="form-control" placeholder="Tên" v-model="application.user.profile.firstname" required>
          <div v-else class="holder">{{ application.user.profile.firstname }}</div>
        </div>
      </div>
      <div class="form-group">
        <label for="" class="text-bold">Số điện thoại <span class="text-danger">*</span></label>
        <input v-if="editable" type="tel" class="form-control" placeholder="Số điện thoại (di động)" v-model="application.user.profile.phone" required>
        <div v-else class="holder">{{ application.user.profile.phone }}</div>
        <transition name="slide-fade">
          <div v-show="errors.phone.status" class="text-danger">{{ errors.phone.message }}</div>
        </transition>
      </div>
      <div class="form-group">
        <label for="" class="text-bold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
        <input v-if="editable" type="text" class="form-control" placeholder="Số nhà, ngách, ngõ" v-model="application.user.profile.address">
        <div v-else class="holder">{{ application.user.profile.address }}</div>
      </div>
      <div class="form-group">
        <div class="pt-2 text-bold">
          Ảnh chứng minh thư nhân dân hoặc căn cước công dân <span class="text-danger">*</span>
        </div>
        <button v-if="editable" type="button" class="btn text-success c-flex-middle" onclick="clickTarget('#image')">
          <camera-icon :width="'15px'" :height="'15px'" class="fill-green"/>&nbsp;Thay ảnh
        </button>
        <transition name="slide-fade">
          <div v-show="errors.image.status" class="text-danger">{{ errors.image.message }}</div>
        </transition>
        <input type="file" id="image" class="d-none" @change="inputImage" accept="image/*" required>
      </div>
      <div class="form-group text-center">
        <img :src="application.image" style="max-width: 100%">
      </div>
    </form>
  </div>
</template>
<script>
import CameraIcon from '../../../../icons/Camera'
export default {
  components: {
    CameraIcon
  },
  data() {
    return {
      editable: false,
      errors: {
        phone: {},
        image: {}
      },
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
    enterEdit() {
      this.editable = true
    },
    cancelEdit() {
      this.editable = false
    },
        validate() {
      if (!Validator.isPhone(this.user.profile.phone)) return this.showError('phone',"Số điện thoại phải là sô di động 10 số và bắt đầu bằng số 0")
      else this.errors.phone.status = false
      if (this.image==null) return this.showError('image',"Bạn phải chọn ảnh")
      return true
    },
    showError(criteria, message) {
      this.errors[criteria].status = true
      this.errors[criteria].message = message
      setTimeout(() => {
        this.errors[criteria].status = false
      }, 4000)
      return false
    },
    reset() {
      this.errors = {}
    },
    inputImage(e) {
      if (e.target.files.length>0) {
        let formData = new FormData()
        formData.append('images[0]', e.target.files[0])
        ajax().post(`/api/image`, formData, { headers: {'Content-Type': 'multipart/form-data'} })
        .then(res => {
          this.application.image = res.data.urls[0]
        })
        .catch(err => {
          $eventHub.$emit('error-alert', {
            title: 'Không thành công',
            message: 'Hiện không thể upload ảnh, xin hãy thử lại sau',
            timeout: 3000
          })
        })
      }
    },
    update() {
      ajax().put(`/api/owner/application/${this.id}`, {
        firstname: this.application.user.profile.firstname,
        lastname: this.application.user.profile.lastname,
        phone: this.application.user.profile.phone,
        address: this.application.user.profile.address,
        image: this.application.image
      })
      .then(res => {
        this.$router.push({name: "account"})
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: "Đăng ký đã được cập nhật, chúng tôi sẽ hồi đáp trong thời gian sớm nhất",
          timeout: 4000
        })
      })
      .catch(err => {
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: err.response.data.message,
          timeout: 3000
        })
      })
    }
  }
}
</script>