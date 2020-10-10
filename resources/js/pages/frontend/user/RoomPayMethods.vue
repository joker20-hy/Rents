<template>
  <div contain-box class="bg-white mt-1">
    <h1 style="font-size: large">Phương thức thanh toán của phòng</h1>
    <div v-for="item in paymethods" :key="item.id" page-section>
      <h4 class="text-primary">{{ item.name }}</h4>
      <div v-show="!edit">Số tài khoản: {{ item.account }}</div>
      <div v-show="!edit">Ghi chú: {{ item.note }}</div>
    </div>
  </div>
</template>
<script>
export default {
  data () {
    return {}
  },
  computed: {
    paymethods () {
      return this.$store.getters['paymethods/paymethods']
    }
  },
  watch: {
    "$route.param.id": {
      handler (id) {
        if (this.paymethods.length==0) this.get(id)
      },
      deep: true,
      immediate: true
    }
  },
  methods: {
    get (id) {
      $eventHub.$emit('on-loading')
      ajax().get(`/api/pay-method?room=${id}`)
      .then(res => {
        $eventHub.$emit('off-loading')
        this.$store.commit('paymethods/paymethods', res.data)
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        console.log(err)
      })
    }
  }
}
</script>