<template>
  <div>
    <nav-header/>
    <div class="container mt-3">
        <h2>Thông tin tài khoản</h2>
        <form @submit.prevent="update">
          <div class="d-flex">
            <button type="button" v-show="!edit" class="btn text-primary ml-auto" @click="enterEdit()">
              <i class="fas fa-pencil"></i> <span style="font-weight: 600">Chỉnh sửa</span>
            </button>
            <button type="button" v-show="edit" class="btn text-danger ml-auto" @click="leaveEdit()">
              <i class="fas fa-times"></i> <span style="font-weight: 600">Hủy</span>
            </button>
          </div>
          <div>
          <div class="form-group" v-if="user.profile">
            <div class="text-center">
              <img style="width: 100px;height: 100px;border-radius: 50%;margin-bottom: 10px" :src="user.profile.image==null?'/images/default.svg':user.profile.image" :alt="user.name">
              <br>
              <div>
                <button type="button" v-show="edit" class="btn text-primary" onclick="clickTarget('#profile-image')">
                  Thay đổi
                </button>
                <button type="button" class="btn text-primary" v-show="edit&&edit_avatar" @click="uploadImage()">
                  <i class="far fa-save"></i> Lưu
                </button>
              </div>
            </div>
          </div>
          </div>
          <label>Tên người dùng</label>
          <div class="form-group">
            <div class="holder" v-show="!edit">{{ user.name }}</div>
            <input type="text" v-show="edit" class="input" v-model="user.name" placeholder="Tên người dùng">
          </div>
          <div class="form-group row" v-if="user.profile">
            <div class="col-6">
              <label>Họ</label>
              <div class="form-group">
                <div class="holder" v-show="!edit">{{ user.profile.lastname?user.profile.lastname:'Chưa rõ' }}</div>
                <input type="text" v-show="edit" class="input" v-model="user.profile.lastname" placeholder="Họ">
              </div>
            </div>
            <div class="col-6">
              <label>Tên</label>
              <div class="form-group">
                <div class="holder" v-show="!edit">{{ user.profile.firstname?user.profile.firstname:'Chưa rõ' }}</div>
                <input type="text" v-show="edit" class="input" v-model="user.profile.firstname" placeholder="Tên">
              </div>
            </div>
          </div>
          <div class="form-group" v-if="edit||(user.profile && user.profile.phone)">
            <label>Số điện thoại</label>
            <div class="holder" v-show="!edit">{{ user.profile.phone }}</div>
            <input type="text" v-show="edit" class="input" v-model="user.profile.phone" placeholder="Số điện thoại">
          </div>
          <div class="form-group" v-if="user.profile">
            <label>Ngày sinh</label>
            <div class="holder" v-show="!edit">{{ user.profile.date_of_birth?user.profile.date_of_birth.split('-').reverse().join('/'):'Chưa rõ' }}</div>
            <input type="date" v-show="edit" class="input" v-model="user.profile.date_of_birth">
          </div>
          <div class="form-group" v-if="user.profile">
            <label>Địa chỉ</label>
            <div class="holder" v-show="!edit">{{ user.profile.address?user.profile.address:'Chưa rõ' }}</div>
            <input type="text" v-show="edit" class="input" v-model="user.profile.address" placeholder="Địa chỉ">
          </div>
          <div class="form-group text-right" v-show="edit">
            <button class="btn btn-outline-primary">
              <i class="far fa-save"></i> Lưu
            </button>
          </div>
        </form>
        <input type="file" id="profile-image" @change="previewImage" class="d-none">
    </div>
  </div>
</template>
<script>
import NavHeader from '../layouts/Header'
export default {
  components: {
    NavHeader,
  },
  data () {
    return {
      edit: false,
      backup_data: {},
      image: '',
      edit_avatar: false
    }
  },
  computed: {
    user () {
      return this.$store.getters['auth/user']
    }
  },
  methods: {
    previewImage (e) {
      try {
        this.image = e.target.files[0]
        this.user.profile.image = URL.createObjectURL(this.image)
        this.edit_avatar=true
      } catch (error) {
        console.log(error)
      }
    },
    getData() {
      let profile = {
        firstname: this.user.profile.firstname,
        lastname: this.user.profile.lastname,
        phone: this.user.profile.phone,
        address: this.user.profile.address,
        date_of_birth: this.user.profile.date_of_birth
      }
      let setting = {
        verification_2_step: this.user.setting.verification_2_step
      }
      return {
        name: this.user.name,
        profile: profile,
        setting: setting
      }
    },
    uploadImage () {
      let data = new FormData()
      data.append('image', this.image)
      $request.post(`/api/user/${this.user.id}/avatar`, data, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      .then(res => {
        this.edit_avatar=false
        this.user.profile.image = res.data
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Đã cập nhật thành công avatar mới',
          timeout: 3000
        })
      })
      .catch(err => {
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Chưa thể lưu avatar mới, hãy thử lại sau',
          timeout: 3000
        })
      })
    },
    update () {
      $request.put(`/api/user/${this.user.id}`, this.getData())
      .then(res => {
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Đã cập nhật thành công thông tin  mới',
          timeout: 3000
        })
      })
      .catch(err => {
        this.restore()
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Chưa thể lưu thông tin mới, hãy thử lại sau',
          timeout: 3000
        })
      })
    },
    backup () {
      this.backup_data = this.getData()
      this.backup_data.profile.image = this.user.profile.image
    },
    restore () {
      this.user.name = this.backup_data.name
      this.user.profile.firstname = this.backup_data.profile.firstname
      this.user.profile.lastname = this.backup_data.profile.lastname
      this.user.profile.phone = this.backup_data.profile.phone
      this.user.profile.image = this.backup_data.profile.image
      this.user.profile.address = this.backup_data.profile.address
      this.user.profile.date_of_birt = this.backup_data.profile.date_of_birth
      this.user.setting.verification_2_step = this.backup_data.setting.verification_2_step
    },
    enterEdit () {
      this.edit = true
    },
    leaveEdit () {
      this.edit = false
      this.edit_avatar=false
    }
  }
}
</script>
<style scoped>
label {
  font-weight: 600;
  margin-bottom: 0px;
}
.container {
  margin-bottom: 60px;
}
</style>