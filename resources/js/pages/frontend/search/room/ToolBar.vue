<template>
  <div class="d-flex bg-white rounded p-2">
    <button class="btn c-flex-middle" @click="$modal.show('room-filter')">
      <equalizer-icon :width="'18px'" :height="'18px'"/>
    </button>
    <select class="ml-auto" v-model="sort" @change="onSort()">
      <option value="">Sắp xếp</option>
      <option v-for="(condition, index) in sort_conditions" :value="index" :key="index">{{ condition }}</option>
    </select>
  </div>
</template>
<script>
import EqualizerIcon from '../../../../icons/Equalizer'
export default {
  name: 'tool-bar',
  components: {
    EqualizerIcon
  },
  computed: {
    sort_conditions () {
      return $config.ROOM.SORT
    }
  },
  watch: {
    "$route.query.sort":  {
      handler (sort) {
        this.sort = sort==undefined?"":sort
      },
      deep: true,
      immediate: true
    }
  },
  data () {
    return {
      sort: ''
    }
  },
  methods: {
    onSort () {
      this.$emit('sort', this.sort)
    }
  }
}
</script>
<style scoped>
select {
  border: none;
  outline: none!important;
  color: #343a40;
  border-bottom: 2px solid #6c757d;
  background-color: transparent;
  padding: 0px 10px;
}
</style>