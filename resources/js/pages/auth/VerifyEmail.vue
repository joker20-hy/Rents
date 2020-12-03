<template>
  <div class="auth-form bg-white m-auto px-2 py-4">
    <div class="text-center">
      <router-link :to="{name: 'home'}">
        <rent-logo-icon :height="'50px'" :width="'auto'" class="fill-blue"/>
      </router-link>
    </div>
    <form @submit.prevent="sendCode()" v-if="user_email==null">
      <div class="text-center my-2">
        <h5 class="mb-0">Khôi phục tài khoản</h5>
      </div>
      <div class="form-group pt-3">
        <label for="">Nhập email để khôi phục tài khoản <span class="text-danger">*</span></label>
        <input type="email" class="form-control" :class="email_error?'border-danger text-danger':''" placeholder="Email" v-model="email" required>
        <transition name="slide-fade">
          <span class="text-danger" v-show="email_error">Tài khoản không tồn tại</span>
        </transition>
      </div>
      <div class="text-right">
        <button class="btn btn-primary">Tiếp theo</button>
      </div>
      <div class="text-center pt-2">
        <router-link :to="{name: 'login'}">Quay lại trang đăng nhập</router-link>
      </div>
    </form>
    <form @submit.prevent="verifyCode()" v-if="user_email!=null">
      <div class="form-group text-center py-2">
        <span style="padding: 2px 6px;border-radius: 12px;border: 1px solid var(--gray)">
          <i class="fas fa-user-circle"></i> {{ user_email }}
        </span>
        <div class="pt-3" v-if="!code_sent">Mã xác nhận đang được gửi tới email của bạn...</div>
        <div v-else class="pt-2">Mã xác nhập đã được gửi về email của bạn</div>
      </div>
      <transition name="slide-fade">
        <div v-if="code_sent">
          <label for="">Nhập mã xác nhận</label>
          <input type="text" class="form-control" v-model="verify_code" placeholder="Mã xác nhận" required>
          <div class="text-right pt-3" v-if="code_sent">
            <button class="btn btn-primary">Tiếp theo</button>
          </div>
        </div>
      </transition>
      <div class="text-center pt-2">
        <router-link :to="{name: 'login'}">Quay lại trang đăng nhập</router-link>
      </div>
    </form>
  </div>
</template>
<script>
import RentLogoIcon from '../../icons/RentLogo'
export default {
  components: {
    RentLogoIcon
  },
  data () {
    return {
      code_sent: false,
      user_email: $auth.check?$auth.user.email:null,
      user_id: '',
      email_error: false,
      email: '',
      verify_code: '',
      next_route: ''
    }
  },
  computed: {
    user() {
      return $auth.user
    },
    auth() {
      return $auth.check
    }
  },
  mounted() {
    if ($auth.check) this.sendCode(this.user_email)
  },
  watch: {
    '$route.query.next': {
      handler (next) {
        this.next_route = next
      },
      immediate: true,
      deep: true
    }
  },
  methods: {
    sendCode(email=null) {
      email = email==null?this.email:email
      $eventHub.$emit('on-loading')
      ajax().get(`/api/verify?email=${email==null?this.email:email}`)
      .then(res => {
        $eventHub.$emit('off-loading')
        this.email_error = false
        this.user_email = email
        this.code_sent = true
        this.user_id = res.data.id
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        if (err.response.status==404) {
          this.email_error = true
          setTimeout(() => {
            this.email_error = false
          }, 5000)
        } else {
          $eventHub.$emit('error-alert', {
            title: 'Đã có lối',
            message: 'Xin hãy thử lại sau'
          })
        }
      })
    },
    verifyCode() {
      ajax().post(`/api/verify/${this.user_id}`, {
        code: this.verify_code
      })
      .then(res => {
        this.$router.push({name: this.next_route, params: {id: this.user_id, token: res.data}})
      })
      .catch(err => {
        $eventHub.$emit('error-alert', {
          title: 'Đã có lối',
          message: 'Xin hãy thử lại sau'
        })
      })
    }
  }
}
</script>
<style scoped>
  .auth-form {
    width: 400px;
    max-width: 100%;
    margin-left: auto;
    margin-right: auto;
    border-radius: 10px;
    box-shadow: 0px 0px 30px #0003;
  }
</style>