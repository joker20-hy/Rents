<template>
  <transition name="slide-fade">
    <tr>
      <td>
        <div v-show="!edit" class="holder">{{ province.name }}</div>
        <input type="text" class="input" v-model="province.name" v-show="edit">
      </td>
      <td>
        <div v-show="!edit" class="holder">{{ province.slug }}</div>
        <input type="text" class="input" v-model="province.slug" v-show="edit">
      </td>
      <td>
        <button class="btn text-primary" @click="enterEdit()" v-show="!edit">Sửa</button>
        <button class="btn text-primary" v-show="edit" @click="update()">Lưu</button>
        <button class="btn text-danger" v-show="edit" @click="cancel()">Hủy</button>
        <button class="btn text-danger" @click="$emit('destroy', province)" v-show="!edit">Xóa</button>
      </td>
    </tr>
  </transition>
</template>
<script>
export default {
  name: 'list-item',
  props: {
    province: {
      required: true,
      type: Object
    }
  },
  data () {
    return {
      edit: false,
      backup_province: {}
    }
  },
  methods: {
    enterEdit () {
      this.backup()
      this.edit = true
    },
    backup () {
      this.backup_province.name = this.province.name
      this.backup_province.slug = this.province.slug
    },
    restore () {
      this.province.name = this.backup_province.name
      this.province.slug = this.backup_province.slug
    },
    leaveEdit () {
      this.edit = false
    },
    cancel () {
      this.restore()
      this.leaveEdit()
    },
    update () {
      ajax().put(`/api/province/${this.province.id}`, this.province)
      .then(res => {
        this.edit = false
        this.$emit('success', 'The province has been updated successfully')
      })
      .catch(err => {
        this.restore()
        this.$emit('error', 'Unable to update the province')
      })
    }
  }
}
</script>
<style scoped>
  td .input {
    text-align: center;
  }
</style>