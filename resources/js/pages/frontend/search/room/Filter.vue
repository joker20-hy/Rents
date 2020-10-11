<template>
  <modal name="room-filter" :height="'auto'" :width="400">
    <div class="py-3 px-2">
      <h4>Bộ lọc</h4>
      <div>
        <div class="py-2">
          <strong>Giá</strong>
        </div>
      	<select class="form-control" v-model="price">
      	  <option value="">Giá</option>
      	  <option v-for="(condition, index) in conditions.price" :key="index" :value="index">{{ condition }}</option>
      	</select>
      </div>
      <div>
        <div class="py-2">
          <strong>Diện tích</strong>
        </div>
        <select class="form-control" v-model="acreage">
          <option value="">Diện tích</option>
          <option v-for="(condition, index) in conditions.acreage" :key="index" :value="index">{{ condition }}</option>
        </select>
      </div>
      <div class="pt-3 pb-2">
      	<strong>Cơ sở vật chất</strong>
      </div>
      <div class="row mx-0">
        <div class="col-4 px-1" v-for="(criterias, index) in conditions.criterias" :key="criterias.id">
          <check-box :index="index" :label="criterias.name" @change="check" :checked="criterias.checked"/>
        </div>
      </div>
      <div class="d-flex">
      	<button class="btn btn-outline-primary ml-auto" @click="apply()">Áp dụng</button>
      </div>
    </div>
  </modal>
</template>
<script>
import CheckBox from '../../../utilities/CheckBox'
export default {
  name: 'room-filter',
  components: {
    CheckBox
  },
  watch: {
    "$route.query.acreage": {
      handler (acreage) {
        this.acreage = acreage==undefined?'':acreage
      },
      deep: true,
      immediate: true
    },
    "$route.query.price": {
      handler (price) {
        this.price = price==undefined?"":price
      },
      deep: true,
      immediate: true
    },
    "$route.query.criterias": {
      handler (criterias) {
        this.criterias = criterias==undefined?[]:criterias.split(',')
      },
      deep: true,
      immediate: true
    }
  },
  data () {
    return {
      acreage: '',
      price: '',
      criterias: [],
      conditions: {
        acreage: $config.ROOM.FILTER.acreage,
        price: $config.ROOM.FILTER.price,
        criterias: []
      }
    }
  },
  mounted () {
    this.getcriterias()
  },
  methods: {
  	check (target) {
  	  this.conditions.criterias[target.index].checked = target.checked
    },
    getcriterias () {
      ajax().get(`/api/criteria`)
      .then(res => {
        res.data.forEach(criterias => {
          criterias.checked=this.criterias.includes(`${criterias.id}`)
        })
        this.conditions.criterias = res.data
      })
      .catch(err => console.log(err.response.data))
    },
  	apply () {
      let criterias = []
      this.conditions.criterias.filter(cri => {
        if (cri.checked) criterias.push(cri.id)
      })
      this.$emit('apply', {
        price: `${this.price}`,
        acreage: `${this.acreage}`,
        criterias: criterias.join(',')
      })
      this.$modal.hide('room-filter')
  	}
  }
}
</script>