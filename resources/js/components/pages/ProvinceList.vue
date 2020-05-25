<template>
  <div class="container">
    <h1 class="d-flex align-items-end mt-5">
        Danh sách tỉnh thành
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
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Nhập tên tỉnh thành" v-model="province.name" required>
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
        <th>Hành động</th>
      </thead>
      <transition-group name="slide-fade" tag="tbody">
        <tr v-for="prov in provinces" :key="prov.id">
          <td>{{ prov.id }}</td>
          <td>{{ prov.name }}</td>
          <td>{{ prov.slug }}</td>
          <td>
            <button class="btn btn-outline-primary" @click="edit=true,choose(prov)">Edit</button>
            <button class="btn text-danger" @click="destroy=true,choose(prov)">Delete</button>
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
        <h3>Sửa thông tin tỉnh thành</h3>
        <form action="" class="py-3" @submit.prevent>
          <div class="form-group">
            <input type="text" class="form-control" v-model="chosenProvince.name">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" v-model="chosenProvince.slug">
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
  name: 'province-list',
  data () {
    return {
      chosenProvince: '',
      edit: false,
      create: false,
      destroy: false,
      page: 1,
      page_count: 1,
      per_page: 1,
      provinces: [],
      province: {
        name: ''
      }
    }
  },
  mounted () {
    this.list()
  },
  methods: {
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
    choose (province) {
      this.chosenProvince = {
        id: province.id,
        name: province.name,
        slug: province.slug
      }
    },
    /**
     * Store a new province
     */
    store () {
      $auth.request.post('/api/province', {
        name: this.province.name
      })
      .then(res => {
        this.province.name = ''
        ++this.total
        if (this.total % this.per_page==1) {
          this.changePage(this.page + 1)
        } else {
          this.provinces.push(res.data)
        }
      })
      .catch(err => console.log(err))
    },
    /**
     * List all provinces
     * @param {Number} page
     */
    list (page=1) {
      $auth.request.get('/api/province?page='+this.page)
      .then(res => {
        this.page_count = res.data.last_page
        this.total = res.data.total
        this.per_page = res.data.per_page
        this.provinces = res.data.data
      })
      .catch(err => {
        this.error('Không thể lấy được danh sách tỉnh thành')
      })
    },
    /**
     * Confirm delete a province
     */
    destroyConfirm () {
      $auth.request.delete('/api/province/'+this.chosenProvince.id)
      .then(res => {
        this.destroy=false
        this.provinces = this.provinces.filter(prov => prov.id!=this.chosenProvince.id)
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
    update () {
      $auth.request.put('/api/province/'+this.chosenProvince.id, {
        name: this.chosenProvince.name,
        slug: this.chosenProvince.slug
      })
      .then(res => {
        this.provinces.forEach(prov => {
          if (prov.id == this.chosenProvince.id) {
            prov.name = this.chosenProvince.name
            prov.slug = this.chosenProvince.slug
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