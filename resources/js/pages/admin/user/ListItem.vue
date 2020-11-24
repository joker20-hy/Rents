<template>
  <transition name="slide-fade">
    <tr>
      <td>{{ user.name }}</td>
      <td>{{ roleName }}</td>
      <td>{{ user.avg_rate }}</td>
      <td>{{ user.rate_count }}</td>
      <td>
        <router-link :to="{name: 'user-detail', params:{id: user.id}}">Chi tiết</router-link>
      </td>
    </tr>
  </transition>
</template>
<script>
export default {
  props: {
  	user: {
  	  required: true,
  	  type: Object
  	}
  },
  computed: {
  	roleName () {
      return this.$store.getters['users/roleNames'][this.user.role]
    },
    verified () {
      return this.$store.getters['users/verified']
    },
    unverified () {
      return this.$store.getters['users/unverified']
    }
  },
  data () {
  	return {}
  },
  methods: {}
}
</script>