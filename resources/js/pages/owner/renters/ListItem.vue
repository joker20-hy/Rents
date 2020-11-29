<template>
  <transition name="slide-fade">
    <div page-section>
      <div><i class="fas fa-user"></i> {{ item.name }}</div>
      <div>Email: {{ item.email }}</div>
      <div v-if="item.profile">Số điện thoại: {{ item.profile.phone?item.profile.phone:'Chưa rõ' }}</div>
      <div v-if="item.profile">Ngày sinh: {{ date_of_birth }}</div>
      <div class="text-right">
        <router-link v-if="!reviewed" :to="{name: 'review-renter', params: {id: item.id}}" class="btn text-primary">Đánh giá</router-link>
        <button v-else class="btn text-primary" disabled>Bạn đã đánh giá</button>
        <button class="btn text-danger" @click="$emit('remove', item)">Rời phòng</button>
      </div>
    </div>
  </transition>
</template>
<script>
export default {
  props: {
    item: {
      required: true,
      type: Object
    }
  },
  data () {
    return {}
  },
  computed: {
    date_of_birth() {
      return this.item.profile.date_of_birth.split('-').reverse().join('/')
    },
    reviewed() {
      for (let review of this.item.review_renter) {
        if (review.pivot.renter_id==this.item.id) return true
      }
      return false
    }
  },
  methods: {
    
  }
}
</script>