<template>
  <span class="check-box" @click="check()" :checked="isChecked">
    <span class="check">
      <check-icon v-show="isChecked" :width="'12px'" :height="'12px'" class="fill-blue"/>
    </span>&nbsp;
    <span>{{ label }}</span>
  </span>
</template>
<script>
import CheckIcon from '../../icons/Check'
export default {
  name: 'check-box',
  components: {
    CheckIcon
  },
  props: {
    checked: {
      required: false,
      default: false,
      type: Boolean
    },
    index: {
      required: true
    },
    label: {
      required: false,
      default: '',
      type: String
    }
  },
  data () {
    return {
      isChecked: this.checked
    }
  },
  methods: {
    check () {
      this.isChecked=!this.isChecked
      this.$emit('change', {
        index: this.index,
        checked: this.isChecked
      })
    }
  }
}
</script>
<style scoped>
  .check-box {
    display: inline-flex;
    align-items: center;
    cursor: pointer;
  }
  .check-box .check {
    min-height: 18px;
    min-width: 18px;
    max-height: 18px;
    max-width: 18px;
    border: 1px solid;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .check-box[checked="checked"] {
    color: #3490dc;
  }
</style>