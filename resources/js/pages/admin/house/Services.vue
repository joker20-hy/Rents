<template>
  <div class="row">
    <div class="col-md-6" v-for="(service, index) in services" :key="service.id" v-show="service.checked||editable">
      <check-box :label="service.name" :index="index" :checked="service.checked" @change="chooseService" v-show="editable"/>
      <transition name="slide-fade">
        <div v-show="service.checked&&editable" class="form-group">
          <label class="mb-0">Giá</label>
          <input type="number" class="input" v-model="service.price" placeholder="vnđ">
        </div>
      </transition>
      <div class="holder px-1" v-show="!editable&&service.checked">
        {{ service.name }}: {{ service.price }}vnđ
      </div>
    </div>
  </div>
</template>
<script>
import CheckBox from '../../utilities/CheckBox'
export default {
  name: 'choose-service',
  components: {
    CheckBox
  },
  props: {
    list: {
      required: true,
      type: Array
    },
    chosens: {
      required: false,
      default: () => [],
      type: Array
    },
    editable: {
      required: false,
      default: true,
      type: Boolean
    }
  },
  watch: {
    list (data) {
      this.services = data
      this.init()
    },
    chosens (data) {
      this.update = data.length>0
      this.init()
    }
  },
  data () {
    return {
      services: this.list,
      update: this.chosens.length>0,
      removeIds: [],
      addIds: []
    }
  },
  methods: {
    init () {
      this.services.forEach(serv => {
        let value = this.chosens.find(val => val.service_id==serv.id)
        if (value!=undefined) {
          serv.price = value.price
          serv.checked = true
        }
      })
    },
    removeService(service) {
      if (!service.checked) {
        let value = this.chosens.find(val => val.service_id==this.services[service.index].id)
        if (value!=undefined) this.removeIds.push(value.service_id)
      } else { this.removeIds = this.removeIds.filter(id => id!=this.services[service.index].id) }
      this.$emit('delete', this.removeIds)
    },
    addService(service) {
      if (service.checked) {
        let value = this.chosens.find(val => val.service_id==this.services[service.index].id)
        if (value==undefined) this.addIds.push(this.services[service.index])
      } else { this.addIds = this.addIds.filter(id => id!=this.services[service.index].id) }
      this.$emit('add', this.addIds)
    },
    chooseService(service) {
      this.removeService(service)
      this.addService(service)
      this.services[service.index].checked = service.checked
    }
  }
}
</script>
<style scoped>
label {
  font-weight: 600;
}
</style>