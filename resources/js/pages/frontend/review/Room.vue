<template>
  <div review-contain v-if="room!=null" class="mb-5">
	<transition name="slide-fade">
  	<form v-show="!done" review-form @submit.prevent="create">
	  <div class="pb-4">
		<router-link :to="{name: 'room-for-rent', params: {id: room.id}}">
		  <h2>{{ room.name }}</h2>
		</router-link>
		<small>{{ room.house.address_detail }}</small>
	  </div>
	  <!-- <label>Tiêu đề</label>
  	  <div class="form-group">
		<input type="text" class="form-control" v-model="title" placeholder="Tiêu đề">
	  </div> -->
  	  <label>Chủ trọ</label>
  	  <review-stars class="form-group" :count="10" @change="ownerRate" :rate="owner_rate"/>
  	  <label>An ninh</label>
  	  <review-stars class="form-group" :count="10" @change="secureRate" :rate="secure_rate"/>
	  <label>Cơ sở vật chất</label>
  	  <review-stars class="form-group" :count="10" @change="getRate" :rate="infra_rate"/>
  	  <label>Nhận xét</label>
  	  <div class="form-group">
  		<textarea class="form-control" placeholder="Nhận xét bổ sung" v-model="description"></textarea>
  	  </div>
  	  <div class="form-group d-flex align-items-center justify-content-end" style="font-weight: 600;flex-wrap: wrap;position:relative" v-click-outside="function (){note=false}">
  	  	<input type="checkbox" v-model="anonymous" :checked="anonymous">
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
          <router-link :to="{name: 'room-for-rent', params: {id: id}}" class="btn btn-primary">
            Chi tiết phòng
          </router-link>
        </div>
      </div>
    </transition>
  </div>
</template>
<script>
import ReviewStars from './Stars'
import SwitchBox from '../../utilities/SwitchBox'
import ClickOutside from 'vue-click-outside'

export default {
  components: {
	  ReviewStars,
	  SwitchBox
  },
  data () {
  	return {
	  type: 0,
	  id: '',
	  title: '',
	  owner_rate: '',
	  secure_rate: '',
	  infra_rate: '',
	  anonymous: 0,
	  description: '',
	  note: false,
	  done: false,
	  APP_NAME: $config.APP_NAME
  	}
  },
  watch: {
	"$route.params.id": {
	  handler (id) {
		this.id = id
		this.getRoom()
	  },
	  deep: true,
      immediate: true
	}
  },
  computed: {
	room () {
	  return this.$store.getters['rooms/first']
	}
  },
  created () {
	this.type = $config.REVIEW.TYPE.ROOM
  },
  methods: {
	getRoom () {
	  ajax().get(`/api/room/${this.id}`)
	  .then(res => {
		this.$store.commit('rooms/rooms', [res.data])
	  })
	  .catch(err => {
		console.log(err)
	  })
	},
	ownerRate (rate) {
	  this.owner_rate = rate
	},
	secureRate (rate) {
	  this.secure_rate = rate
	},
	getRate (rate) {
	  this.infra_rate = rate
	},
	create () {
	  $eventHub.$emit('on-loading')
	  ajax().post(`/api/room/${this.id}/review`, {
		title: this.title,
		owner_rate: this.owner_rate,
		secure_rate: this.secure_rate,
		infra_rate: this.infra_rate,
		anonymous: this.anonymous,
		description: this.description
	  })
	  .then(res => {
		$eventHub.$emit('off-loading')
		this.done = true
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
          message: "Hiện không thể đánh giá phòng trọ này, hãy thử lại sau",
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
<style scoped>
label {
  font-weight: 600;
  color: var(--dark);
}
</style>