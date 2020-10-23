<template>
  <transition name="slide-fade">
    <form @submit.prevent="store" class="bg-light rounded">
      <div class="row form-group px-3 py-2">
        <div class="col-md-3">
          <select class="form-control" v-model="area.province_id" @change="getDistricts()" required>
            <option value="" selected>Tỉnh/thành</option>
            <option v-for="prov in provinces" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
          </select>
        </div>
        <div class="col-md-3 pl-0">
          <select class="form-control" v-model="area.district_id" required>
            <option value="" selected>Quận/Huyện</option>
            <option v-for="dist in districts" :key="dist.id" :value="dist.id">{{ dist.name }}</option>
          </select>
        </div>
        <div class="col-md-4 pl-0">
          <input type="text" class="form-control" v-model="area.name" placeholder="Area name" required>
        </div>
        <div class="col-md-1 pl-0">
          <button class="w-100 btn text-primary">Create</button>
        </div>
        <div class="col-md-1 pl-0">
          <button type="button" class="btn text-danger" @click="cancel()">Cancel</button>
        </div>
      </div>
    </form>
  </transition>
</template>
<script>
export default {
  name: 'area-create',
  data () {
    return {
      provinces: [],
      districts: [],
      area: {
        province_id: '',
        district_id: '',
        name: ''
      }
    }
  },
  created () {
    this.getProvinces()
  },
  methods: {
    reset () {
      this.area.province_id = ''
      this.area.district_id = ''
      this.area.name = ''
    },
    getProvinces () {
      ajax().get('/api/province/all')
      .then(res => {
        this.provinces = res.data
      })
      .catch(err => {
        console.log(err.response.data.message)
      })
    },
    getDistricts () {
      this.area.district_id = ''
      ajax().get(`/api/district/${this.area.province_id}`)
      .then(res => {
        this.districts = res.data
      })
      .catch(err => {
        console.log(err.response.data.message)
      })
    },
    store () {
      ajax().post('/api/area', this.area)
      .then(res => {
        this.reset()
        this.$emit('created', res.data)
        this.$emit('success', 'New area has been created successfully')
      })
      .catch(err => {
        this.$emit('error', 'Unable to create new area')
      })
    },
    cancel () {
      this.reset()
      this.$emit('cancel')
    }
  }
}
</script>