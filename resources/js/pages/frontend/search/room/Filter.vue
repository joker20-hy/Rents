<template>
  <modal name="room-filter" :height="'auto'" :width="400">
    <div class="py-3 px-2">
      <h4>Bộ lọc</h4>
      <div>
        <div class="py-2">
          <strong>Giá</strong>
        </div>
      	<select class="form-control" v-model="chosen_price">
      	  <option value="">Giá</option>
      	  <option v-for="(condition, index) in price_conditions" :key="index" :value="index">{{ condition }}</option>
      	</select>
      </div>
      <div>
        <div class="py-2">
          <strong>Giá</strong>
        </div>
        <select class="form-control" v-model="chosen_acreage">
          <option value="">Diện tích</option>
          <option v-for="(condition, index) in acreage_conditions" :key="index" :value="index">{{ condition }}</option>
        </select>
      </div>

      <div class="pt-3 pb-2">
      	<strong>Cơ sở vật chất</strong>
      </div>
      <div class="row mx-0">
        <div class="col-4 px-1" v-for="(infras, index) in infras_conditions" :key="infras.id">
          <check-box :index="index" :label="infras.name" @change="check" :checked="infras.checked"/>
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
import serialize from '../../../../utilities/serialize'
export default {
  name: 'room-filter',
  props: {
    acreage: {
      required: true,
      type: Array
    },
    price: {
      required: true,
      type: Array
    },
    infras: {
      required: true,
      type: Array
    },
    filled: {
      required: true,
      type: Object
    }
  },
  components: {
    CheckBox
  },
  watch: {
    filled (val) {
      this.chosen_price = val.price
    },
    price (price) {
      this.price_conditions = price
    },
    infras (infras) {
      this.infras_conditions = infras
    }
  },
  data () {
    return {
      chosen_acreage: this.filled.acreage,
      chosen_price: this.filled.price,
      acreage_conditions: this.acreage,
      price_conditions: this.price,
      infras_conditions: this.infras
    }
  },
  methods: {
  	check (target) {
  	  this.infras_conditions[target.index].checked = target.checked
  	},
  	apply () {
      this.$emit('apply', {
  	  	price: {
          index: this.chosen_price,
          name: this.price_conditions[this.chosen_price]
        },
        acreage: {
          index: this.chosen_acreage,
          name: this.acreage_conditions[this.chosen_acreage]
        },
  	  	infras: this.infras_conditions.filter(infras => infras.checked==true)
      })
      this.$modal.hide('room-filter')
  	}
  }
}
</script>