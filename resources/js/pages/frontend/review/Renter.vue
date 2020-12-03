<template>
  <div review-contain>
    <transition name="slide-left">
      <form v-show="!done" page-section @submit.prevent="store()">
        <h2>Đánh giá</h2>
        <div class="pb-2" v-if="renter">
          @{{ renter.profile.lastname }} {{ renter.profile.firstname }}
        </div>
        <label>Tiêu đề</label>
        <div class="form-group">
          <input type="text" class="form-control" v-model="review.title" placeholder="Tiêu đề">
        </div>
        <label>Đánh giá</label>
        <review-stars class="form-group" :count="10" @change="getRate" :rate="review.rate"/>
        <div class="form-group">
        <textarea class="form-control" placeholder="Nhận xét bổ xung" v-model="review.description"></textarea>
        </div>
        <div class="form-group d-flex align-items-center justify-content-end" style="font-weight: 600;flex-wrap: wrap;position:relative" v-click-outside="function (){note=false}">
          <input type="checkbox" v-model="review.anonymous" :checked="review.anonymous">
          &nbsp;Đánh giá ẩn danh&nbsp;<i class="fas fa-info-circle" @click="note=!note"></i>
          <transition name="slide-fade">
            <div note v-show="note">
              Tên của người đánh giá sẽ bị dấu và không ai có thể biết
            </div>
          </transition>
        </div>
        <div class="form-group d-flex">
          <button class="ml-auto btn btn-primary">
            Đánh giá
          </button>
        </div>
      </form>
    </transition>
    <transition name="slide-left">
      <div v-show="done" class="text-center" page-section>
        <h3 class="text-success">
          Cảm ơn
        </h3>
        <p>{{ APP_NAME }} chân thành cảm ơn vì đóng góp của bạn</p>
        <div>
          <button class="btn text-primary" @click="$router.go(-1)">
            Trang trước
          </button>
          &nbsp;&nbsp;
          <router-link :to="{name: 'home'}" class="btn btn-primary">
            Trang chủ
          </router-link>
        </div>
      </div>
    </transition>
  </div>
</template>
<script>
import ReviewStars from './Stars'
import ClickOutside from 'vue-click-outside'
export default {
  components: {
    ReviewStars
  },
  data () {
  	return {
      review: {
        description: '',
        anonymous: false,
        title: '',
        rate: 0,
        type: $config.REVIEW.TYPE.RENTER
      },
      note: false,
      done: true,
      APP_NAME: $config.APP_NAME
    }
  },
  watch: {
    "$route.params.id": {
      handler(id) {
        this.id = id
        this.get()
      },
      immediate: true,
      deep: true
    }
  },
  computed: {
    renter () {
      return this.$store.getters['users/find'](this.id)
    }
  },
  methods: {
    getRate (rate) {
      this.review.rate = rate
    },
    store () {
      $eventHub.$emit('on-loading')
      ajax().post(`/api/renter/${this.id}/review`, this.review)
      .then(res => {
        
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: "Đánh giá thành công",
          timeout: 4000
        })
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: "Hiện không thể đánh giá người thuê trọ này, hãy thử lại sau",
          timeout: 5000
        })
      })
    },
    get() {
      $eventHub.$emit('on-loading')
      ajax().get(`/api/user/${this.id}`)
      .then(res => {
        $eventHub.$emit('off-loading')
        this.$store.commit('users/users', [res.data])
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: "Hiện không thể lấy thông tin người dùng, hãy thử lại sau",
          timeout: 5000
        })
      })
    }
  },
  directives: {
    ClickOutside
  }
}
</script>