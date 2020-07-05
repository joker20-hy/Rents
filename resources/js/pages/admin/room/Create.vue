<template>
    <div class="container d-flex">
      <form @submit.prevent="store()" id="create-room">
        <h3 class="py-3 px-2 bg-primary text-light" style="margin-left: -25px;margin-right: -25px;">
          Create room
        </h3>
        <div class="form-group">
          <label>House <span class="text-danger" title="Required feild">*</span>
          </label>
          <suggest-box :api="'/api/sg/houses'" :placeholder="'House'" @change="getHouse" :required="true"/>
        </div>
        <div class="form-group row" v-show="images.length>0">
          <div v-for="(image, index) in images" :key="index" class="col-4">
            <img :src="image" alt="" class="mw-100">
          </div>
        </div>
        <div class="form-group">
          <label>Images <span class="text-danger" title="Required feild">*</span>:</label>
          <button type="button" class="btn text-primary" onclick="clickTarget('#images')">
            <i class="far fa-images fa-lg"></i>
          </button>
          <div class="text-danger" v-if="error.images!=undefined">{{ error.images[0] }}</div>
          <input type="file" id="images" class="d-none" ref="images" @change="getImages" accept="image/*" multiple>
        </div>
        <div class="form-group">
          <label>Name <span class="text-danger" title="Required feild">*</span>
          </label>
          <input type="text" class="input" v-model="room.name" placeholder="Room name" required>
          <div class="text-danger" v-if="error.name">{{ error.name[0] }}</div>
        </div>
        <div class="form-group">
          <label>Acreage <span class="text-danger" title="Required feild">*</span></label>
          <div class="d-flex align-items-end">
            <input type="number" class="input" v-model="room.acreage" placeholder="Room acreage" required><span class="px-3">m2</span>
          </div>
          <div class="text-danger" v-if="error.acreage">{{ error.acreage[0] }}</div>
        </div>
        <div class="form-group">
          <label>Price <span class="text-danger" title="Required feild">*</span></label>
          <div class="d-flex align-items-end">
            <input type="number" class="input" v-model="room.price" placeholder="Room price" required><span class="px-3">vnđ/tháng</span>
          </div>
          <div class="text-danger" v-if="error.price">{{ error.price[0] }}</div>
        </div>
        <div class="form-group">
          <label>Cycle <span class="text-danger" title="Required feild">*</span></label>
          <div class="d-flex align-items-end">
            <input type="number" class="input" v-model="room.cycle" placeholder="Pay cycle" required><span class="px-3">Tháng</span>
          </div>
          <div class="text-danger" v-if="error.cycle">{{ error.cycle[0] }}</div>
        </div>
        <div class="form-group">
          <label>Description <span class="text-danger" title="Required feild">*</span></label>
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
import { $auth } from '../../../utilities/request/request'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import adapter from '../../../utilities/CKImageAdapter'
import SuggestBox from '../../utilities/SuggestBox'
import CheckBox from '../../utilities/CheckBox'
export default {
  name: 'create-room',
  components: {
    SuggestBox,
    CheckBox
  },
  data () {
    return {
      editor: ClassicEditor,
      editorConfig: {
        extraPlugins: [ adapter ],
      },
      room: {
        name: '',
        acreage: '',
        images: [],
        description: '',
        house_id: '',
        price: '',
        cycle: ''
      },
      images: [],
      criterias: [],
      error: {}
    }
  },
  mounted () {
    this.getCriterias()
  },
  methods: {
    getCriteria (criteria) {
      this.criterias[criteria.index].checked = criteria.checked
    },
    getHouse (house) {
      this.room.house_id = house.id
    },
    getImages (e) {
      this.room.images = e.target.files
      this.images = []
      for(let i=0;i<e.target.files.length;++i) {
        this.images[i] = URL.createObjectURL(e.target.files[i])
      }
    },
    getData () {
      let data = new FormData()
      data.append('name', this.room.name)
      data.append('acreage', this.room.acreage)
      for(let i=0;i<this.room.images.length;++i) {
        data.append(`images[${i}]`, this.room.images[i])
      }
      let criCount = 0
      this.criterias.forEach(cri => {
        if (cri.checked) {
          data.append(`criterias[${criCount}]`,cri.id)
          ++criCount
        }
      })
      data.append('description', this.room.description)
      data.append('house_id', this.room.house_id)
      data.append('price', this.room.price)
      data.append('cycle', this.room.cycle)
      return data
    },
    store () {
      $auth.request.post('/api/room', this.getData(), {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(res => {
        this.$router.push({name: 'room-list'})
      })
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
      $auth.request.get('/api/criteria')
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
<style scoped>
  label {
    font-weight: 600
  }
  #create-room {
    margin: auto;
    margin-top: 15px;
    padding: 20px 15px;
    max-width: 100%;
    width: 720px;
    background-color: var(--light);
    box-shadow: 0px 1px 3px #aaa;
  }
</style>