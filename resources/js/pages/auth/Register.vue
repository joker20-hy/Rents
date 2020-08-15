<template>
  <div class="auth-container">
    <div class="register-form">
      <div class="intro-frame">
        <img src="/images/register.jpg" alt="">
      </div>
      <form @submit.prevent="register()">
        <h3 class="text-primary mb-3">Đăng ký tài khoản</h3>
        <div class="form-group">
          <label>Tên người dùng <span class="text-danger">*</span></label>
          <input type="text" class="input" :class="errors.name?'error':''" placeholder="Tên người dùng" v-model="user.name" @keyup="errors.name=''" required>
          <transition name="slide-fade">
            <span v-show="show_error&&errors.name" class="text-danger">Tên người dùng không hợp lệ</span>
          </transition>
        </div>
        <div class="form-group">
          <label>Email <span class="text-danger">*</span></label>
          <input type="email" class="input" :class="errors.email?'error':''" placeholder="Email" v-model="user.email" @keyup="errors.email=''" required>
          <transition name="slide-fade">
            <span v-show="show_error&&errors.email" class="text-danger">Email không hợp lệ</span>
          </transition>
        </div>
        <div class="form-group">
          <label>Mật khẩu <span class="text-danger">*</span></label>
          <input type="password" class="input" :class="errors.password?'error':''" placeholder="Mật khẩu"  v-model="user.password" @keyup="errors.password=''" required>
          <transition name="slide-fade">
            <span v-show="show_error&&errors.password" class="text-danger">Mật khẩu không hợp lệ</span>
          </transition>
        </div>
        <div class="form-group">
          <label>Xác nhận mật khẩu <span class="text-danger">*</span></label>
          <input type="password" class="input" :class="errors.password_confirmation?'error':''" placeholder="Xác nhận mật khẩu" v-model="user.password_confirmation" @keyup="errors.password_confirmation=''" required>
          <transition name="slide-fade">
            <span v-if="show_error&&errors.password_confirmation" class="text-danger">{{ errors.password_confirmation }}</span>
          </transition>
        </div>
        <div class="text-right">
          <button class="btn btn-outline-primary">Đăng ký</button>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
export default {
  data () {
    return {
      user: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      errors: {},
      show_error: false
    }
  },
  methods: {
    validate() {
      if (this.user.password!=this.user.password_confirmation) {
        this.showErrors({password_confirmation: 'Xác nhận mật khẩu không đúng'})
        return false
      }
      return true
    },
    register () {
      if(!this.validate()) return false
      $request.post('/api/register', this.user)
      .then(res => {
        this.$router.push({name: 'login'})
      })
      .catch(err => {
        this.showErrors(err.response.data.errors)
      })
    },
    showErrors(errors) {
      this.errors = errors
      this.show_error = true
      setTimeout(() => this.show_error=false, 4000)
    }
  }
}
</script>
<style scoped>
  .auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    background-color: var(--blue);
    padding: 10px;
  }
  label {
    font-weight: 600;
    margin-bottom: 0px;
    color: var(--light);
  }
  .input {
    display: block;
    width: 100%;
    border: none;
    border-bottom: 2px solid #ccc;
    padding: 8px 15px;
    outline: unset!important;
    color: var(--light);
  }
  .input.error {
    color: var(--red)!important;
    border-color: var(--red)!important;
  }
  .input:focus {
    border-color: var(--blue);
  }
  .register-form {
    width: 720px;
    max-width: 100%;
    margin-left: auto;
    margin-right: auto;
    background-image: url(/images/register.jpg);
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    box-shadow: 0px 1px 3px;
    border-radius: 10px;
  }
  .intro-frame, form {
    float: left;
  }
  .register-form form {
    width: 100%;
    padding: 30px 10px;
    background-color: #212529e8;
    border-radius: 10px;
  }
  .intro-frame {
    height: 100%;
    display: none;
  }
  @media(min-width: 768px) {
    .register-form {
      background-image: unset;
      background-color: var(--light);
    }
    .input, label {
      color: var(--dark);
    }
    .register-form .intro-frame {
      display: block;
    }
    .register-form .intro-frame, .register-form form {
      width: 50%;
    }
    .register-form form {
      padding: 20px 10px;
      background-color: transparent;
    }
  }
  .intro-frame img {
    width: 100%;
    border-radius: 10px 0px 0px 10px;
  }
</style>