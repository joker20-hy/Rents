<template>
  <transition name="slide-fade">
    <tr>
       <td>{{ room.name }}</td>
        <td>{{ room.address }}</td>
        <td>{{ room.avg_rate }}</td>
        <td>{{ room.rate_count }}</td>
        <td>
          <div>
            <button type="button" class="btn text-primary" @click="detail()">Detail</button>
            <button type="button" class="btn text-danger" @click="destroy()">Delete</button>
          </div>
        </td> 
    </tr>
  </transition>
</template>
<script>
export default {
  name: 'list-item',
  props: {
    room: {
      required: true,
      type: Object
    }
  },
  data () {
    return {}
  },
  methods: {
    detail () {
      $eventHub.$emit('show-room-detail', this.room)
    },
    destroy () {
      this.$emit('destroy', this.room.id)
    }
  }
}
</script>
<style scoped>
  td {
    padding: 10px 0px;
  }
</style>