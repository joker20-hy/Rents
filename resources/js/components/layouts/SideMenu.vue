<template>
  <section id="side-menu" v-show="is_show" class="float-left" style="min-width: 300px;max-width: 300px;min-height: 100vh">
    <div style="height: 200px;display: flex;align-items: flex-end;justify-content: center;padding-bottom:15px">
     <span class="text-center">
      <img v-if="auth.profile" :src="auth.profile.image==null?'/images/default.svg':auth.profile.image" alt=""/>
      <br>
      {{ auth.name }}
     </span>
    </div>
    <div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">
          <router-link :to="{name : 'user-list'}">User list</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name : 'province-list'}">Province list</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name : 'district-list'}">District list</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name : 'area-list'}">Area list</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name : 'direction-list'}">Direction list</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name : 'house-list'}">House list</router-link>
        </li>
        <li class="list-group-item">
          <router-link :to="{name : 'room-list'}">Room list</router-link>
        </li>
      </ul>
    </div>
  </section>
</template>
<script>
import { $auth } from '../../auth'

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
