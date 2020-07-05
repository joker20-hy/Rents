<template>
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm pl-0">
        <button class="btn text-primary mx-2" @click="toggleSidebar()">
            <i class="fas fa-ellipsis-v"></i>
        </button>

        <router-link class="navbar-brand text-primary" to="/">Rent</router-link>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto"></ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <button class="btn font-weight-bold text-primary" @click="logout()">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </ul>
        </div>
    </nav>
</template>
<script>
export default {
  name: 'header-bar',
  data () {
    return {}
  },
  methods: {
    toggleSidebar () {
      $eventHub.$emit('show-sidemenu')
    },
    logout () {
      $eventHub.$emit('show-logout-form')
    }
  }
}
</script>
