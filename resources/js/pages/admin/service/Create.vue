<template>
  <transition name="slide-fade">
	<form class="p-2 my-2 bg-white" @submit.prevent="store()">
	  <div class="form-group">
		<h5>Thêm dịch vụ</h5>
	  </div>
	  <div class="form-group">
		<input type="text" v-model="service.name" class="form-control mw-100" placeholder="Tên dịch vụ" required>
	  </div>
	  <div class="form-group">
		<select v-model="service.type" class="form-control mw-100" required>
		  <option value="">Chọn loại dịch vụ</option>
		  <option v-for="(type, index) in service_types" :key="index" :value="type.value">{{ type.name }}</option>
	  	</select>
	  </div>
	  <div class="form-group" v-show="service.type==service_types.PER_UNIT.value">
		<input type="text" v-model="service.unit" placeholder="Đơn vị VD: số điện">
	  </div>
	  <div class="form-group text-right">
		<button type="button" class="btn text-danger mx-1" @click="cancel()">Cancel</button>
		<button class="btn btn-outline-primary mx-1">Create</button>
	  </div>
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
		unit: ''
  	  }
  	}
  },
  computed: {
  	service_types () {
  	  return $config.SERVICE_TYPE
  	}
  },
  methods: {
  	store () {
  	  $request.post('/api/service', this.service)
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