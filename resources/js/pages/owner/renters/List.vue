<template>
  <div class="contain-sm mt-3">
    <h1 style="font-size: large">Danh sách người thuê</h1>
    <list-item v-for="renter in renters" :key="renter.id" :item="renter"/>
  </div>
</template>
<script>
import ListItem from './ListItem'
export default {
  components: {
    ListItem
  },
  data () {
    return {
      id: this.$route.params.room
    }
  },
  computed: {
    renters () {
      return this.$store.getters['users/users']
    }
  },
  mounted () {
    this.list()
  },
  methods: {
    list () {
      ajax().get(`/api/room/${this.id}/renters`)
      .then(res => {
        this.$store.commit('users/users', res.data)
      })
      .catch(err => {
        console.log(err)
      })
    }
  }
}
</script>