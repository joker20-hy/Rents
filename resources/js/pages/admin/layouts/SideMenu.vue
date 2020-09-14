<template>
  <section admin-side-menu id="side-menu" v-show="is_show">
    <header>
      <span class="text-center">
        <img class="avatar" v-if="auth.profile" :src="auth.profile.image==null?'/images/default.svg':auth.profile.image" alt=""/>
      <br>
      {{ auth.name }}
     </span>
    </header>
    <div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">
          <router-link :to="{name : 'user-list'}">Users</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name : 'province-list'}">Provinces</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name : 'district-list'}">Districts</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name : 'area-list'}">Areas</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name : 'direction-list'}">Directions</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name : 'house-list'}">Houses</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name : 'room-list'}">Rooms</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name : 'service-list'}">Services</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name: 'criteria-list'}">Criterias</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name: 'review-list'}">Reviews</router-link>
        </li>
      </ul>
    </div>
  </section>
</template>
<script>
export default {
  name: 'side-menu',
  data () {
    return {
      is_show: true
    }
  },
  created () {
    $eventHub.$on('show-sidemenu', this.show)
  },
  computed: {
    auth () {
      return this.$store.getters['auth/user']
    }
  },
  methods: {
    show () {
      this.is_show = !this.is_show
    }
  }
}
</script>
