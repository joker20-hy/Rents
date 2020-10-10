<template>
  <transition name="slide-fade">
	<tr>
	  <td>
	  	<div class="holder" v-show="!edit">{{ criteria.name }}</div>
	  	<input class="input text-center" v-model="criteria.name" v-show="edit">
	  </td>
	  <td>
		<div class="holder" v-html="criteria.icon" v-show="!edit"></div>
		<textarea class="input" v-model="criteria.icon" v-show="edit"></textarea>
	  </td>
	  <td>
	  	<button class="btn text-primary" @click="enterEdit()" v-show="!edit">
		  <i class="fas fa-pencil"></i>
		</button>
	  	<button class="btn text-primary" @click="update()" v-show="edit">
		  <i class="fas fa-save text-primary" v-show="!updating"></i>
	  	  <i class="fas fa-spinner fa-pulse text-primary" v-show="updating"></i>
	  	</button>
	  	<button class="btn text-danger" @click="leaveEdit()" v-show="edit">
		  <i class="fas fa-times"></i>
		</button>
	  	<button class="btn text-danger" @click="$emit('destroy', criteria)" v-show="!edit">
		  <i class="fas fa-trash"></i>
		</button>
	  </td>
	</tr>
  </transition>
</template>
<script>
export default {
  name: 'list-item',
  props: {
  	criteria: {
  	  required: true,
  	  type: Object
  	}
  },
  data () {
    return {
      edit: false,
      backup_data: {},
      updating: false
    }
  },
  methods: {
  	enterEdit () {
  	  this.backup()
  	  this.edit = true
  	},
  	backup () {
		this.backup_data.name = this.criteria.name
		this.backup_data.icon = this.criteria.icon
  	},
  	cancel () {
  	  this.restore()
  	  this.leaveEdit()
  	},
  	restore () {
		this.criteria.name = this.backup_data.name,
		this.criteria.icon = this.backup_data.icon
  	},
  	leaveEdit () {
  	  this.edit = false
  	},
  	update () {
  	  this.updating = true
  	  ajax().put(`/api/criteria/${this.criteria.id}`, {
		name: this.criteria.name,
		icon: this.criteria.icon
  	  })
  	  .then(res => {
  	  	this.updating = false
  	  	this.leaveEdit()
  	  	$eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'New criteria has been created',
          timeout: 3000
        })
  	  })
  	  .catch(err => {
  	  	this.updating = false
  	  	this.cancel()
  	  	$eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to create new criteria, please try again',
          timeout: 3000
        })
  	  })
  	}
  }
}
</script>
<style scoped>
  td {
  	padding: 10px;
  	text-align: center;
  }
</style>