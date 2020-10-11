<template>
  <div class="row mx-0">
    <div class="mx-auto" v-if="services.length==0">
      <span v-if="loading">
        <i class="fas fa-spinner fa-pulse"></i>
      </span>
      <span v-else>Dịch vụ của phòng đang được cập nhật</span>
    </div>
    <div v-else class="col-md-6 col-lg-3 py-2 text-center" v-for="service in services" :key="service.id">
      {{ service.name }} {{ service.price }} vnđ/{{ service.unit }}
    </div>
  </div>
</template>
<script>
export default {
  name: 'room-services',
  props: {
    id: {
      required: true,
      type: Number
    }
  },
  data() {
    return {
      services: [],
      loading: true
    }
  },
  mounted() {
    this.loading = true
    ajax().get(`/api/house/${this.id}/services`)
    .then(res => {
      this.services = res.data
      this.loading = false
    })
    .catch(err => {
      this.loading = false
    })
  }
}
</script>