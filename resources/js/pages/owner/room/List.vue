<template>
  <div class="container">
    <div class="col-md-10 mx-auto">
      <h4>Danh sách phòng trọ</h4>
    </div>
  </div>
</template>
<script>
import $serialize from '../../../utilities/serialize'

export default {
  data () {
    return {
      query: {
        page: 1,
        house: this.$route.params.house_id
      }
    }
  },
  // watch: {
  //   "$route.params.house_id": {
  //     handler (house_id) {
  //       this.house_id = house_id
  //       this.list()
  //     },
  //     deep: true,
  //     immediate: true
  //   }
  // },
  created () {
    this.list()
  },
  methods: {
    list () {
      $auth.request.get(`/api/room/list?${serialize.fromObj(this.query)}`)
      .then(res => {
        console.log(res)
      })
      .catch(err => {
        console.log(err)
      })
    }
  }
}
</script>