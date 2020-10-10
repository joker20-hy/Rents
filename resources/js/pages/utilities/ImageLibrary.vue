<template>
  <div>
    <label for="" style="font-weight: 600" v-show="label!=''">{{ label }}</label>
    <div class="d-flex align-items-center pt-1" v-if="editable">
      {{ photos_label }}
      <button type="button" class="btn text-primary px-1" onclick="clickTarget('#images')">
        <i class="fas fa-cloud-upload-alt"></i> Thêm ảnh
      </button>
      <input type="file" id="images" class="d-none" ref="images" @change="getImages" accept="image/*" multiple>
      <div class="ml-auto" v-show="editable">
        <button type="button" class="btn text-primary" v-show="!edit&&bucket.length>=0" @click="enterEdit">Chọn</button>
        <button type="button" class="btn text-danger" v-show="edit" @click="showDelete()">
          <i class="far fa-trash-alt"></i>
        </button>
        <button type="button" class="btn text-primary" v-show="edit" @click="leaveEdit">Hủy</button>
      </div>
    </div>
    <div v-show="editable">
      <small class="text-muted">** Kích thước ảnh không thể lớn hơn {{ maxSize }} **</small>
    </div>
    <div v-show="bucket.length==0" class="text-center text-muted">Hiện chưa có ảnh</div>
    <transition-group name="slide-fade" tag="div" class="row photos-bucket">
      <div class="img-item" v-for="img in bucket" :key="img.id" :style="`background-image: url(${img.url})`">
        <div class="choose-cover" @click="choose(img)" :class="img.choose?'show':''">
          <i class="fas fa-check fa-2x text-light"></i>
        </div>
      </div>
    </transition-group>
    <confirm-box :name="'delete-confirm'" :title="'Confirm delete'" :message="'Images gonna be delete forever'" @confirm="remove()"/>
    <confirm-box :name="'upload-confirm'" :title="'Upload images'" :message="photos_label" @confirm="upload()" @cancel="cancelUpload()" @before-close="cancelUpload()"/>
  </div>
</template>
<script>
import ConfirmBox from './ConfirmBox'

export default {
  name: 'image-library',
  components: {
    ConfirmBox
  },
  props: {
    label: {
      required: false,
      default: '',
      type: String
    },
    images: {
      required: true,
      type: Array
    },
    editable: {
      required: false,
      default: true,
      type: Boolean
    },
    updateApi: {
      required: false,
      type: String
    },
    deleteApi: {
      required: false,
      type: String
    }
  },
  data () {
    return {
      edit: false,
      bucket: [],
      photos: [],
      photos_label: '',
      photos_count: 0
    }
  },
  watch: {
    editable (val) {
      this.leaveEdit()
    },
    images (val) {
      this.syncBucket()
    }
  },
  mounted () {
    this.syncBucket()
  },
  computed: {
    maxSize () {
      return $config.MAX_SIZE_IMAGE
    }
  },
  methods: {
    syncBucket () {
      this.bucket = []
      this.images.forEach((img, index) => {
        this.bucket.push({
          id: index,
          url: img,
          choose: false
        })
      })
    },
    enterEdit () {
      this.edit = true
    },
    leaveEdit () {
      this.bucket.forEach(img => img.choose = false)
      this.edit = false
    },
    showDelete () {
      this.$modal.show('delete-confirm')
    },
    getImages (e) {
      this.photos = e.target.files
      this.photos_count = this.photos.length
      this.photos_label = this.photos_count==0?'':`${this.photos.length} images`
      if (this.photos_count > 0) {
        this.$modal.show('upload-confirm')
      }
    },
    getData () {
      let data = new FormData()
      for(let i=0;i<this.photos.length;i++) {
        data.append(`images[${i}]`, this.photos[i])
      }
      return data
    },
    upload () {
      ajax().post(this.updateApi, this.getData(), {
        headers: {'Content-Type': 'multipart/form-data'}
      })
      .then(res => {
        this.$emit('updated', res.data)
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: `Uploaded ${this.photos_count} images`
        })
        this.cancelUpload()
        this.$modal.hide('upload-confirm')
      })
      .catch(err => {
        this.$modal.hide('upload-confirm')
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: `Unable to upload your images, please try a gain`
        })
      })
    },
    cancelUpload () {
      this.photos = []
      this.photos_count = 0
      this.photos_label = ''
    },
    remove () {
      let images = []
      this.bucket.filter(img => !img.choose).forEach(img => images.push(img.url))
      ajax().put(this.deleteApi, {
        images: images
      })
      .then(res => {
        this.edit = false
        this.$emit('updated', images)
        this.$modal.hide('delete-confirm')
      })
      .catch(err => {
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: `Unable to delete your images, please try a gain`
        })
      })
    },
    choose (img) {
      if (this.edit) img.choose=!img.choose
    }
  }
}
</script>
<style scoped>
  .photos-bucket {
    max-height: 400px;
    overflow-y: scroll;
  }
  .img-item {
    position: relative;
    width: 33.333333%;
    padding-top: 25%;
    border: 2px solid #fff;
    border-radius: 6px;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
  }
  .choose-cover {
    position: absolute;
    top:0px;
    left:0px;
    width:100%;
    height:100%;
    background-color:#00000060;
    align-items: center;
    justify-content: center;
    display: flex;
    opacity: 0;
  }
  .choose-cover.show {
    opacity: 1;
  }
</style>