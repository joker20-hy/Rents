<template>
  <div page-section class="text-light bg-primary">
    <div class="d-flex">
      <h5>Liên hệ khi thuê phòng</h5>
      <router-link class="ml-auto text-light" :to="{name: 'owner-detail-house', params: {id: house_id}}">Sửa</router-link>
    </div>
    <div v-if="house.contact">
      <span class="text-bold">Số điện thoại:</span> {{ house.contact.phone }}
    </div>
    <div v-if="house.contact">
      <span class="text-bold">Khác:</span> {{ house.contact.others==null?'Hiện chưa rõ':house.contact.others }}
    </div>
    <div v-else class="text-center text-muted">
        Hiện chữa rõ
        <br>
        <router-link :to="{name: 'owner-detail-house', params: {id: house_id}}">Thêm</router-link>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    houseid: {
      required: true
    }
  },
  watch: {
    houseid(val) {
      this.house_id=val
      this.getHouse()
    }
  },
  created() {
    this.getHouse()
  },
  data() {
    return {
      house_id: this.houseid,
      house: {}
    }
  },
  methods: {
    getHouse() {
      ajax().get(`/api/house/${this.house_id}`)
      .then(res => {
        res.data.contact=res.data.contact==null
                            ?{phone:'',others:''}
                            :JSON.parse(res.data.contact)
        this.house = res.data
      })
      .catch(err => {
        console.log(err)
      })
    }
  }
}
</script>