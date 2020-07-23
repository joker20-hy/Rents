<template>
  <div review-contain>
  	<form class="py-3 px-2" review-form>
  	  <label>Chủ trọ</label>
  	  <review-stars class="form-group" :count="10" @change="getRate"/>
  	  <label>An ninh</label>
  	  <review-stars class="form-group" :count="10" @change="getRate"/>
	  <label>Cơ sở vật chất</label>
  	  <review-stars class="form-group" :count="10" @change="getRate"/>
  	  <label>Nhận xét</label>
  	  <div class="form-group">
  		<textarea class="form-control" placeholder="Nhận xét bổ xung"></textarea>
  	  </div>
  	  <div class="form-group">
  	  	<input type="checkbox" name=""> Đánh giá ẩn danh
  	  </div>
  	  <div class="form-group">
  	  	<button class="btn btn-primary">
  	  	  Đánh giá
  	  	</button>
  	  </div>
  	</form>
  </div>
</template>
<script>
import ReviewStars from './Stars'
export default {
  components: {
  	ReviewStars
  },
  data () {
  	return {
  	  owners: []
  	}
  },
  methods: {
  	hoverStar (e) {
  	  e.target.classList.add('text-warning')
  	},
  	leaveStar (e) {
  	  e.target.classList.remove('text-warning')
	},
	getRate (rate) {
	  console.log(rate)
	}  
  }
}
</script>
<style scoped>
label {
  font-weight: 600
}
</style>