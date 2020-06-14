<template>
  <modal name="user-setting" class="user-setting top0" :width="500">
    <div class="p-3">
      <h4>User setting</h4>
      <div class="row ml-0 mr-0" v-if="owner.setting!=undefined">
        <div class="col-6">
          2 step verification
        </div>
        <div class="col-6">
          <switch-input class="mr-0 ml-auto" :status="twoStep" @click="change2Step()"/>
        </div>
      </div>
    </div>
  </modal>
</template>
<script>
import SwitchInput from '../utilities/SwitchInput'
import { $auth } from '../../auth'

export default {
  name: 'user-setting',
  components: {
    SwitchInput
  },
  data () {
    return {
      twoStep: false,
      owner: {}
    }
  },
  mounted () {
    $eventHub.$on('show-user-setting', this.show)
    $eventHub.$on('hide-user-setting', this.hide)
  },
  methods: {
    show (user) {
      this.owner = user
      this.twoStep = user.setting.verification_2_step==$config.TRUE
      this.$modal.show('user-setting')
    },
    hide () {
      this.$modal.hide('user-setting')
    },
    change2Step () {
      this.twoStep = !this.twoStep
      $auth.request.put(`/api/user/${this.owner.id}/setting`, {
        verification_2_step: this.twoStep ? $config.TRUE : $config.FALSE
      })
      .then(res => {
        this.owner.setting.verification_2_step=this.twoStep ? $config.TRUE : $config.FALSE
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'User setting is updated',
          timeout: 3000
        })
      })
      .catch(err => {
        this.twoStep = !this.twoStep
      })
    }
  }
}
</script>
<style scoped>
  .user-setting {
    display: flex;
  }
</style>