<template>
  <transition name="slide-fade">
    <div list-item>
      <div img-wrap :style="`background-image: url(${avatar})`">
      </div>
      <div class="pl-2">
        <div class="text-bold">
          <router-link :to="{name: 'owner-detail-house', params: {id: house.id}}" class="text-info" tool title="Chi tiết">
            {{ house.name }}
          </router-link>
        </div>
        <div>
          <small>{{ house.address_detail }}</small>
        </div>
        <div>
          <span v-if="house.rent"></span>
        </div>
      </div>
      <div tool-wrap>
        <router-link :to="{name: 'owner-detail-house', params: {id: house.id}}" class="text-info" tool title="Chi tiết">
          <i class="fas fa-info-circle"></i> Chi tiết
        </router-link>
        <router-link :to="{name: 'owner-list-room', params: {id: house.id}}" class="text-primary" tool title="Danh sách phòng trọ">
          <i class="fas fa-list-ul"></i> Phòng trọ
        </router-link>
        <button class="text-danger" tool title="Xóa" @click="destroy()">
          <i class="far fa-trash-alt"></i> Xóa
        </button>
      </div>
    </div>
  </transition>
</template>
<script>
export default {
  props: {
    house: {
      required: true,
      type: Object
    }
  },
  name: 'list-item',
  data () {
    return {}
  },
  computed: {
    avatar () {
      return this.house.images.length>0?this.house.images[0]:'/images/default.png'
    }
  },
  methods: {
    destroy () {
      this.$emit('destroy', this.house.id)
    }
  }
}
</script>
