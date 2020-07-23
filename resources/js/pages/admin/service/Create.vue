<template>
  <transition name="slide-fade">
	<form class="p-2 my-2 bg-white" style="display: flex" @submit.prevent="store()">
	  <input type="text" v-model="service.name" class="form-control mw-100" placeholder="Tên dịch vụ" required>
	  <button class="btn btn-outline-primary mx-1">Create</button>
	  <button type="button" class="btn btn-outline-danger mx-1" @click="cancel()">Cancel</button>
	</form>
  </transition>
</template>
<script>
export default {
  name: 'create-service',
  data () {
  	return {
  	  service: {
  	  	type: '',
  	  	name: '',
  	  	price: ''
  	  }
  	}
  },
  computed: {
  	service_type () {
  	  return $config.SERVICE_TYPE
  	}
  },
  methods: {
  	store () {
  	  $auth.request.post('/api/service', {
  	  	name: this.service.name,
  	  	price: this.service.price,
  	  	type: this.service.type
  	  })
  	  .then(res => {
  	  	this.reset()
  	  	this.$emit('created', res.data)
  	  	$eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'New service has been created',
          timeout: 3000
        })
  	  })
  	  .catch(err => {
  	  	$eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to create new service, please try again',
          timeout: 3000
        })
  	  })
  	},
  	reset () {
  	  this.service = {
  	  	type: '',
  	  	name: '',
  	  	price: ''
  	  }
  	},
  	cancel () {
      this.reset()
  	  this.$emit('cancel')
  	}
  }
}
</script>