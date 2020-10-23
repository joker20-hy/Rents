<template>
  <transition name="slide-fade">
    <tr>
      <td>{{ user.name }}</td>
      <td>{{ roleName }}</td>
      <td>{{ user.avg_rate }}</td>
      <td>{{ user.rate_count }}</td>
      <td>
        <switch-input class="m-auto" :status="user.verify===verified" @click="verifyStatus()"/>
      </td>
      <td>
        <span class="text-primary mx-2" @click="profile()" style="cursor:pointer">
          Profile
        </span>
        <span class="text-primary mx-2" @click="setting()" style="cursor:pointer">
          Settings
        </span>
      </td>
    </tr>
  </transition>
</template>
<script>
import SwitchInput from '../../utilities/SwitchInput'
export default {
  name: 'list-item',
  components: {
    SwitchInput
  },
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
  methods: {
    profile () {
      $eventHub.$emit('show-user-profile', this.user)
    },
    setting () {
      $eventHub.$emit('show-user-setting', this.user)
    },
    verifyStatus () {
      this.$store.commit('users/changeVerify', this.user)
      ajax().put(`/api/user/${this.user.id}/verify`, {
        verify: this.user.verify
      })
      .then(res => {
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'Update verify status successfully',
          timeout: 3000
        })
      })
      .catch(err => {
        this.$store.commit('users/changeVerify', this.user)
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to update verify status'
        })
      })
    }
  }
}
</script>