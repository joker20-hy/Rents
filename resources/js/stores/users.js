const users = {
  namespaced: true,
  state: {
    users: [],
    config: $config.USER,
    roommate_wanted: {}
  },
  getters: {
    users (state) {
      return state.users
    },
    verified (state) {
      return state.config.VERIFIED
    },
    unverified () {
      return state.config.UNVERIFIED
    },
    roles (state) {
      return state.config.ROLE
    },
    roleNames (state) {
      return state.config.ROLE_NAME
    },
    roommate_wanted(state) {
      return state.roommate_wanted
    }
  },
  mutations: {
    users (state, users) {
      state.users = users
    },
    changeVerify (state, user) {
      user.verify = user.verify===state.config.VERIFIED ? state.config.UNVERIFIED : state.config.VERIFIED
    },
    roommate_wanted(state, wanted) {
      state.roommate_wanted = wanted
    }
  }
}
export default users