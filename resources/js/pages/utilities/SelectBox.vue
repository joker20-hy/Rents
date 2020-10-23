<template>
  <div class="select" v-click-outside="hide">
    <div class="selected" @click="show=!show">
      {{ selected.name }} <i class="fas fa-chevron-down ml-auto" style="transition: .2s" :style="show?'transform: rotate(-180deg)':'transform: rotate(-0deg)'"></i>
    </div>
    <transition name="slide-fade">
    <div class="option-list" style="top: 50px" v-show="show">
      <div class="option" @click="reset()">{{ initial.name }}</div>
      <div class="option" v-for="option in options" :key="option.id" @click="select(option)">{{ option.name }}</div>
    </div>
    </transition>
  </div>
</template>
<script>
import ClickOutside from 'vue-click-outside'
export default {
  name: 'select-box',
  props: {
    options: {
      required: true,
      type: Array
    },
    default: {
      required: true,
      type: Object
    }
  },
  data () {
    return {
      show: false,
      initial: this.default,
      selected: this.default
    }
  },
  methods: {
    hide () {
      this.show = false
    },
    reset () {
      this.selected = this.default
      this.hide()
    },
    select (option) {
      this.selected = option
      this.hide()
      this.$emit('choose', this.selected)
    }
  },
  directives: {
    ClickOutside
  }
}
</script>