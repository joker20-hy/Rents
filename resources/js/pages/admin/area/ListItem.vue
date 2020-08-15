<template>
  <transition name="slide-fade">
    <tr>
      <td>
        <div class="holder" v-show="!edit">{{ area.name }}</div>
        <input type="text" class="input" v-model="area.name" v-show="edit">
      </td>
      <td>
        <div class="holder" v-show="!edit">{{ area.slug }}</div>
        <input type="text" class="input" v-model="area.slug" v-show="edit">
      </td>
      <td>
        <div class="holder">{{ area.province.name }}</div>
      </td>
      <td>
        <div class="holder">{{ area.district.name }}</div>
      </td>
      <td>
        <button class="btn text-primary" @click="enterEdit()" v-show="!edit">Edit</button>
        <button class="btn text-primary" v-show="edit" @click="update()">Update</button>
        <button class="btn text-danger" v-show="edit" @click="cancel()">Cancel</button>
        <button class="btn text-danger" v-show="!edit" @click="$emit('destroy', area)">Delete</button>
      </td>
    </tr>
  </transition>
</template>
<script>
export default {
  name: 'list-item',
  props: {
    area: {
      required: true,
      type: Object
    }
  },
  data () {
    return {
      edit: false,
      backup_area: {}
    }
  },
  methods: {
    enterEdit () {
      this.backup()
      this.edit = true
    },
    leaveEdit () {
      this.edit = false
    },
    cancel () {
      this.restore()
      this.leaveEdit()
    },
    backup () {
      this.backup_area.name = this.area.name
      this.backup_area.slug = this.area.slug
    },
    restore () {
      this.area.name = this.backup_area.name
      this.area.slug = this.backup_area.slug
    },
    update () {
      $request.put(`/api/area/${this.area.id}`, {
        name: this.area.name,
        slug: this.area.slug
      })
      .then(res => {
        this.edit = false
        this.$emit('success', 'The area has been updated successfully')
      })
      .catch(err => {
        this.restore()
        this.$emit('error', 'Unable to update the area')
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