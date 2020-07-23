<template>
  <transition name="slide-fade">
    <div list-item>
      <div img-wrap>
        <img src="/images/photo.svg" :alt="house.name">
      </div>
      <div class="pl-2">
        <div class="text-bold">{{ house.name }}</div>
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
        <router-link :to="{name: 'owner-create-room', params: {house_id: house.id}}" class="text-success" tool title="Thêm phòng">
          <i class="fas fa-plus"></i> Thêm phòng
        </router-link>
        <router-link :to="{name: 'owner-list-room', params: {house_id: house.id}}" class="text-primary" tool title="Danh sách phòng trọ">
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
  methods: {
    destroy () {
      this.$emit('destroy', this.house.id)
    }
  }
}
</script>
