<template>
  <transition name="slide-fade">
    <form class="row mx-0 my-4 justify-content-center" @submit.prevent="store">
      <div class="col-12 py-2 d-flex bg-white rounded">
        <input type="text" class="text-left input" v-model="name" placeholder="Province name">
        <button class="btn text-primary">Create</button>
        <button type="button" class="btn text-danger" @click="cancel()">Cancel</button>
      </div>
    </form>
  </transition>
</template>
<script>
export default {
  name: 'province-create',
  data () {
    return {
      name: ''
    }
  },
  methods: {
    reset () {
      this.name = ''
    },
    store () {
      ajax().post('/api/province', {
        name: this.name
      })
      .then(res => {
        this.reset()
        this.$emit('created', res.data)
      })
      .catch(err => {
        this.$emit('Unable to create new province')
      })
    },
    cancel () {
      this.reset()
      this.$emit('cancel')
    }
  }
}
</script>