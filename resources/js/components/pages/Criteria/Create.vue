<template>
  <transition name="slide-fade">
    <form class="row mx-0 p-3 bg-white rounded mb-2" @submit.prevent="store()">
  	  <div class="d-flex w-100">
  	  	<input class="form-control mw-100" v-model="name" placeholder="Name of criteria">
	  	<button class="btn btn-outline-primary ml-2">Create</button>
	  	<button type="button" class="btn btn-outline-danger ml-2" @click="cancel()">Cancel</button>
  	  </div>
    </form>
  </transition>
</template>
<script>
import { $request } from '../../../auth'
export default {
  name: 'create-criteria',
  data () {
  	return {
  	  name: ''
  	}
  },
  methods: {
  	store () {
  	  $request.post('/api/criteria', {
  	  	name: this.name
  	  })
  	  .then(res => {
  	  	this.name = ''
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
  	  this.$emit('cancel')
  	}
  }
}
</script>