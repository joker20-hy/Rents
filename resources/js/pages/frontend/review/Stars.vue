<template>
  <div review-stars>
  	<i
  	v-for="(item, index) in stars"
  	:key="index"
  	class="fa-star"
  	:class="item.active?'fas active':'far'"
  	@mouseover="hoverStar(index)"
  	@mouseleave="leaveStar(item)"
  	@click="choose(item)"
  	></i>
  </div>
</template>
<script>
export default {
  name: 'review-stars',
  props: {
  	count: {
  	  required: true,
  	  type: Number
	},
	rate: {
	  required: false,
	  default: ''
	}
  },
  data () {
  	return {
  	  value: '',
  	  stars: []
  	}
  },
  mounted () {
  	this.init()
  },
  methods: {
  	init () {
  	  for (let i = 0; i < this.count; i++) {
  	  	this.stars.push({
  	  	  id: i,
  	  	  active: (i<this.rate&&this.rate!=null)?true:false
  	  	})
  	  }
  	},
  	hoverStar (position) {
	  this.stars.forEach((star, index) => {
		star.active = index<=position
	  })
  	},
  	leaveStar (position) {
  	  this.stars.forEach((star, index) => {
		star.active = index<=position||index<=this.value
	  })
  	},
  	choose (star) {
  	  this.value = star.id
  	  this.stars.forEach((star, i) => {
  	  	if (i <= this.value) star.active=true
  	  	else star.active = false
  	  })
  	  this.$emit('change', this.value+1)
  	}
  }
}
</script>
<style scoped>
[review-stars] i {
  padding: 0px 5px;
}
[review-stars] i.active {
  color: #f6993f;
}
</style>