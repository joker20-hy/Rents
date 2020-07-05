<template>
  <modal name="user-profile" class="p-2" :width="500" @before-close="leaveEdit">
    <h3 class="p-3 mb-0 d-flex text-primary">
      User profile
      <div class="ml-auto">
        <button type="button" class="btn" v-show="!edit" @click="enterEdit()">
          <i class="fas fa-pen"></i>
        </button>
        <button type="button" class="btn" @click="hide()">
          <i class="fas fa-times text-danger"></i>
        </button>
      </div>
    </h3>
    <div v-if="user.profile" class="profile-detail">
      <div class="form-group text-center">
        <img :src="user.profile.image==null?'/images/default.svg':user.profile.image" alt="">
        <br>
        <button type="button" class="btn" onclick="clickTarget('#profile-image')">
          <svg height="18" width="18" viewBox="0 -24 512.00046 512" xmlns="http://www.w3.org/2000/svg">
          <path d="m413.492188 128.910156c-17.292969-86.765625-101.648438-143.082031-188.414063-125.789062-63.460937 12.648437-113.082031 62.238281-125.769531 125.691406-61.519532 7.089844-105.648438 62.707031-98.5625 124.230469 6.523437 56.621093 54.480468 99.339843 111.476562 99.300781h80.09375c8.847656 0 16.019532-7.171875 16.019532-16.019531 0-8.847657-7.171876-16.019531-16.019532-16.019531h-80.09375c-44.238281-.261719-79.882812-36.332032-79.625-80.566407.261719-44.234375 36.332032-79.882812 80.570313-79.625 8.164062.003907 15.023437-6.140625 15.921875-14.253906 8.132812-70.308594 71.722656-120.710937 142.03125-112.578125 59.109375 6.839844 105.738281 53.464844 112.574218 112.578125 1.34375 8.257813 8.5 14.308594 16.867188 14.253906 44.238281 0 80.097656 35.859375 80.097656 80.097657 0 44.234374-35.859375 80.09375-80.097656 80.09375h-80.09375c-8.847656 0-16.019531 7.171874-16.019531 16.019531 0 8.847656 7.171875 16.019531 16.019531 16.019531h80.097656c61.925782-.386719 111.816406-50.898438 111.433594-112.828125-.351562-56.394531-42.53125-103.753906-98.507812-110.605469zm0 0"/>
          <path d="m313.019531 287.464844c6.148438 6.367187 16.289063 6.542968 22.652344.394531 6.363281-6.144531 6.539063-16.285156.394531-22.648437-.128906-.136719-.261718-.265626-.394531-.394532l-67.9375-67.953125c-6.246094-6.265625-16.390625-6.277343-22.65625-.03125-.007813.011719-.019531.019531-.027344.03125l-67.9375 67.953125c-6.363281 6.144532-6.539062 16.285156-.394531 22.648438 6.148438 6.363281 16.289062 6.539062 22.652344.394531.132812-.128906.265625-.261719.394531-.394531l40.609375-40.625v201.617187c0 8.847657 7.171875 16.019531 16.019531 16.019531 8.84375 0 16.015625-7.171874 16.015625-16.019531v-201.617187zm0 0"/></svg>
          Change
        </button>
        <input type="file" class="d-none" id="profile-image">
      </div>
      <form :class="edit ? 'edit': ''" @submit.prevent="update">
        <div class="form-group row">
          <div class="col-md-6">
            <label>Firstname</label>
            <div class="info">{{ user.profile.firstname }}</div>
            <input class="input" type="text" v-model="user.profile.firstname">
          </div>
          <div class="col-md-6">
            <label>Lastname</label>
            <div class="info">{{ user.profile.lastname }}</div>
            <input class="input" type="text" v-model="user.profile.lastname">            
          </div>
        </div>
        <div class="form-group">
          <label>Address</label>
          <div class="info">{{ user.profile.address }}</div>
          <input class="input" type="text" v-model="user.profile.address">
        </div>
        <div class="form-group">
          <label>Phone number</label>
          <div class="info">{{ user.profile.phone }}</div>
          <input class="input" type="text" v-model="user.profile.phone">
        </div>
        <div class="form-group">
          <label>Date of birth</label>
          <div class="info">{{ user.profile.date_of_birth }}</div>
          <input class="input" type="date" v-model="user.profile.date_of_birth">
        </div>
        <div class="form-group text-right" v-show="edit">
          <button type="button" class="btn btn-outline-danger" @click="cancel()">Cancel</button>
          <button class="btn btn-outline-primary">Update</button>
        </div>
      </form>
    </div>
  </modal>
</template>
<script>
import { $auth } from '../../../utilities/request/request'
export default {
  name: 'user-profile',
  data () {
    return {
      edit: false,
      user: {},
      profile_backup: {}
    }
  },
  mounted () {
    $eventHub.$on('show-user-profile', this.show)
  },
  methods: {
    show (user) {
      this.$modal.show('user-profile')
      this.user = user
    },
    hide () {
      this.$modal.hide('user-profile')
    },
    backup () {
      this.profile_backup = {
        firstname: this.user.profile.firstname,
        lastname: this.user.profile.lastname,
        address: this.user.profile.address,
        phone: this.user.profile.phone,
        date_of_birth: this.user.profile.date_of_birth
      }
    },
    enterEdit () {
      this.backup()
      this.edit = true
    },
    restore () {
      this.user.profile.firstname = this.profile_backup.firstname
      this.user.profile.lastname = this.profile_backup.lastname
      this.user.profile.address = this.profile_backup.address
      this.user.profile.phone = this.profile_backup.phone
      this.user.profile.date_of_birth = this.profile_backup.date_of_birth
    },
    cancel () {
      this.restore()
      this.leaveEdit()
    },
    leaveEdit () {
      this.edit = false
    },
    update () {
      $auth.request.put(`/api/user/${this.user.id}/profile`, this.user.profile)
      .then(res => {
        this.leaveEdit()
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'Update successfully',
          timeout: 3000
        })
      })
      .catch(err => {
        this.restore()
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to update profile'
        })
      })
    }
  }
}
</script>
<style scoped>
  label {
    font-weight: 600;
  }
  .profile-detail {
    padding: 15px;
    max-height: 80vh;
    overflow-y: scroll;
  }
  .profile-detail .info {
    padding: 8px 15px;
  }
  .profile-detail form.edit .info {
    display: none;
  }
  .profile-detail .input {
    display: none;
  }
  .profile-detail form.edit .input {
    display: block;
  }
</style>