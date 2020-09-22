<template>
  <transition name="slide-fade">
    <div page-section>
      <h4 class="text-primary" v-show="!edit">
        {{ item.name }}
      </h4>
      <input type="text" class="input" v-model="item.name" v-show="edit">
      <div v-show="!edit">
        Số tài khoản: {{ item.account }}
      </div>
      <input type="number" class="input" v-model="item.account" v-show="edit">
      <div v-show="!edit">
        Ghi chú: {{ item.note }}
      </div>
      <textarea class="input" v-model="item.note" v-show="edit"></textarea>
      <div class="text-right">
        <button class="btn text-primary ml-auto" v-show="!edit" @click="enterEdit()">
          <i class="fas fa-pencil"></i>
        </button>
        <button class="btn text-primary ml-auto" v-show="edit" @click="update()">
          <i class="fas fa-save"></i> Lưu
        </button>
        <button class="btn text-danger ml-auto" v-show="edit" @click="leaveEdit()">
          <i class="fas fa-times"></i> Hủy
        </button>
        <button class="btn text-danger ml-auto" v-show="!edit" @click="$emit('destroy', item.id)">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    </div>
  </transition>
</template>
<script>
export default {
  name: 'list-item',
  props: {
    item: {
      required: true,
      type: Object
    }
  },
  data () {
    return {
      edit: false,
      backup_data: {}
    }
  },
  methods: {
    backup () {
      this.backup_data.name = this.item.name
      this.backup_data.account = this.item.account
      this.backup_data.note = this.item.note
    },
    enterEdit() {
      this.backup()
      this.edit = true
    },
    restore () {
      this.item.name = this.backup_data.name
      this.item.account = this.backup_data.account
      this.item.note = this.backup_data.note
    },
    leaveEdit () {
      this.restore()
      this.edit = false
    },
    update () {
      $eventHub.$emit('on-loading')
      ajax().put(`/api/pay-method/${item.id}`, this.item)
      .then(res => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Phương thức thanh toán đã được cập nhật thành công',
          timeout: 3000
        })
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Phương thức thanh toán chưa thể cập nhật, hãy thử lại sau',
          timeout: 3000
        })
      })
    }
  }
}
</script>