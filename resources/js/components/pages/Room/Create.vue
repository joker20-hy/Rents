<template>
    <div class="container d-flex">
      <form @submit.prevent="store()" id="create-room">
        <h3 class="py-3 px-2 bg-primary text-light" style="margin-left: -25px;margin-right: -25px;">
          Create room
        </h3>
        <div class="form-group">
          <label for="">
            House
            <span class="text-danger" title="Required feild">*</span>
          </label>
          <suggest-box :api="'/api/sg/houses'" :placeholder="'House'" @change="getHouse" :required="true"/>
        </div>
        <div class="form-group row" v-show="images.length>0">
          <div v-for="(image, index) in images" :key="index" class="col-4">
            <img :src="image" alt="" class="mw-100">
          </div>
        </div>
        <div class="form-group">
          <label for="">Images <span class="text-danger" title="Required feild">*</span>:</label>
          <button type="button" class="btn text-primary" onclick="clickTarget('#images')">
            <i class="far fa-images fa-lg"></i>
          </button>
          <div class="text-danger" v-if="error.images!=undefined">{{ error.images[0] }}</div>
          <input type="file" id="images" class="d-none" ref="images" @change="getImages" accept="image/*" multiple>
        </div>
        <div class="form-group">
          <label for="">
            Name
            <span class="text-danger" title="Required feild">*</span>
          </label>
          <input type="text" class="input" v-model="room.name" placeholder="Room name" required>
          <div class="text-danger" v-if="error.name">{{ error.name[0] }}</div>
        </div>
        <div class="form-group">
          <label for="">
            Acreage
            <span class="text-danger" title="Required feild">*</span>  
          </label>
          <div class="d-flex align-items-end">
            <input type="number" class="input" v-model="room.acreage" placeholder="Room acreage" required><span class="px-3">m2</span>
          </div>
          <div class="text-danger" v-if="error.acreage">{{ error.acreage[0] }}</div>
        </div>
        <div class="form-group">
          <label for="">
            Description
            <span class="text-danger" title="Required feild">*</span>
          </label>
          <ckeditor :editor="editor" v-model="room.description" :config="editorConfig"></ckeditor>
          <div class="text-danger" v-if="error.description">{{ error.description[0] }}</div>
        </div>
        <div class="form-group text-right">
          <button class="btn btn-outline-primary">Create</button>
        </div>
      </form>
    </div>
</template>
<script>
import { $auth } from '../../../auth'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import adapter from '../../../utilities/CKImageAdapter'
import SuggestBox from '../../utilities/SuggestBox'

export default {
  name: 'create-room',
  components: {
    SuggestBox
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
        house_id: ''
      },
      images: [],
      error: {}
    }
  },
  methods: {
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
      data.append('description', this.room.description)
      data.append('house_id', this.room.house_id)
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