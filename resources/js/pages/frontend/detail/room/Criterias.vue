<template>
  <div class="row mx-0">
    <div class="criterias col-4 col-md-3 col-xl-2" v-for="criteria in criterias" :key="criteria.id">
      <span v-html="criteria.icon"></span> {{ criteria.name }}
    </div>
  </div>
</template>
<script>
export default {
  props: {
    criterias: {
      required: true,
      type: Array
    }
  },
  name: 'room-criterias',
  data () {
    return {}
  }
}
</script>
<style scoped>
  .criterias svg {
    width: 30px;
    height: 30px;
  }
</style>