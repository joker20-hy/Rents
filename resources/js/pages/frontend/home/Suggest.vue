<template>
  <div v-show="show" suggests>
    <div class="blank">
      <span class="triangle"></span>
    </div>
    <div class="option-list">
      <div class="option text-center text-muted" v-show="options.length==0">Hiện không có kết quả</div>
      <div class="option" v-for="(option, index) in options" :key="`${option.id}@${index}`" @click="$emit('choose', option)">{{ option.name }}</div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'suggest-box',
  props: {
    show: {
      required: false,
      default: false,
      type: Boolean
    },
    options: {
      required: true,
      type: Array
    }
  },
  data () {
    return {}
  },
  methods: {
    // 
  }
}
</script>