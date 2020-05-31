<template>
  <div class="container">
    <h1 class="d-flex align-items-end mt-5">
      User list
    </h1>
    <table class="table table-borderless text-center">
      <thead>
        <th>Username</th>
        <th>Role</th>
        <th>Rate</th>
        <th>Rate count</th>
        <th>Verify</th>
        <th>Actions</th>
      </thead>
      <transition-group name="slide-fade" tag="tbody">
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.name }}</td>
          <td>{{ roleName(user.role) }}</td>
          <td>{{ user.avg_rate }}</td>
          <td>{{ user.rate_count }}</td>
          <td>
            <switch-input :status="user.verify===verified" @click="changeVerifyStatus(user)"/>
          </td>
          <td>
            <button class="btn" @click="getProfile(user)">
              Profile
            </button>
          </td>
        </tr>
      </transition-group>
    </table>
    <paginate
      v-model="query_params.page"
      :page-count="page_count"
      :page-range="3"
      :margin-pages="2"
      :click-handler="changePage"
      :prev-text="'Prev'"
      :next-text="'Next'"
      :container-class="'pagination'"
      :page-class="'page-item'">
    </paginate>
    <user-profile :user="chosen"></user-profile>
  </div>
</template>
<script>
import { $auth } from '../../auth'
import SwitchInput from '../utilities/SwitchInput'
import UserProfile from '../layouts/UserProfile'

export default {
  name: 'user-list',
  components: {
    SwitchInput,
    UserProfile
  },
  data () {
    return {
      editProfile: false,
      query_params: {
        page: 1
      },
      page_count: 1,
      per_page: 1,
      chosen: {}
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
      return `/api/user?page=${this.query_params.page}`
    }
  },
  methods: {
    roleName (role) {
      return this.roleNames[role]
    },
    choose (user) {
      this.chosen = user
    },
    changeVerifyStatus (user) {
      this.$store.commit('users/changeVerify', user)
      $auth.request.put(`/api/user/${user.id}/verify`, {
        verify: user.verify
      })
      .then(res => {
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'Update verify status successfully',
          timeout: 3000
        })
      })
      .catch(err => {
        this.$store.commit('users/changeVerify', user)
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to update verify status'
        })
      })
    },
    changePage (page) {
      this.query_params.page = page
      this.list()
    },
    getProfile (user) {
      $auth.request.get(`/api/user/${user.id}/profile`)
      .then(res => {
        user.profile = res.data
        $eventHub.$emit('show-user-profile', user)
      })
      .catch(err => {
        let errMessage = err.response.data.message==undefined ? '' : err.response.data.message
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: errMessage
        })
      })
    },
    list () {
      $auth.request.get(this.listUrl)
      .then(res => {
        this.$store.commit('users/users', res.data.data)
        this.page_count = res.data.last_page
        this.total = res.data.total
      })
      .catch(err => {
        console.log(err)
      })
    }
  }
}
</script>
