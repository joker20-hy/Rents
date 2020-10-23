<template>
  <modal name="user-setting" class="p-2" :width="500">
    <div class="p-3">
      <h4>User setting</h4>
      <div class="row ml-0 mr-0" v-if="user.setting!=undefined">
        <div class="col-6">
          2 step verification
        </div>
        <div class="col-6">
          <switch-input class="mr-0 ml-auto" :status="user.setting.verification_2_step==TRUE" @click="change2Step()"/>
        </div>
      </div>
    </div>
  </modal>
</template>
<script>
import SwitchInput from '../../utilities/SwitchInput'
import { $request } from '../../../auth'

export default {
  name: 'user-setting',
  components: {
    SwitchInput
  },
  data () {
    return {
      user: {}
    }
  },
  computed: {
    TRUE () {
      return $config.TRUE 
    },
    FALSE () {
      return $config.FALSE 
    }
  },
  mounted () {
    $eventHub.$on('show-user-setting', this.show)
  },
  methods: {
    show (user) {
      this.$modal.show('user-setting')
      this.user = user
    },
    hide () {
      this.$modal.hide('user-setting')
    },
    change2Step () {
      this.user.setting.verification_2_step=this.user.setting.verification_2_step!=$config.TRUE ? $config.TRUE : $config.FALSE
      ajax().put(`/api/user/${this.user.id}/setting`, this.user.setting)
      .then(res => {
        $eventHub.$emit('success-alert', {
          title: 'Success',
          message: 'User setting is updated',
          timeout: 3000
        })
      })
      .catch(err => {
        this.user.setting.verification_2_step=this.user.setting.verification_2_step!=$config.TRUE ? $config.TRUE : $config.FALSE
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to update user setting'
        })
      })
    }
  }
}
</script>