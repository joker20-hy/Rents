<template>
  <transition name="slide-fade">
    <form @submit.prevent="store" class="bg-light rounded">
      <div class="row form-group px-3 py-2">
        <div class="col-md-4">
          <select class="form-control" v-model="district.province_id" required>
            <option value="" selected>Tỉnh/thành</option>
            <option v-for="prov in provinces" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
          </select>
        </div>
        <div class="col-md-6 pl-0">
          <input type="text" class="form-control" v-model="district.name" placeholder="District name" required>
        </div>
        <div class="col-md-1 pl-0">
          <button class="btn text-primary">Create</button>
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
  name: 'district-create',
  data () {
    return {
      provinces: [],
      district: {
        province_id: '',
        name: ''
      }
    }
  },
  created () {
    this.getProvinces()
  },
  methods: {
    getProvinces () {
      $auth.request.get('/api/province/all')
      .then(res => {
        this.provinces = res.data
      })
      .catch(err => {
        console.log(err.response.data.message)
      })
    },
    reset () {
      this.district.province_id = ''
      this.district.name = ''
    },
    store () {
      $auth.request.post('/api/district', this.district)
      .then(res => {
        this.reset()
        this.$emit('created', res.data)
        this.$emit('success', 'District has been created')
      })
      .catch(err => {
        this.$emit('error', 'Unable to create new district')
      })
    },
    cancel () {
      this.reset()
      this.$emit('cancel')
    }
  }
}
</script>