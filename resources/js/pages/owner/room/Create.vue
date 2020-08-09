<template>
  <div class="container">
    <form @submit.prevent="store()" class="col-lg-10 col-xl-8" detail-form id="create-room">
        <h3 class="py-3 px-2 bg-primary text-light" style="margin-left: -25px;margin-right: -25px;">
          Thêm phòng
        </h3>
        <div class="form-group row" v-show="images.length>0">
          <div v-for="(image, index) in images" :key="index" class="col-4">
            <img :src="image" alt="" class="mw-100">
          </div>
        </div>
        <div class="form-group">
          <label>Ảnh của phòng <span class="text-danger" title="Required feild">*</span>:</label>
          <button type="button" class="btn text-primary" onclick="clickTarget('#images')">
            <i class="far fa-images fa-lg"></i>
          </button>
          <div class="text-danger" v-if="error.images!=undefined">{{ error.images[0] }}</div>
          <input type="file" id="images" class="d-none" ref="images" @change="getImages" accept="image/*" multiple>
        </div>
        <div class="form-group">
          <label>Tên phòng <span class="text-danger" title="Required feild">*</span>
          </label>
          <input type="text" class="input" v-model="room.name" placeholder="Tên phòng" required>
          <div class="text-danger" v-if="error.name">{{ error.name[0] }}</div>
        </div>
        <div class="form-group">
          <label>Diện tích <span class="text-danger" title="Required feild">*</span></label>
          <div class="d-flex align-items-end">
            <input type="number" class="input" v-model="room.acreage" placeholder="Diện tích" required><span class="px-3">m2</span>
          </div>
          <div class="text-danger" v-if="error.acreage">{{ error.acreage[0] }}</div>
        </div>
        <div class="form-group">
          <label>Giá <span class="text-danger" title="Required feild">*</span></label>
          <div class="d-flex align-items-end">
            <input type="number" class="input" v-model="room.price" placeholder="Giá phòng" required><span class="px-3">vnđ/tháng</span>
          </div>
          <div class="text-danger" v-if="error.price">{{ error.price[0] }}</div>
        </div>
        <div class="form-group">
          <label>Chu kỳ đóng tiền <span class="text-danger" title="Required feild">*</span></label>
          <div class="d-flex align-items-end">
            <input type="number" class="input" v-model="room.cycle" placeholder="Chu kỳ đóng tiền" required><span class="px-3">Tháng</span>
          </div>
          <div class="text-danger" v-if="error.cycle">{{ error.cycle[0] }}</div>
        </div>
        <div class="form-group">
          <label>Mô tả <span class="text-danger" title="Required feild">*</span></label>
          <ckeditor :editor="editor" v-model="room.description" :config="editorConfig"></ckeditor>
          <div class="text-danger" v-if="error.description">{{ error.description[0] }}</div>
        </div>
        <label>Tiêu chí của phòng trọ <span class="text-danger" title="Required feild">*</span></label>
        <div class="row mx-0">
          <div class="col-4" v-for="(cri, index) in criterias" :key="cri.id">
            <check-box :label="cri.name" :index="index" @change="getCriteria"/>
          </div>
        </div>
        <div class="form-group text-right">
          <button class="btn btn-outline-primary">Create</button>
        </div>
      </form>
  </div>
</template>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import adapter from '../../../utilities/CKImageAdapter'
import CheckBox from '../../utilities/CheckBox'
export default {
  components: {
    CheckBox
  },
  data () {
    return {
      editor: ClassicEditor,
      editorConfig: {
        extraPlugins: [ adapter ],
      },
      images: [],
      error: {},
      room: {
        name: '',
        house_id: this.$route.params.id,
        acreage: '',
        price: '',
        cycle: '',
        description: ''
      },
      criterias: []
    }
  },
  mounted () {
    this.getCriterias()
  },
  computed: {
    data () {
      let data = new FormData()
      data.append('name', this.room.name)
      data.append('acreage', this.room.acreage)
      for(let i=0;i<this.room.images.length;++i) { data.append(`images[${i}]`, this.room.images[i]) }
      let criCount = 0
      this.criterias.forEach(cri => {
        if (cri.checked) { data.append(`criterias[${criCount}]`,cri.id); ++criCount }
      })
      data.append('description', this.room.description)
      data.append('house_id', this.room.house_id)
      data.append('price', this.room.price)
      data.append('cycle', this.room.cycle)
      return data
    }
  },
  methods: {
    getCriteria (criteria) {
      this.criterias[criteria.index].checked = criteria.checked
    },
    getImages (e) {
      this.room.images = e.target.files
      this.images = []
      for(let i=0;i<e.target.files.length;++i) {
        this.images[i] = URL.createObjectURL(e.target.files[i])
      }
    },
    store () {
      $request.post('/api/room', this.data, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      .then(res => this.$router.push({name: 'room-list'}) )
      .catch(err => {
        if (err.response==422) {
          this.error = err.response.data.errors
        } else {
          this.error = {}
          $eventHub.$emit('error-alert', {
            title: 'Error',
            message: 'Unable to store room, please try again',
            timeout: 3000
          })
        }
      })
    },
    getCriterias () {
      $request.get('/api/criteria')
      .then(res => {
        res.data.forEach(cri => cri.checked = false);
        this.criterias = res.data
      })
      .catch(err => {
        console.log(err.response.data)
      })
    }
  }
}
</script>