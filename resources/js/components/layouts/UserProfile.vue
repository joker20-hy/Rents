<template>
  <modal name="user-profile" class="user-profile top0" :width="500">
    <h3 class="px-3 pt-4 pb-1 d-flex text-primary">
      User profile
      <button type="button" class="btn ml-auto" @click="toggleEdit()">
        <i :class="edit ? 'fas fa-times text-danger' : 'fas fa-pen'"></i>
      </button>
    </h3>
    <form v-if="owner.profile" class="profile-detail" :class="edit ? 'edit': ''" @submit.prevent="update">
      <div class="form-group text-center">
        <img v-if="owner.profile" :src="owner.profile.image" alt="">
        <br>
        <button type="button" class="btn">
          <svg height="18" width="18" viewBox="0 -24 512.00046 512" xmlns="http://www.w3.org/2000/svg">
          <path d="m413.492188 128.910156c-17.292969-86.765625-101.648438-143.082031-188.414063-125.789062-63.460937 12.648437-113.082031 62.238281-125.769531 125.691406-61.519532 7.089844-105.648438 62.707031-98.5625 124.230469 6.523437 56.621093 54.480468 99.339843 111.476562 99.300781h80.09375c8.847656 0 16.019532-7.171875 16.019532-16.019531 0-8.847657-7.171876-16.019531-16.019532-16.019531h-80.09375c-44.238281-.261719-79.882812-36.332032-79.625-80.566407.261719-44.234375 36.332032-79.882812 80.570313-79.625 8.164062.003907 15.023437-6.140625 15.921875-14.253906 8.132812-70.308594 71.722656-120.710937 142.03125-112.578125 59.109375 6.839844 105.738281 53.464844 112.574218 112.578125 1.34375 8.257813 8.5 14.308594 16.867188 14.253906 44.238281 0 80.097656 35.859375 80.097656 80.097657 0 44.234374-35.859375 80.09375-80.097656 80.09375h-80.09375c-8.847656 0-16.019531 7.171874-16.019531 16.019531 0 8.847656 7.171875 16.019531 16.019531 16.019531h80.097656c61.925782-.386719 111.816406-50.898438 111.433594-112.828125-.351562-56.394531-42.53125-103.753906-98.507812-110.605469zm0 0"/>
          <path d="m313.019531 287.464844c6.148438 6.367187 16.289063 6.542968 22.652344.394531 6.363281-6.144531 6.539063-16.285156.394531-22.648437-.128906-.136719-.261718-.265626-.394531-.394532l-67.9375-67.953125c-6.246094-6.265625-16.390625-6.277343-22.65625-.03125-.007813.011719-.019531.019531-.027344.03125l-67.9375 67.953125c-6.363281 6.144532-6.539062 16.285156-.394531 22.648438 6.148438 6.363281 16.289062 6.539062 22.652344.394531.132812-.128906.265625-.261719.394531-.394531l40.609375-40.625v201.617187c0 8.847657 7.171875 16.019531 16.019531 16.019531 8.84375 0 16.015625-7.171874 16.015625-16.019531v-201.617187zm0 0"/></svg>
          Change
        </button>
      </div>
      <div class="form-group row">
        <div class="col-md-6">
          <label>Firstname</label>
          <div class="info">{{ owner.profile.firstname }}</div>
          <input class="input" type="text" v-model="owner.profile.firstname">
        </div>
        <div class="col-md-6">
          <label>Lastname</label>
            <div class="info">{{ owner.profile.lastname }}</div>
            <input class="input" type="text" v-model="owner.profile.lastname">            
        </div>
      </div>
      <div class="form-group">
        <label>Address</label>
        <div class="info">{{ owner.profile.address }}</div>
        <input class="input" type="text" v-model="owner.profile.address">
      </div>
      <div class="form-group">
        <label>Phone number</label>
        <div class="info">{{ owner.profile.phone }}</div>
        <input class="input" type="text" v-model="owner.profile.phone">
      </div>
      <div class="form-group">
        <label>Date of birth</label>
        <div class="info">{{ owner.profile.date_of_birth }}</div>
        <input class="input" type="date" v-model="owner.profile.date_of_birth">
      </div>
      <div class="form-group text-right" v-show="edit">
        <button type="button" class="btn btn-outline-danger" @click="hide">Cancel</button>
        <button class="btn btn-outline-primary">Update</button>
      </div>
    </form>
  </modal>
</template>
<script>
import { $auth } from '../../auth'

export default {
  name: 'user-profile',
  data () {
    return {
      edit: false,
      owner: {},
      backup: {}
    }
  },
  mounted () {
    $eventHub.$on('show-user-profile', this.show)
    $eventHub.$on('hide-user-profile', this.hide)
  },
  methods: {
    show (user) {
      this.owner = user
      this.$modal.show('user-profile')
    },
    hide () {
      this.edit = false
      this.$modal.hide('user-profile')
    },
    toggleEdit () {
      this.edit ? this.endEdit() : this.startEdit()
    },
    startEdit () {
      this.edit = true
      this.backup = {
        firstname: this.owner.profile.firstname,
        lastname: this.owner.profile.lastname,
        address: this.owner.profile.address,
        phone: this.owner.profile.phone,
        date_of_birth: this.owner.profile.date_of_birth
      }
    },
    endEdit () {
      this.edit = false
    },
    update () {
      $auth.request.put(`/api/user/${this.owner.id}/profile`, {
        firstname: this.owner.profile.firstname,
        lastname: this.owner.profile.lastname,
        address: this.owner.profile.address,
        phone: this.owner.profile.phone,
        date_of_birth: this.owner.profile.date_of_birth
      })
      .then(res => {
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'Update successfully'
        })
      })
      .catch(err => {
        this.owner.profile.firstname = this.backup.firstname
        this.owner.profile.lastname = this.backup.lastname
        this.owner.profile.address = this.backup.address
        this.owner.profile.phone = this.backup.phone
        this.owner.profile.date_of_birth = this.backup.date_of_birth
        let errMessage = err.response.data.message==undefined ? 'Unable to update profile' : err.response.data.message
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: errMessage
        })
      })
    }
  }
}
</script>
<style scoped>
  .user-profile {
    display: flex;
  }
  .user-profile .vm--modal{
    top: 0px!important;
  }
  label {
    font-weight: 600;
  }
  .profile-detail {
    padding: 15px;
  }
  .profile-detail .info {
    padding: 8px 15px;
  }
  .profile-detail.edit .info {
    display: none;
  }
  .input {
    display: block;
    width: 100%;
    border: none;
    border-bottom: 2px solid #ccc;
    padding: 8px 15px;
    outline: unset!important;
  }
  .profile-detail .input {
    display: none;
  }
  .profile-detail.edit .input {
    display: block;
  }
</style>