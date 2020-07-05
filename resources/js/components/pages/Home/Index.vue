<template>
  <div>
    <header-bar/>
    <router-view/>
  </div>
</template>
<script>
import { $request } from '../../../auth'
import HeaderBar from '../../layouts/HeaderBar'
export default {
  components: {
    HeaderBar
  },
  data () {
    return {}
  },
  mounted () {
    this.authUser()
  },
  methods: {
    authUser () {
      $request.get('/api/user/find')
      .then(res => {
        this.$store.commit('auth/user', res.data)
      })
      .catch(err => {
        console.log(err)
      })
    }
  }
}
</script>