<template>
  <section admin-side-menu id="side-menu" v-show="is_show">
    <header>
      <span class="text-center">
        <img class="avatar" v-if="auth.profile" :src="auth.profile.image==null?'/images/default.svg':auth.profile.image" alt=""/>
      <br> {{ auth.name }}
     </span>
    </header>
    <div>
      <ul class="list-group list-group-flush">
        <router-link :to="{name : 'user-list'}" item>
          <i class="fas fa-users"></i> Users
        </router-link>
        <router-link :to="{name : 'province-list'}" item>
          <i class="fas fa-city"></i> Provinces
        </router-link>
        <router-link :to="{name : 'district-list'}" item>
          <i class="fas fa-map-marker-alt"></i> Districts
        </router-link>
        <router-link :to="{name : 'area-list'}" item>
          <i class="fas fa-map-signs"></i> Areas
        </router-link>
        <router-link :to="{name : 'direction-list'}" item>
          <i class="fas fa-directions"></i> Directions
        </router-link>
        <router-link :to="{name : 'house-list'}" item>
          <i class="fab fa-houzz"></i> Houses
        </router-link>
        <router-link :to="{name : 'room-list'}" item>
          <i class="fas fa-door-open"></i> Rooms
        </router-link>
        <router-link :to="{name : 'service-list'}" item>
          <i class="fas fa-poll-h"></i> Services
        </router-link>
        <router-link :to="{name: 'criteria-list'}" item>
          <i class="fas fa-check"></i> Criterias
        </router-link>
        <router-link :to="{name: 'review-list'}" item>
          <i class="fas fa-user-edit"></i> Reviews
        </router-link>
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
