<template>
  <div class="container">
  	<h3 class="pt-5 pb-2 mb-0 text-primary">
      Review list
    </h3>
    <div class="d-flex py-3">
      <button class="type-btn mr-2" :class="type==review_type.RENTER?'active':''" @click="changeReceiverType(review_type.RENTER)">
        renters
      </button>
      <button class="type-btn mr-2" :class="type==review_type.HOUSE?'active':''" @click="changeReceiverType(review_type.HOUSE)">
        houses
      </button>
      <button class="type-btn mr-2" :class="type==review_type.ROOM?'active':''" @click="changeReceiverType(review_type.ROOM)">
        rooms
      </button>
    </div>
    <table class="records-list">
      <thead>
      	<th>Reviewer</th>
        <th>Rate</th>
      	<th>Receiver</th>
        <th>Anonymous</th>
      	<th>Actions</th>
      </thead>
      <tbody>
        <list-item v-for="review in reviews" :key="review.id" :review="review" @destroy="showDestroy"/>
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
    <confirm-box :name="'delete-review'" :title="'Delete review'" :message="'Review gonna be deleted'" @confirm="destroy()"/>
    <review-detail/>
  </div>
</template>
<script>
import serialize from '../../../utilities/serialize'
import ConfirmBox from '../../utilities/ConfirmBox'
import ReviewDetail from './Detail'
import ListItem from './ListItem'
export default {
  name: 'review-list',
  components: {
    ReviewDetail,
    ConfirmBox,
    ListItem,
  },
  data () {
  	return {
      type: $config.REVIEW.TYPE.RENTER,
  	  loading: false,
  	  create: false,
  	  query: {
  	  	page: 1
  	  },
  	  page_count: 1,
      per_page: 1,
      chosen: ''
  	}
  },
  created () {
  	this.list()
  },
  computed: {
  	reviews () {
  	  return this.$store.getters['reviews/reviews']
  	},
  	listApi () {
  	  return `/api/review/${this.type}?${serialize.fromObj(this.query.page)}`
    },
    review_type () {
      return $config.REVIEW.TYPE
    }
  },
  methods: {
    changeReceiverType (type) {
      this.type = type
      this.list()
    },
  	list () {
  	  this.loading = true
  	  $request.get(this.listApi)
  	  .then(res => {
  	  	this.loading = false
        this.per_page = res.data.per_page
        this.page_count = res.data.last_page
  	  	this.$store.commit('reviews/reviews', res.data.data)
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
    showDestroy (review) {
      this.chosen = review
      this.$modal.show('delete-review')
    },
    destroy () {
      $request.delete(`/api/review/${this.chosen.id}`)
      .then(res => {
        this.$modal.hide('delete-review')
        this.success('The review has been deleted successfully')
        this.$store.commit('reviews/remove', this.chosen.id)
      })
      .catch(err => {
        this.error('Unable to delete the review')
      })
    },
    success (message) {
      $eventHub.$emit('success-alert', {
        title: 'Success',
        message: message,
        timeout: 3000
      })
    },
    error (message) {
      $eventHub.$emit('error-alert', {
        title: 'Error',
        message: message
      })
    }
  }
}
</script>
<style scoped>
  .type-btn {
    border: none;
    outline: none;
    padding: 6px 15px;
    font-weight: 600;
    background-color: #fff;
    border-radius: 16px;
  }
  .type-btn.active {
    background-color: var(--blue);
    color: #fff;
  }
</style>