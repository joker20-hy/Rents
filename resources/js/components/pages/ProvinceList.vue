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
            <button class="btn btn-outline-primary" @click="edit=true,chosenId=prov.id">Edit</button>
            <button class="btn text-danger" @click="destroy=true,chosenId=prov.id">Delete</button>
          </td>
        </tr>
      </transition-group>
    </table>
    <paginate
      v-model="page"
      :page-count="page_count"
      :page-range="3"
      :margin-pages="2"
      :click-handler="clickCallback"
      :prev-text="'Prev'"
      :next-text="'Next'"
      :container-class="'pagination'"
      :page-class="'page-item'">
    </paginate>
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
            <input type="text" class="form-control">
          </div>
          <div class="form-group">
            <input type="text" class="form-control">
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
      chosenId: '',
      edit: false,
      create: false,
      destroy: false,
      deleteConfirm: false,
      page: 1,
      page_count: 1,
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
    api (page=null) {
      return '/api/province'+(page==null? '' : '?page='+page)
    },
    clickCallback (page) {
      this.page = page
      this.list(page)
    },
    store () {
      $auth.request.post(this.api(), {
        name: this.province.name
      })
      .then(res => {
        this.province.name = ''
        this.list(this.page)
      })
      .catch(err => console.log(err))
    },
    list (page=null) {
      $auth.request.get(this.api(page))
      .then(res => {
        this.provinces = []
        this.page_count = res.data.last_page
        res.data.data.forEach(province => this.provinces.push(province))
      })
      .catch(err => {
        this.$store.commit('setNotification', {
          title: "Không thành công",
          message: "Không thể lấy được danh sách tỉnh thành"
        })
      })
    },
    destroyConfirm () {
      $auth.request.delete('/api/province/'+this.chosenId)
      .then(res => {
        this.deleteConfirm=false
        this.list(this.page)
      })
    },
    notification (title, message) {
      this.$store.commit('setNotification', {
        title: title,
        message: message
      })
      this.$bvModal.show('notification')
    },
    update () {
      //
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
    transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
  }
  .slide-fade-enter, .slide-fade-leave-to {
    transform: translateX(10px);
    opacity: 0;
  }
</style>