<template>
  <div>
    <nav-header/>
    <div class="p-2">
      <router-view></router-view>
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
      ajax().post(`/api/user/${this.user.id}/avatar`, data, {
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
      ajax().put(`/api/user/${this.user.id}`, this.getData())
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