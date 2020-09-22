<template>
  <modal :name="name" :height="'auto'" :width="500" :classes="['bg-white', 'p-2']" @before-open="get">
    <p>Những người thuê phòng này có thể xem những phương thức thánh toán dưới đây</p>
    <div v-for="(item, index) in paymethods" :key="item.id" class="row mx-0" page-section>
      <div class="d-flex align-items-center p-2">
        <check-box :index="index" @change="choose" :checked="item.checked"></check-box>
      </div>
      <div>
        <h4 class="text-primary">{{ item.name }}</h4>
        <div>Số tài khoản: {{ item.account }}</div>
        <div>Ghi chú: {{ item.note }}</div>
      </div>
    </div>
    <div class="text-right">
      <button class="btn text-primary" @click="changePayMethods()">
        <i class="fas fa-cloud-upload-alt"></i> Xác nhận
      </button>
    </div>
  </modal>
</template>
<script>
import CheckBox from '../../utilities/CheckBox'

export default {
  name: 'pay-methods',
  components: {
    CheckBox
  },
  props: {
    name: {
      required: true,
      type: String
    },
    chosen: {
      required: false,
      default () {
        return []
      },
      type: Array
    }
  },
  data () {
    return {}
  },
  computed: {
    paymethods () {
      let records = this.$store.getters['paymethods/paymethods']
      records.forEach(item => {
        item.checked = this.chosen.find(pm => pm.id==item.id)!=undefined
      })
      return records
    }

  },
  methods: {
    choose (obj) {
      this.paymethods[obj.index].checked = obj.checked
    },
    get() {
      if (this.paymethods.length>0) return true 
      $eventHub.$emit('on-loading')
      ajax().get('/api/pay-method')
      .then(res => {
        $eventHub.$emit('off-loading')
        this.$store.commit('paymethods/paymethods', res.data)
      })
      .then(err => {
        $eventHub.$emit('off-loading')
      })
    },
    changePayMethods () {
      let chosenItems = this.paymethods.filter(item => item.checked)
      this.$emit('change', chosenItems)
    }
  }
}
</script>