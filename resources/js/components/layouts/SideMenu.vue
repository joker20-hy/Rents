<template>
  <section id="side-menu" v-show="is_show" class="float-left" style="min-width: 300px;max-width: 300px;min-height: 100vh">
    <div style="height: 200px" class="bg-primary">
      joker
    </div>
    <div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">
          <router-link to="/users">User list</router-link>
        </li>
        <li class="list-group-item">
          <router-link to="/provinces">Province list</router-link>
        </li>
        <li class="list-group-item">
          <router-link to="/districts">District list</router-link>
        </li>
        <li class="list-group-item">
          <router-link to="/areas">Area list</router-link>
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
  methods: {
    show () {
      this.is_show = !this.is_show
    }
  }
}
</script>
