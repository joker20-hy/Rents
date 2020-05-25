<template>
  <div class="container">
    <h1 class="d-flex align-items-end mt-5">
      Danh sách khu vực
      <b-button
        :class="create ? '' : 'collapsed'"
        :aria-expanded="create ? 'true' : 'false'"
        aria-controls="create-form"
        variant="light"
        class="show-create-form ml-auto"
        @click="create = !create"
      >
        <i class="fas fa-plus" :class="create ? 'cancel-create' : ''"></i>
      </b-button>
    </h1>
    <!-- create form -->
    <b-collapse id="create-form" v-model="create">
      <form class="my-3" @submit.prevent="store()">
        <div class="row form-group">
          <div class="col-md-3">
            <select class="form-control" v-model="area.province_id" @change="getDistricts()" required>
              <option value="" selected>Tỉnh/thành</option>
              <option v-for="prov in provinces" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-control" v-model="area.district_id" required>
              <option value="" selected>Quận/Huyện</option>
              <option v-for="dist in districts" :key="dist.id" :value="dist.id">{{ dist.name }}</option>
            </select>
          </div>
          <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Nhập tên quận huyện" v-model="area.name" required>
          </div>
        </div>
        <button class="btn btn-outline-primary">Create</button>
      </form>
    </b-collapse>
    <!-- end create form -->

    <table class="table table-borderless">
      <thead>
        <th>STT</th>
        <th>Tên</th>
        <th>Slug</th>
        <th>Tỉnh thành</th>
        <th>Hành động</th>
      </thead>
      <transition-group name="slide-fade" tag="tbody">
        <tr v-for="area in areas" :key="area.id">
          <td>{{ area.id }}</td>
          <td>{{ area.name }}</td>
          <td>{{ area.slug }}</td>
          <td>{{ area.province.name }}</td>
          <td>{{ area.district.name }}</td>
          <td>
            <button class="btn btn-outline-primary" @click="edit=true,choose(area)">Edit</button>
            <button class="btn text-danger" @click="remove=true,choose(area)">Delete</button>
          </td>
        </tr>
      </transition-group>
    </table>
    <paginate
      v-model="page"
      :page-count="page_count"
      :page-range="3"
      :margin-pages="2"
      :click-handler="changePage"
      :prev-text="'Prev'"
      :next-text="'Next'"
      :container-class="'pagination'"
      :page-class="'page-item'">
    </paginate>
    <!-- edit area -->
    <b-modal v-model="edit" centered hide-header hide-footer>
      <div class="p-2 text-center">
        <h3>Sửa thông tin quận huyện</h3>
        <form action="" class="py-3" @submit.prevent>
          <div class="row form-group">
            <div class="col-md-6">
              <select class="form-control" v-model="chosen.province_id" @change="getDistricts(chosen.province_id),chosen.district_id=''">
                <option v-for="prov in provinces" :key="prov.id" :value="prov.id">
                  {{ prov.name }}
                </option>
              </select>
            </div>
            <div class="col-md-6">
              <select class="form-control" v-model="chosen.district_id">
                <option value="" selected>Quận/Huyện</option>
                <option v-for="dist in districts" :key="dist.id" :value="dist.id">
                  {{ dist.name }}
                </option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" v-model="chosen.name">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" v-model="chosen.slug">
          </div>
        </form>
        <b-button variant="outline-primary" @click="update()">Update</b-button>
        <b-button variant="outline-danger" @click="edit=false">Cancel</b-button>
      </div>
    </b-modal>
    <!-- destroy confirm -->
    <b-modal v-model="remove" centered hide-header hide-footer>
      <div class="p-2 text-center">
        <h3>Xác nhận xóa</h3>
        <br>
        <b-button variant="outline-primary" @click="destroy()">Confirm</b-button>
        <b-button variant="outline-danger" @click="remove=false">Cancel</b-button>
      </div>
    </b-modal>
  </div>
</template>
<script>
import { $auth } from '../../auth.js'

