<template>
  <div review-contain>
    <form review-form @submit.prevent="store()">
      <label>Tiêu đề</label>
  	  <div class="form-group">
        <input type="text" class="form-control" v-model="title" placeholder="Tiêu đề">
      </div>
      <label>Đánh giá</label>
  	  <review-stars class="form-group" :count="10" @change="ownerRate" :rate="rate"/>
      <div class="form-group">
  		<textarea class="form-control" placeholder="Nhận xét bổ xung" v-model="description"></textarea>
  	  </div>
  	  <div class="form-group d-flex align-items-center justify-content-end">
  	  	<input type="checkbox" v-model="anonymous" :checked="anonymous">&nbsp;Đánh giá ẩn danh
  	  </div>
  	  <div class="form-group d-flex">
  	  	<button class="ml-auto btn btn-primary">
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
      title: '',
      rate: 0,
      description,
      anonymous
    }
  },
  methods: {
    ownerRate (rate) {
      this.rate = rate
    },
    store () {
      //
    }
  }
}
</script>