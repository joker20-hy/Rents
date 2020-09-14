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
        <button class="btn text-primary ml-auto" v-show="!edit" @click="edit=true">
          <i class="fas fa-pencil"></i>
        </button>
        <button class="btn text-primary ml-auto" v-show="edit" @click="update()">
          <i class="fas fa-save"></i> Lưu
        </button>
        <button class="btn text-danger ml-auto" v-show="edit" @click="edit=false">
          <i class="fas fa-times"></i> hủy
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
      edit: false
    }
  },
  methods: {
    update () {
      //
    }
  }
}
</script>