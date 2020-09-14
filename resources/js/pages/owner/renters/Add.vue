<template>
  <div contain-box>
    <div class="my-3 py-2 text-white text-center bg-primary">
      Hãy gửi mã QR hoặc link thuê phòng này tới những nghời thuê trọ
    </div>
    <div class="row mx-0">
      <div class="col-6 text-center">
        <qrcode-vue :value="target" :size="80" level="H"></qrcode-vue>
        <div style="font-weight: 600">Mã QR thuê phòng</div>
      </div>
      <div class="col-6 text-center">
        <button style="width:80px;height: 80px" class="btn btn-outline-primary mb-2" @click="$modal.show('join-room-link')">
          <i class="fas fa-link"></i>
        </button>
        <div style="font-weight: 600">Link thuê phòng</div>
      </div>
    </div>
    <modal name="join-room-link" :width="360">
      <div class="p-2">
        <h4 class="mt-2 pb-3">Link giúp người thuê nhập phòng</h4>
        <form class="form-inline my-2 my-lg-0">
          <input class="form-control mr-sm-2" type="url" placeholder="Search" aria-label="Search" v-model="target">
          <button
            v-clipboard="target"
            v-clipboard:success="copySuccess"
            v-clipboard:error="copyError"
            class="btn btn-outline-success my-2 my-sm-0"
            type="button"
          >Copy</button>
        </form>
      </div>
    </modal>
  </div>
</template>
<script>
import QrcodeVue from 'qrcode.vue'
export default {
  components: {
    QrcodeVue
  },
  data () {
    return {
      id: this.$route.params.room
    }
  },
  computed: {
    target () {
      return `${$auth.baseUrl}/thue-phong/${this.id}`
    }
  },
  methods: {
    copySuccess () {
      $eventHub.$emit('success-alert', {
        title: 'Thành công',
        message: 'Đã copy link thành công',
        timeout: 3000
      })
    },
    copyError () {
      $eventHub.$emit('error-alert', {
        title: 'Đã có lỗi',
        message: 'Không thể copy link, hãy thử lại'
      })
    }
  }
}
</script>