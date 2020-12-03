<template>
  <transition name="slide-fade">
    <div>
      <div v-if="editable" class="row mx-0">
        <div class="col-md-6" v-for="(cri, index) in criterias" :key="cri.id">
          <check-box :label="cri.name" :checked="cri.checked" :index="index" @change="getCriteria"/>
        </div>
      </div>
      <div v-else class="row mx-0">
        <div class="col-md-6" v-for="cri in chosens" :key="cri.id">{{ cri.name }}</div>
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
    },
    editable: {
      required: false,
      default: true,
      type: Boolean
    }
  },
  data () {
    return {}
  },
  computed: {
    chosens() {
      return this.criterias.filter(item => item.checked)
    }
  },
  methods: {
    getCriteria (criteria) {
      this.criterias[criteria.index].checked = criteria.checked
    }
  }
}
</script>