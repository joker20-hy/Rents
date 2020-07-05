<template>
  <transition name="slide-fade">
  	<tr>
  	  <td class="px-2">
  	  	<div class="holder" v-show="!edit">
  	  	  {{ service.name }}
  	  	</div>
  	  	<input class="form-control" v-show="edit" v-model="service.name">
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
import { $auth } from '../../../utilities/request/request'
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
  methods: {
  	enterEdit () {
  	  this.backup()
  	  this.edit = true
  	},
  	backup () {
  	  this.backup_data = {
  	  	name: this.service.name
  	  }
  	},
   	update () {
   	  this.updating = true
   	  $auth.request.put(`/api/service/${this.service.id}`, {
   	  	name: this.service.name
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