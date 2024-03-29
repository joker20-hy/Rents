<template>
  <div class="container">
    <h1 class="d-flex align-items-end mt-5 py-3">
      User list
    </h1>
    <table class="records-list">
      <thead>
        <th>Username</th>
        <th>Role</th>
        <th>Rate</th>
        <th>Rate count</th>
        <th>Verify</th>
        <th>Actions</th>
      </thead>
      <tbody>
        <list-item v-for="user in users" :key="user.id" :user="user"/>
      </tbody>
    </table>
    <div class="py-4 text-center mx-auto" v-if="loading">
      <span class="p-2 bg-light rounded-circle">
        <i class="fas fa-spinner fa-pulse fa-lg text-primary"></i>
      </span>
    </div>  
    <paginate
      v-model="query.page"
      :page-count="page_count"
      :page-range="3"
      :margin-pages="2"
      :click-handler="changePage"
      :prev-text="'Prev'"
      :next-text="'Next'"
      :container-class="'pagination'"
      :page-class="'page-item'">
    </paginate>
    <user-profile/>
    <user-setting/>
  </div>
</template>
<script>
import { $auth } from '../../../auth'
import ListItem from './ListItem'
import UserProfile from './Profile'
import UserSetting from './Setting'

export default {
  name: 'user-list',
  components: {
    ListItem,
    UserProfile,
    UserSetting
  },
  data () {
    return {
      query: {
        page: 1
      },
      page_count: 1,
      per_page: 1,
      loading: false
    }
  },
  mounted () {
    this.list()
  },
  computed: {
    users () {
      return this.$store.getters['users/users']
    },
    verified () {
      return this.$store.getters['users/verified']
    },
    roleNames () {
      return this.$store.getters['users/roleNames']
    },
    listUrl () {
      return `/api/user?page=${this.query.page}`
    }
  },
  methods: {
    changePage (page) {
      this.query.page = page
      this.list()
    },
    list () {
      this.loading = true
      $auth.request.get(this.listUrl)
      .then(res => {
        this.loading = false
        this.$store.commit('users/users', res.data.data)
        this.page_count = res.data.last_page
      })
      .catch(err => {
        this.loading = false
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to get user list'
        })
      })
    }
  }
}
</script>
