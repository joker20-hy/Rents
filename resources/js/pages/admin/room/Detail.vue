<template>
  <modal name="room-detail" :width="720">
    <form @submit.prevent="update()" style="max-height: 80vh;overflow-y: scroll;overflow-x: hidden;">
      <h3 class="d-flex sticky-top bg-white text-primary p-3 mb-0">
        Room detail
        <div class="ml-auto">
          <button type="button" class="btn text-primary" @click="enterEdit()" v-show="!edit">Edit</button>
          <button class="btn text-primary" v-show="edit">Update</button>
          <button type="button" class="btn text-danger" v-show="edit" @click="leaveEdit()">Cancel</button>
          <button type="button" class="btn text-danger" @click="$modal.hide('room-detail')">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </h3>
      <div class="px-2 pb-2">
        <image-library class="form-group" :images="room.images" :updateApi="`/api/room/${room.id}/images`" :deleteApi="`/api/room/${room.id}/images`" @updated="updateImages"/>
        <div class="form-group">
          <label for="">Room name</label>
          <div class="holder" v-show="!edit">{{ room.name }}</div>
          <input type="text" class="input" v-model="room.name" v-show="edit" placeholder="Room name" required>
        </div>
        <div class="form-group">
          <label for="">Acreage</label>
          <div class="holder" v-show="!edit">{{ room.acreage }} m2</div>
          <div class="align-items-end" :class="edit?'d-flex':'d-none'">
            <input type="number" class="input" v-model="room.acreage" placeholder="Room acreage" required><span class="px-3">m2</span>
          </div>
        </div>
        <div class="form-group">
          <label for="">Price</label>
          <div class="holder" v-show="!edit">{{ room.price }} vnđ</div>
          <div class="align-items-end" :class="edit?'d-flex':'d-none'">
            <input type="number" class="input" v-model="room.price" placeholder="Room price" required><span class="px-3">vnđ</span>
          </div>
        </div>
        <div class="form-group">
          <label for="">Cycle</label>
          <div class="holder" v-show="!edit">{{ room.cycle }} tháng</div>
          <div class="align-items-end" :class="edit?'d-flex':'d-none'">
            <input type="number" class="input" v-model="room.cycle" placeholder="Pay cycle" required><span class="px-3">tháng</span>
          </div>
        </div>
        <div class="form-group">
          <label for="">Description</label>
          <div class="holder content-html" v-show="!edit" v-html="room.description"></div>
          <div v-show="edit">
            <ckeditor :editor="editor" v-model="room.description" :config="editorConfig"></ckeditor>
          </div>
        </div>
      </div>
    </form>
  </modal>
</template>
<script>
import ImageLibrary from '../../utilities/ImageLibrary'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import adapter from '../../../utilities/CKImageAdapter'
export default {
  name: 'room-detail',
  components: {
    ImageLibrary
  },
  mounted () {
    $eventHub.$on('show-room-detail', this.show)
  },
  data () {
    return {
      edit: false,
      editor: ClassicEditor,
      editorConfig: {
        extraPlugins: [ adapter ],
      },
      room: {},
      room_backup: {},
      errors: {}
    }
  },
  methods: {
    show (room) {
      this.edit = false
      this.room = room
      this.backup(room)
      this.$modal.show('room-detail')
    },
    backup (room) {
      this.room_backup.name = room.name
      this.room_backup.acreage = room.acreage
      this.room_backup.description = room.description
      this.room_backup.price = room.price
      this.room_backup.cycle = room.cycle
    },
    restore () {
      this.room.name = this.room_backup.name
      this.room.acreage = this.room_backup.acreage
      this.room.description = this.room_backup.description
      this.room.price = this.room_backup.price
      this.room.cycle = this.room_backup.cycle
    },
    enterEdit () {
      this.edit = true
    },
    leaveEdit () {
      this.edit = false
    },
    updateImages (images) {
      this.room.images = images
    },
    update () {
      ajax().put(`/api/room/${this.room.id}`, this.room)
      .then(res => {
        this.leaveEdit()
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'Update info of this room successfully'
        })
      })
      .catch(err => {
        this.restore()
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to update info of this room'
        })
      })
    }
  }
}
</script>
<style scoped>
  label {
    font-weight: 600;
  }
</style>