export default {
  data () {
    return {
      page: 1,
      page_count: 1,
      per_page: 1,
      areas: [],
      chosen: '',
      create: false,
      edit: false,
      remove: false,
      districts: [],
      provinces: [],
      area: {
        province_id: '',
        district_id: '',
        name: ''
      }
    }
  },
  mounted () {
    this.getProvinces()
    this.list()
  },
  methods: {
    getProvinces () {
      $auth.request.get('/api/province/all')
      .then(res => {
        this.provinces = res.data
      })
      .catch(err => {
        this.error(err.response.data.message)
      })
    },
    getDistricts (province_id=null) {
      $auth.request.get(`/api/district/${province_id==null ? this.area.province_id : province_id}`)
      .then(res => {
        this.districts = res.data.data
      })
      .catch(err => {
        this.error(err.response.data.message)
      })
    },
    changePage (page) {
      this.page = page
    },
    list (page=1) {
      $auth.request.get(`/api/area?page=${page}`)
      .then(res => {
        this.page_count = res.data.last_page
        this.total = res.data.total
        this.per_page = res.data.per_page
        this.areas = res.data.data
      })
      .catch(err => {
        this.error(err.response.data.message)
      })
    },
    store () {
      $auth.request.post('/api/area', {
        province_id: this.area.province_id,
        district_id: this.area.district_id,
        name: this.area.name
      })
      .then(res => {
        this.area.province_id = ''
        this.area.district_id = ''
        this.area.name = ''
        this.total = this.total + 1
        if (this.total % this.per_page==1) {
          this.changePage(this.page + 1)
        } else {
          this.areas.push(res.data)
        }
      })
      .catch(err => {
        this.error(err.response.data.message)
      })
    },
    choose (area) {
      this.chosen = {
        id: area.id,
        name: area.name,
        slug: area.slug,
        province_id: area.province_id,
        district_id: area.district_id
      }
      if (this.edit) this.getDistricts(area.province_id)
    },
    update () {
      $auth.request.put(`/api/area/${this.chosen.id}`, {
        name: this.chosen.name,
        slug: this.chosen.slug,
        province_id: this.chosen.province_id,
        district_id: this.chosen.district_id
      })
      .then(res => {
        this.areas.forEach(area => {
          if (area.id == this.chosen.id) {
            area.name = this.chosen.name
            area.slug = this.chosen.slug
            area.province_id = this.chosen.province_id
            area.district_id = this.chosen.district_id
          }
        })
        this.edit = false
        this.success('Update successfully')
      })
      .catch(err => {
        this.error(err.response.data.message)
      })
    },
    destroy () {
      $auth.request.delete(`/api/area/${this.chosen.id}`)
      .then(res => {
        this.areas = this.areas.filter(area => area.id!=this.chosen.id)
        this.success('Delete successfully')
        this.remove = false
      })
      .catch(err => {
        this.error(err.response.data.message)
        this.remove = false
      })
    },
    /**
     * Success notification
     * @param {String} message
     */
    success (message) {
      this.notification('Thành công', message, {
        title: 'text-success',
        message: ''
      })
    },
    /**
     * Error notification
     * @param {String} message
     */
    error (message) {
      this.notification('Không thành công', message, {
        title: 'text-danger',
        message: ''
      })
    },
    notification (title, message, colors, timeout = 3000) {
      this.$store.commit('notification', {
        title: title,
        message: message,
        colors: colors
      })
      this.$bvModal.show('notification')
      setTimeout(() => {
        this.$bvModal.hide('notification')
      }, timeout)
    }
  }
}
</script>
<style scoped>
  .show-create-form {
    width: 40px;
    height:40px;
  }
  .show-create-form i {
    transition: 0.2s;
    color: var(--blue);
  }
  .cancel-create {
    transform: rotate(45deg);
    color: var(--red)!important;
  }
  .slide-fade-enter-active {
    transition: all .3s ease;
  }
  .slide-fade-leave-active {
    transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
  }
  .slide-fade-enter, .slide-fade-leave-to {
    transform: translateX(10px);
    opacity: 0;
  }
</style>
