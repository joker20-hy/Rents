<template>
  <div class="container">
    <h1 class="d-flex align-items-end mt-5">
        Danh sách quận/huyện
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
            <select class="form-control" v-model="district.province_id" required>
              <option value="" selected>Tỉnh/thành</option>
              <option v-for="prov in provinces" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
            </select>
          </div>
          <div class="col-md-9">
            <input type="text" class="form-control" placeholder="Nhập tên quận huyện" v-model="district.name" required>
          </div>
        </div>
        <button class="btn btn-outline-primary">Create</button>
      </form>
    </b-collapse>
    <!-- end create form -->
    <!-- list province -->
    <table class="table table-borderless">
      <thead>
        <th>STT</th>
        <th>Tên</th>
        <th>Slug</th>
        <th>Tỉnh thành</th>
        <th>Hành động</th>
      </thead>
      <transition-group name="slide-fade" tag="tbody">
        <tr v-for="dist in districts" :key="dist.id">
          <td>{{ dist.id }}</td>
          <td>{{ dist.name }}</td>
          <td>{{ dist.slug }}</td>
          <td>{{ dist.province.name }}</td>
          <td>
            <button class="btn btn-outline-primary" @click="edit=true,choose(dist)">Edit</button>
            <button class="btn text-danger" @click="destroy=true,choose(dist)">Delete</button>
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
    <!-- end list province -->
    <!-- delete province confirm -->
    <b-modal v-model="destroy" centered hide-header hide-footer>
      <div class="p-2 text-center">
        <h3>Xác nhận xóa</h3>
        <br>
        <b-button variant="outline-primary" @click="destroyConfirm()">Confirm</b-button>
        <b-button variant="outline-danger" @click="destroy=false">Cancel</b-button>
      </div>
    </b-modal>
    <!-- edit province -->
    <b-modal v-model="edit" centered hide-header hide-footer>
      <div class="p-2 text-center">
        <h3>Sửa thông tin quận huyện</h3>
        <form action="" class="py-3" @submit.prevent>
          <div class="row form-group">
            <div class="col-md-6">
              <select class="form-control" v-model="chosenDistrict.province_id">
                <option v-for="prov in provinces" :key="prov.id" :value="prov.id">
                  {{ prov.name }}
                </option>
              </select>
            </div>
            <div class="col-md-6">
              <input type="text" class="form-control" v-model="chosenDistrict.name">
            </div>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" v-model="chosenDistrict.slug">
          </div>
        </form>
        <b-button variant="outline-primary" @click="update()">Update</b-button>
        <b-button variant="outline-danger" @click="edit=false">Cancel</b-button>
      </div>
    </b-modal>
  </div>
</template>
<script>
import { $auth } from '../../auth.js'

export default {
  name: 'district-list',
  data () {
    return {
      chosenDistrict: '',
      edit: false,
      create: false,
      destroy: false,
      page: 1,
      page_count: 1,
      provinces: [],
      districts: [],
      district: {
        name: '',
        province_id: ''
      }
    }
  },
  mounted () {
    this.list()
    this.getProvinces()
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
    /**
     * Change page
     * @param {Number} page target page
     */
    changePage (page) {
      this.page = page
      this.list(page)
    },
    /**
     * Choose a province for the next action
     * @param {Object} province
     */
    choose (district) {
      this.chosenDistrict = {
        id: district.id,
        name: district.name,
        slug: district.slug,
        province_id: district.province_id
      }
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
    /**
     * Show notification
     * @param {String} title
     * @param {String} message
     * @param {Object} colors {title: {String},message: {String}}
     * @param {Number} timeout in seconds
     */
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
    },
    /**
     * Store a new province
     */
    store () {
      $auth.request.post('/api/district', {
        province_id: this.district.province_id,
        name: this.district.name
      })
      .then(res => {
        this.district.province_id = ''
        this.district.name = ''
        this.total = this.total + 1
        if (this.total % 10==1) {
          this.changePage(this.page + 1)
        } else {
          this.districts.push(res.data)
        }
      })
      .catch(err => {
        this.error(err.response.data.message)
      })
    },
    /**
     * List all provinces
     * @param {Number} page
     */
    list (page=1) {
      $auth.request.get(`/api/district?page=${page}`)
      .then(res => {
        this.districts = []
        this.page_count = res.data.last_page
        this.total = res.data.total
        res.data.data.forEach(district => this.districts.push(district))
      })
      .catch(err => {
        this.error('Không thể lấy được danh sách quận/huyện')
      })
    },
    /**
     * Confirm delete a district
     */
    destroyConfirm () {
      $auth.request.delete('/api/district/'+this.chosenDistrict.id)
      .then(res => {
        this.destroy=false
        this.districts = this.districts.filter(prov => prov.id!=this.chosenDistrict.id)
        this.success('Xóa thành công')
      })
      .catch(err => {
        this.destroy=false
        this.error(err.response.data.message)
      })
    },
    update () {
      $auth.request.put('/api/district/'+this.chosenDistrict.id, {
        name: this.chosenDistrict.name,
        slug: this.chosenDistrict.slug,
        province_id: this.chosenDistrict.province_id
      })
      .then(res => {
        this.districts.forEach(prov => {
          if (prov.id == this.chosenDistrict.id) {
            prov.name = this.chosenDistrict.name
            prov.slug = this.chosenDistrict.slug
            prov.province_id = this.chosenDistrict.province_id
          }
        })
        this.edit=false
        this.success('Chỉnh sửa thành công')
      })
      .catch(err => {
        this.error(err.response.data.message)
      })
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