<template>
  <transition name="slide-fade">
    <div class="row mx-0">
      <div class="col-md-4" v-for="(cri, index) in criterias" :key="cri.id">
        <check-box :label="cri.name" :checked="cri.checked" :index="index" @change="getCriteria"/>
      </div>
    </div>
  </transition>
</template>
<script>
import CheckBox from '../../utilities/CheckBox'
export default {
  components: {
    CheckBox
  },
  name: 'criteria-list',
  props: {
    criterias: {
      required: true,
      type: Array
    }
  },
  data () {
    return {}
  },
  methods: {
    getCriteria (criteria) {
      this.criterias[criteria.index].checked = criteria.checked
    }
  }
}
</script>