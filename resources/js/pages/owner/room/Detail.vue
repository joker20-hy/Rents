<template>
  <div class="container mt-3 mb-5">
    <form v-if="room" @submit.prevent="update()">
      <h3 class="d-flex sticky-top bg-white text-primary p-2 mb-0 align-items-center" style="font-size: large;z-index: 10" v-if="room">
        {{ `${room.name}` }}
        <div class="ml-auto">
          <button type="button" class="btn text-primary" @click="enterEdit()" v-show="!edit">Chỉnh sửa</button>
          <button class="btn text-primary" v-show="edit">Cập nhật</button>
          <button type="button" class="btn text-danger" v-show="edit" @click="leaveEdit()">Hủy</button>
        </div>
      </h3>
      <image-library
        class="form-group pt-3"
        :images="room.images"
        :editable="edit"
        :updateApi="`/api/room/${id}/images`"
        :deleteApi="`/api/room/${id}/images`"
        @updated="getImages"/>
        <div class="form-group">
          <label for="">Tên phòng</label>
          <div class="holder" v-show="!edit">{{ room.name }}</div>
          <input type="text" class="input" v-model="room.name" v-show="edit" placeholder="Room name" required>
        </div>
        <div class="form-group" v-show="!edit">
          <label>Số người thuê phòng này: </label>
          <div class="holder">{{ room.renters_count }}</div>
        </div>
        <div class="form-group">
          <label for="">Diện tích</label>
          <div class="holder" v-show="!edit">{{ room.acreage }} m2</div>
          <div class="align-items-end" :class="edit?'d-flex':'d-none'">
            <input type="number" class="input" v-model="room.acreage" placeholder="Room acreage" required><span class="px-3">m2</span>
          </div>
        </div>
        <div class="form-group">
          <label for="">Giá</label>
          <div class="holder" v-show="!edit">{{ room.price }} vnđ</div>
          <div class="align-items-end" :class="edit?'d-flex':'d-none'">
            <input type="number" class="input" v-model="room.price" placeholder="Room price" required><span class="px-3">vnđ</span>
          </div>
        </div>
        <div class="form-group">
          <label for="">Chu kỳ đóng tiền</label>
          <div class="holder" v-show="!edit">{{ room.cycle }} tháng</div>
          <div class="align-items-end" :class="edit?'d-flex':'d-none'">
            <input type="number" class="input" v-model="room.cycle" placeholder="Pay cycle" required><span class="px-3">tháng</span>
          </div>
        </div>
        <label>Tiêu chí của phòng trọ <span class="text-danger" title="Required feild">*</span></label>
        <criteria-list :criterias="criterias"></criteria-list>
        <div class="form-group">
          <label for="">Mô tả</label>
          <div class="holder content-html" v-show="!edit" v-html="room.description"></div>
          <div v-show="edit">
            <ckeditor :editor="editor" v-model="room.description" :config="editorConfig"></ckeditor>
          </div>
        </div>
    </form>
  </div>
</template>
<script>
import ImageLibrary from '../../utilities/ImageLibrary'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import adapter from '../../../utilities/CKImageAdapter'
import CriteriaList from './Criterias'

export default {
  components: {
    ImageLibrary,
    CriteriaList
  },
  data () {
    return {
      id: this.$route.params.id,
      edit: false,
      editor: ClassicEditor,
      editorConfig: {
        extraPlugins: [ adapter ],
      }
    }
  },
  watch: {
    "$route.params.id": {
      handler (id) {
        this.id = id
        if (this.room==undefined) this.getRoom()
        this.getCriterias()
      },
      deep: true,
      immediate: true
    }
  },
  computed: {
    room () {
      return this.$store.getters['rooms/find'](this.id)
    },
    criterias () {
      let criterias = this.$store.getters['criterias/criterias']
      if (criterias.length > 0) {
        criterias.forEach(cri => {
          cri.checked = this.criteriaIds.find(item => item.id==cri.id)!=undefined
        })
      }
      return criterias
    },
    criteriaIds () {
      return select(this.room.criterias, ['id'])
    },
    getData () {
      let criterias = []
      this.criterias.forEach(cri => cri.checked?criterias.push(cri.id):'')
      return {
        id: this.room.id,
        name: this.room.name,
        price: this.room.price,
        acreage: this.room.acreage,
        description: this.room.description,
        cycle: this.room.cycle,
        images: this.room.images,
        criterias: criterias
      }
    }
  },
  methods: {
    enterEdit () {
      this.edit = true
    },
    leaveEdit () {
      this.edit = false
    },
    getImages (images) {
      this.room.images = images
    },
    getRoom() {
      $eventHub.$emit('on-loading')
      ajax().get(`/api/room/${this.id}`)
      .then(res => {
        res.data.images = JSON.parse(res.data.images)
        res.data.description = utf8.decode(res.data.description)
        this.$store.commit('rooms/rooms', [res.data])
        $eventHub.$emit('off-loading')
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        console.log(err.response.data)
      })
    },
    getCriteria (criteria) {
      this.criterias[criteria.index].checked = criteria.checked
    },
    getCriterias () {
      ajax().get('/api/criteria')
      .then(res => {
        res.data.forEach(cri => cri.checked = false)
        this.$store.commit('criterias/criterias', res.data)
      })
      .catch(err => console.log(err.response.data) )
    },
    update () {
      $eventHub.$emit('on-loading')
      ajax().put(`/api/room/${this.room.id}`, this.getData)
      .then(res => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'Update info of this room successfully'
        })
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to update info of this room'
        })
      })
    }
  }
}
</script>