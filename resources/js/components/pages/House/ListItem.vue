<template>
  <transition name="slide-fade">
    <tr>
      <td>{{ house.address }}</td>
      <td>{{ house.avg_rate }}</td>
      <td>{{ house.rate_count }}</td>
      <td>{{ house.rent?'Yes':'No' }}</td>
      <td>
        <div>
          <button class="btn text-primary" @click="detail()">detail</button>
          <button class="btn text-danger" @click="destroy()">delete</button>
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