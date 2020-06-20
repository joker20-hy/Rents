<template>
  <div class="container">
  	<h3 class="d-flex align-items-end mt-5 py-3 text-primary">
      Service list
      <button class="btn ml-auto text-primary" v-show="!create" style="font-weight: 600" @click="create=true">
        <i class="fas fa-plus"></i> Create
      </button>
    </h3>
    <create-service v-show="create" @created="created" @cancel="create=false"/>
    <table class="records-list">
      <thead>
      	<th>Name</th>
      	<th>Price</th>
      	<th>Type</th>
      	<th>Actions</th>
      </thead>
      <tbody>
      	<list-item v-for="service in services" :key="service.id" :service="service" @destroy="destroyConfirm"/>
      </tbody>
    </table>
    <div class="py-4 text-center mx-auto" v-show="loading">
      <span class="p-2 bg-light rounded-circle">
        <i class="fas fa-spinner fa-pulse fa-lg text-primary"></i>
      </span>
    </div>
    <paginate
  	  v-model="query.page"
  	  :page-count="page_count"
  	  :page-range="3"
  	  :margin-pages="2"
  	  :click-handler="changePage"
  	  :prev-text="'Prev'"
  	  :next-text="'Next'"
  	  :container-class="'pagination'"
  	  :page-class="'page-item'">
  	</paginate>
	<confirm-box :name="'delete-service'" :title="'Delete service'" :message="'Service gonna be deleted'" @confirm="destroy()"/>
  </div>
</template>
<script>
import { $auth } from '../../../auth'
import serialize from '../../../utilities/serialize'
import ListItem from './ListItem'
import CreateService from './Create'
import ConfirmBox from '../../utilities/ConfirmBox'

export default {
  name: 'service-list',
  components: {
  	CreateService,
  	ListItem,
  	ConfirmBox
  },
  data () {
  	return {
  	  loading: false,
  	  create: false,
  	  query: {
  	  	page: 1
  	  },
  	  page_count: 1,
  	  per_page: 1
  	}
  },
  created () {
  	this.list()
  },
  computed: {
  	services () {
  	  return this.$store.getters['services/services']
  	},
  	listApi () {
  	  return `/api/service?${serialize.fromObj(this.query.page)}`
  	}
  },
  methods: {
  	list () {
  	  this.loading = true
  	  $auth.request.get(this.listApi)
  	  .then(res => {
  	  	this.loading = false
  	  	this.page_count = res.data.last_page
  	  	this.per_page = res.data.per_page
  	  	this.$store.commit('services/services', res.data.data)
  	  })
  	  .catch(err => {
  	  	this.loading = false
  	  	$eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to get service list',
          timeout: 3000
        })
  	  })
  	},
  	changePage (page) {
  	  this.query.page = page
  	  this.list()
  	},
  	created (service) {
  	  if (this.services.length<this.per_page) {
  	  	this.$store.commit('services/add', service)
  	  } else {
  	  	this.query.page=this.query.page+1
  	  	this.list()
  	  }
  	},
  	destroyConfirm (service) {
  	  this.chosen = service
  	  this.$modal.show('delete-service')
  	},
  	destroy () {
  	  $auth.request.delete(`/api/service/${this.chosen.id}`)
  	  .then(res => {
  	  	this.$modal.hide('delete-service')
  	  	this.$store.commit('services/remove', this.chosen.id)
  	  	$eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'The service has been deleted successfully',
          timeout: 3000
        })
  	  })
  	  .catch(err => {
  	  	this.$modal.hide('delete-service')
  	  	$eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to delete the service',
          timeout: 3000
        })
  	  })
  	}
  }
}
</script>