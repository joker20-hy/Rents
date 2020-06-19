<template>
  <transition name="slide-fade">
  	<form class="row py-2 my-2 ml-0 mr-0 bg-white" @submit.prevent="store()">
	  <div class="col-md-2 px-2">
	  	<select class="form-control" v-model="service.type">
	  	  <option value="">Loại dịch vụ</option>
	  	  <option :value="service_type.PER_UNIT.value">{{ service_type.PER_UNIT.name }}</option>
	  	  <option :value="service_type.PERIODIC.value">{{ service_type.PERIODIC.name }}</option>
	  	</select>
	  </div>
	  <div class="col-md-4 px-2">
	  	<input type="text" v-model="service.name" class="form-control" placeholder="Tên dịch vụ" required>
	  </div>
	  <div class="col-md-4 px-2 d-flex align-items-center">
	  	<input type="number" v-model="service.price" class="form-control mw-100" placeholder="Giá dịch vụ" required>
	  	<span class="pl-3" style="font-weight: 600">vnđ</span>
	  </div>
	  <div class="col-md-1 px-2">
	  	<button class="btn btn-outline-primary w-100 px-2">Create</button>
	  </div>
	  <div class="col-md-1 px-2">
	  	<button type="button" class="btn btn-outline-danger w-100 px-2" @click="reset(),hide()">Cancel</button>
	  </div>
	</form>
  </transition>
</template>
<script>
import { $auth } from '../../../auth'
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
  	hide () {
  	  this.$emit('hide')
  	}
  }
}
</script>