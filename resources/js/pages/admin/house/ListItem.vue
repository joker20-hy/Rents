<template>
  <transition name="slide-fade">
    <tr>
      <td>{{ house.address }}</td>
      <td>{{ house.avg_rate }}</td>
      <td>{{ house.rate_count }}</td>
<!--       <td>{{ house.rent?'Yes':'No' }}</td> -->
      <td>
        <div>
          <router-link class="btn text-primary" :to="{name: 'house-detail', params: {id: house.id}}">Chi tiết</router-link>
          <button class="btn text-danger" @click="destroy()">Xóa</button>
        </div>
      </td>
    </tr>
  </transition>
</template>
<script>
export default {
  name: 'list-item',
  props: {
    house: {
      required: true,
      type: Object
    }
  },
  data () {
    return {}
  },
  methods: {
    detail () {
      $eventHub.$emit('show-detail-house', this.house)
    },
    destroy () {
      this.$emit('destroy', this.house.id)
    }
  }
}
</script>