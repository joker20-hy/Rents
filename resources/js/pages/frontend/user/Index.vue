<template>
  <div>
    <nav-header/>
    <div class="container">
        <h2>Thông tin tài khoản</h2>
        <form @submit.prevent>
            <label>Tên người dùng</label>
            <div class="form-group">
                <div class="holder" v-show="!edit">{{ user.name }}</div>
                <input type="text" v-show="edit" class="input" v-model="user.name" placeholder="Tên người dùng">
            </div>
            <div class="form-group row" v-if="user.profile">
                <div class="col-6">
                    <label>Họ</label>
                    <div class="form-group">
                        <div class="holder" v-show="!edit">{{ user.profile.lastname }}</div>
                        <input type="text" v-show="edit" class="input" v-model="user.profile.lastname" placeholder="Họ">
                    </div>
                </div>
                <div class="col-6">
                    <label>Tên</label>
                    <div class="form-group">
                        <div class="holder" v-show="!edit">{{ user.profile.firstname }}</div>
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
              <div class="holder" v-show="!edit">{{ user.profile.date_of_birth.split('-').reverse().join('/') }}</div>
              <input type="date" v-show="edit" class="input" v-model="user.profile.date_of_birth">
            </div>
            <div class="form-group" v-if="user.profile">
              <label>Địa chỉ</label>
              <div class="holder" v-show="!edit">{{ user.profile.address }}</div>
              <input type="text" v-show="edit" class="input" v-model="user.profile.address" placeholder="Địa chỉ">
            </div>
        </form>
    </div>
  </div>
</template>
<script>
import NavHeader from '../layouts/Header'
export default {
  components: {
    NavHeader
  },
  data () {
    return {
      edit: false,
      user: {}
    }
  },
  watch: {
    "$route.params.id": {
	  handler (id) {
		this.get(id)
	  },
	  deep: true,
      immediate: true
	}
  },
  methods: {
    get (id) {
      $request.get(`/api/user/find/${id}`)
      .then(res => {
        this.user = res.data
      })
      .catch(err => {
        console.log(err)
      })
    }
  }
}
</script>
<style scoped>
label {
  font-weight: 600;
  margin-bottom: 0px;
}
</style>