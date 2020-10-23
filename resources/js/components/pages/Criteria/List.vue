<template>
  <div class="col-md-8 col-lg-6 mx-auto ">
  	<h3 class="mt-5 text-primary d-flex">
  	  Criteria list
  	  <button class="ml-auto btn text-primary" v-show="!create" @click="create=true">
  	  	<i class="fas fa-plus"></i> Create
  	  </button>
  	</h3>
  	<create-criteria v-show="create" @created="created" @cancel="create=false"/>
  	<table class="records-list">
  	  <thead>
  	  	<th>name</th>
  	  	<th>Actions</th>
  	  </thead>
  	  <tbody>
  	  	<list-item v-for="criteria in criterias" :key="criteria.id" :criteria="criteria" @destroy="destroyConfirm"/>
  	  </tbody>
  	</table>
  	<paginate
  	  v-model="page"
  	  :page-count="page_count"
  	  :page-range="3"
  	  :margin-pages="2"
  	  :click-handler="changePage"
  	  :prev-text="'Prev'"
  	  :next-text="'Next'"
  	  :container-class="'pagination'"
  	  :page-class="'page-item'">
  	</paginate>
  	<confirm-box :name="'delete-criteria'" :title="'Delete criteria'" :message="'Criteria gonna be deleted'" @confirm="destroy()"/>
  </div>
</template>
<script>
import { $request } from '../../../auth'
import ConfirmBox from '../../utilities/ConfirmBox'
import CreateCriteria from './Create'
import ListItem from './ListItem'
export default {
  name: 'criteria-list',
  components: {
  	ConfirmBox,
  	CreateCriteria,
  	ListItem
  },
  data () {
  	return {
  	  create: false,
  	  page: 1,
  	  per_page: 1,
  	  page_count: 1,
  	  chosen: {}
  	}
  },
  computed: {
  	criterias () {
  	  return this.$store.getters['criterias/criterias']
  	}
  },
  mounted () {
  	this.list()
  },
  methods: {
  	list () {
  	  ajax().get(`/api/criteria?page${this.page}`)
  	  .then(res => {
  	  	this.page_count = res.data.last_page
  	  	this.per_page = res.data.per_page
  	  	this.$store.commit('criterias/criterias', res.data.data)
  	  })
  	  .catch(err => {
  	  	$eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to get criteria list',
          timeout: 3000
        })
  	  })
  	},
  	created (criteria) {
  	  if (this.criterias.length < this.per_page) {
  	  	this.$store.commit('criterias/add', criteria)
  	  } else {
  	  	this.page = this.page + 1
  	  	this.list()
  	  }
  	},
  	changePage (page) {
  	  this.page = page
  	  this.list()
  	},
  	destroyConfirm (criteria) {
  	  this.chosen = criteria
  	  this.$modal.show('delete-criteria')
  	},
  	destroy () {
  	  ajax().delete(`/api/criteria/${this.chosen.id}`)
  	  .then(res => {
  	  	$eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'The criteria has been delete successfully',
          timeout: 3000
        })
  	  })
  	  .catch(err => {
  	  	$eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to delete criteria',
          timeout: 3000
        })
  	  })
  	}
  }
}
</script>