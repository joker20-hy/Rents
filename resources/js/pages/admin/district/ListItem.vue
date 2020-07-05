<template>
  <transition name="slide-fade">
    <tr>
      <td>
        <div v-show="!edit" class="holder">{{ district.name }}</div>
        <input type="text" class="input" v-model="district.name" v-show="edit">
      </td>
      <td>
        <div v-show="!edit" class="holder">{{ district.slug }}</div>
        <input type="text" class="input" v-model="district.slug" v-show="edit">
      </td>
      <td>
        <div class="holder">{{ district.province.name }}</div>
      </td>
      <td>
        <button class="btn text-primary" @click="enterEdit()" v-show="!edit">Edit</button>
        <button class="btn text-primary" v-show="edit" @click="update()">Update</button>
        <button class="btn text-danger" v-show="edit" @click="cancel()">Cancel</button>
        <button class="btn text-danger" @click="$emit('destroy', district)" v-show="!edit">Delete</button>
      </td>
    </tr>
  </transition>
</template>
<script>
import { $auth } from '../../../utilities/request/request'
export default {
  name: 'list-item',
  props: {
    district: {
      required: true,
      type: Object
    }
  },
  data () {
    return {
      edit: false,
      backup_district: {}
    }
  },
  methods: {
    enterEdit () {
      this.backup()
      this.edit = true  
    },
    backup () {
      this.backup_district.name = this.district.name
      this.backup_district.slug = this.district.slug
    },
    leaveEdit () {
      this.edit = false
    },
    restore () {
      this.district.name = this.backup_district.name
      this.district.slug = this.backup_district.slug
    },
    update () {
      $auth.request.put(`/api/district/${this.district.id}`, this.district)
      .then(res => {
        this.leaveEdit()
        this.$emit('success', 'District has been updated successfully')
      })
      .catch(err => {
        this.restore()
        this.$emit('error', 'Unable to update this district')
      })
    },
    cancel () {
      this.restore()
      this.leaveEdit()
    }
  }
}
</script>
<style scoped>
  td .input {
    text-align: center;
  }
</style>