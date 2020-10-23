<template>
  <div class="row mx-0">
    <div class="criterias col-4 col-md-3 col-xl-2" v-for="criteria in criterias" :key="criteria.id">
      <span v-html="criteria.icon"></span> {{ criteria.name }}
    </div>
  </div>
</template>
<script>
export default {
  name: 'room-criterias',
  props: {
    list: {
      required: true,
      type: Array
    }
  },
  data () {
    return {
      criterias: this.list
    }
  }
}
</script>
<style scoped>
  .criterias svg {
    width: 30px;
    height: 30px;
  }
</style>