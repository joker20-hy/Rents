<template>
  <modal name="review-detail" class="p-2" :width="600">
    <div class="bg-light p-3">
      <h3 class="text-primary py-2">Review detail</h3>
      <label>Title</label>
      <div class="holder">{{ review.title }}</div>
      <label>Rate</label>
      <div class="holder">{{ review.rate }}</div>
      <label>Description</label>
      <div class="holder" v-html="review.description"></div>
      <div class="holder px-0 d-flex">
        <label>Anonymous</label><switch-input :status="review.anonymous==TRUE" class="ml-auto"/>
      </div>
      <div class="row">
        <div class="col-6">
          <label>Likes</label>
          <div class="holder">{{ review.like }}</div>
        </div>
        <div class="col-6">
          <label>Report</label>
          <div class="holder">{{ review.report }}</div>
        </div>
      </div>
    </div>
  </modal>
</template>
<script>
import SwitchInput from '../../utilities/SwitchInput'
export default {
  name: 'review-detail',
  components: {
    SwitchInput
  },
  computed: {
    TRUE () {
      return $config.TRUE
    },
    FALSE () {
      return $config.FALSE
    }
  },
  data () {
    return {
      edit: false,
      review: {}
    }
  },
  mounted () {
    $eventHub.$on('show-review-detail', this.show)
  },
  methods: {
    show (review) {
      this.review = review,
      this.$modal.show('review-detail')
    }
  }
}
</script>
<style scoped>
  label {
    font-weight: 600;
  }
</style>