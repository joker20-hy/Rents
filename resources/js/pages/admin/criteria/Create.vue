<template>
  <transition name="slide-fade">
    <form class="p-3 bg-white rounded mb-2" @submit.prevent="store()">
	  <div class="form-group">
		<input class="form-control" v-model="name" placeholder="Tên của tiêu chí">
	  </div>
	  <div class="form-group">
		<textarea class="form-control" v-model="icon" placeholder="svg icon"></textarea>
	  </div>
  	  <div class="text-right">
		<button type="button" class="btn btn-outline-danger ml-2" @click="cancel()">Cancel</button>
	  	<button class="btn btn-outline-primary ml-2">Create</button>
  	  </div>
    </form>
  </transition>
</template>
<script>
export default {
  name: 'create-criteria',
  data () {
  	return {
	  name: '',
	  icon: ''
  	}
  },
  methods: {
  	store () {
  	  ajax().post('/api/criteria', {
		name: this.name,
		icon: this.icon
  	  })
  	  .then(res => {
		this.name = ''
		this.icon = ''
  	  	this.$emit('created', res.data)
  	  	$eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'New criteria has been created',
          timeout: 3000
        })
  	  })
  	  .catch(err => {
  	  	$eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to create new criteria, please try again',
          timeout: 3000
        })
  	  })
  	},
  	cancel () {
	  this.name = ''
	  this.icon = ''
  	  this.$emit('cancel')
  	}
  }
}
</script>