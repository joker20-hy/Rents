<template>
  <transition name="slide-fade">
  	<tr>
  	  <td class="px-2">
  	  	<div class="holder" v-show="!edit">
  	  	  {{ service.name }}
  	  	</div>
  	  	<input class="form-control" v-show="edit" v-model="service.name"></input>
  	  </td>
  	  <td class="px-2">
  	  	<div class="holder" v-show="!edit">
  	  	  {{ service.price }}
  	  	</div>
  	  	<input class="form-control" v-show="edit" v-model="service.price"></input>
  	  </td>
  	  <td class="px-2">
  	  	<div class="holder" v-show="!edit">
  	  	  {{ type_name(service.type) }}
  	  	</div>
  	  	<select class="form-control" v-show="edit" v-model="service.type">
	  	  <option value="">Loại dịch vụ</option>
	  	  <option :value="service_type.PER_UNIT.value">{{ service_type.PER_UNIT.name }}</option>
	  	  <option :value="service_type.PERIODIC.value">{{ service_type.PERIODIC.name }}</option>
	  	</select>
  	  </td>
  	  <td class="px-2">
  	  	<div>
  	  	  <button class="btn text-primary" @click="enterEdit()" v-show="!edit">edit</button>
  	  	  <button class="btn text-primary" @click="update()" v-show="edit">
  	  	  	<i class="fas fa-spinner fa-pulse text-primary" v-show="updating"></i> Save
  	  	  </button>
  	  	  <button class="btn text-danger" @click="cancel()" v-show="edit">Cancel</button>
  	  	  <button class="btn text-danger" @click="destroy()" v-show="!edit">Delete</button>
  	  	</div>
  	  </td>
  	</tr>
  </transition>
</template>
<script>
import { $auth } from '../../../auth'

export default {
  name: 'list-item',
  props: {
  	service: {
  	  required: true,
  	  type: Object
  	}
  },
  data () {
  	return {
  	  edit: false,
  	  updating: false,
  	  backup_data: {}
  	}
  },
  computed: {
  	service_type () {
  	  return $config.SERVICE_TYPE
  	}
  },
  methods: {
  	type_name (type) {
  	  for(let s in this.service_type) {
  	  	if (this.service_type[s].value == type) return this.service_type[s].name
  	  }
  	},
  	enterEdit () {
  	  this.backup()
  	  this.edit = true
  	},
  	backup () {
  	  this.backup_data = {
  	  	name: this.service.name,
  	  	price: this.service.price,
  	  	type: this.service.type
  	  }
  	},
   	update () {
   	  this.updating = true
   	  $auth.request.put(`/api/service/${this.service.id}`, {
   	  	name: this.service.name,
   	  	price: this.service.price,
   	  	type: this.service.type
   	  })
   	  .then(res => {
   	  	this.edit = false
   	  	this.updating = false
		$eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'Update service successfully',
          timeout: 3000
        })
   	  })
   	  .catch(err => {
   	  	this.cancel()
   	  	this.updating = false
   	  	$eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to update the service, please try again',
          timeout: 3000
        })
   	  })
  	},
  	cancel () {
  	  this.restore()
  	  this.edit = false
  	},
  	restore () {
  	  this.service.name = this.backup_data.name
  	  this.service.price = this.backup_data.price
  	  this.service.type= this.backup_data.type
  	},
  	destroy () {
  	  this.$emit('destroy', this.service)
  	}
  }
}
</script>