<template>
  <div contain-box class="mt-2" v-if="user">
    <form v-if="user.profile" page-section @submit.prevent="post">
      <h2 class="text-center py-3">
        Đăng ký chủ trọ
      </h2>
      <div class="row form-group">
        <div class="col-6">
          <label for="" class="text-bold">Họ <span class="text-danger">*</span></label>
          <input type="text" class="form-control" placeholder="Họ" v-model="user.profile.lastname" required>
        </div>
         <div class="col-6">
            <label for="" class="text-bold">Tên <span class="text-danger">*</span></label>
          <input type="text" class="form-control" placeholder="Tên" v-model="user.profile.firstname" required>
        </div>
      </div>
      <div class="form-group">
        <label for="" class="text-bold">Số điện thoại <span class="text-danger">*</span></label>
        <input type="tel" class="form-control" placeholder="Số điện thoại (di động)" v-model="user.profile.phone" required>
        <transition name="slide-fade">
          <div v-show="errors.phone.status" class="text-danger">{{ errors.phone.message }}</div>
        </transition>
      </div>
      <div class="form-group">
        <label for="" class="text-bold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Số nhà, ngách, ngõ" v-model="user.profile.address">
      </div>
      <div class="form-group">
        <div class="pt-2 text-bold">
          Ảnh chứng minh thư nhân dân hoặc căn cước công dân <span class="text-danger">*</span>
        </div>
        <button type="button" class="btn text-success c-flex-middle" onclick="clickTarget('#image')">
          <camera-icon :width="'15px'" :height="'15px'" class="fill-green"/>&nbsp;Chọn ảnh
        </button>
        <transition name="slide-fade">
          <div v-show="errors.image.status" class="text-danger">{{ errors.image.message }}</div>
        </transition>
        <input type="file" id="image" class="d-none" @change="inputImage" accept="image/*">
      </div>
      <div class="form-group text-center" v-show="image!=null">
        <img :src="image" style="max-width: 100%">
      </div>
      <div class="text-right">
        <button class="btn btn-primary">Gửi đăng ký</button>
      </div>
    </form>
  </div>
</template>
<script>
import CameraIcon from '../../../icons/Camera'
import Validator from '../../../utilities/validate'
export default {
  components: {
    CameraIcon
  },
  computed: {
    user() {
      return this.$store.getters['auth/user']
    },
    params() {
      return {
        firstname: this.user.profile.firstname,
        lastname: this.user.profile.lastname,
        phone: this.user.profile.phone,
        address: this.user.profile.address,
        image: this.image,
      }
    }
  },
  watch: {
    "$route.params": {
      handler(params) {
        this.id = params.id
        this.token = params.token
      },
      immediate: true,
      deep: true
    }
  },
  data() {
    return {
      errors: {
        phone: {
          status: false,
          message: ''
        },
        image: {
          status: false,
          message: ''
        }
      },
      image: null
    }
  },
  methods: {
    post() {
      if (!this.validate()) return false
      ajax().post(`/api/user/${this.id}/apply-for-owner?token=${this.token}`, this.params)
      .then(res => {
        this.$router.push({name: "account"})
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: "Đã gửi đăng ký thành công, chúng tôi sẽ hồi đáp trong thời gian sớm nhất",
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
          this.image = res.data.urls[0]
        })
        .catch(err => {
          $eventHub.$emit('error-alert', {
            title: 'Không thành công',
            message: 'Hiện không thể upload ảnh, xin hãy thử lại sau',
            timeout: 3000
          })
        })
      }
    }
  }
}
</script>