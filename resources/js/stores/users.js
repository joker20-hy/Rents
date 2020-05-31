const users = {
  namespaced: true,
  state: {
    users: [],
    config: $config.user
  },
  getters: {
    users (state) {
      return state.users
    },
    verified (state) {
      return state.config.VERIFIED
    },
    roles (state) {
      return state.config.ROLE
    },
    roleNames (state) {
      return state.config.ROLE_NAME
    }
  },
  mutations: {
    users (state, users) {
      state.users = users
    },
    changeVerify (state, user) {
      user.verify = user.verify===state.config.VERIFIED ? state.config.UNVERIFIED : state.config.VERIFIED
    }
  }
}
export default users