<template>
  <div contain-box>
    <div page-section>
      <h4 class="row mt-2 p-2 text-white bg-primary" style="font-size: large">
        Mã QR và link thuê phòng dành cho người thuê trọ
      </h4>
      <div class="text-center py-5">
        <qrcode-vue :value="target" :size="80" level="H"></qrcode-vue>
        <div style="font-weight: 600">Mã QR thuê phòng</div>
      </div>
      <div class="py-3 text-center text-primary">
        hoặc
      </div>
      <div class="text-center py-5">
        <button style="width:80px;height: 80px" class="btn btn-outline-primary mb-2" @click="$modal.show('join-room-link')">
          <i class="fas fa-link"></i>
        </button>
        <div style="font-weight: 600">Link thuê phòng</div>
      </div>
    </div>
    <modal name="join-room-link" :width="400">
      <div class="p-2">
        <h4 class="mt-2">Link giúp người thuê nhập phòng</h4>
        <div class="c-flex-middle">
          <input class="form-control" style="width:calc(100% - 50px);margin-right: 5px" type="url" placeholder="Search" aria-label="Search" v-model="target">
          <button
            v-clipboard="target"
            v-clipboard:success="copySuccess"
            v-clipboard:error="copyError"
            class="btn text-success my-2 my-sm-0"
            type="button"
          >Copy</button>
        </div>
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
      return `${$auth.baseUrl}/thue/phong/${this.id}`
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