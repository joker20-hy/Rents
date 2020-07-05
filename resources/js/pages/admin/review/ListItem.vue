<template>
  <transition name="slide-fade" tag="tbody" class="record-list">
    <tr>
      <td>{{ review.user.name }}</td>
      <td>{{ review.rate }}</td>
      <td>{{ review.receiver_id }}</td>
      <td>{{ review.anonymous }}</td>
      <td>
        <button class="btn text-primary" @click="detail()">Detail</button>
        <button class="btn text-danger" @click="$emit('destroy', review)">Delete</button>
      </td>
    </tr>
  </transition>
</template>
<script>
export default {
  name: 'list-item',
  props: {
    review: {
      required: true,
      type: Object
    }
  },
  data () {
    return {}
  },
  methods: {
    detail () {
      $eventHub.$emit('show-review-detail', this.review)
    }
  }
}
</script>