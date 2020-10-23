const auth = {
  namespaced: true,
  state: {
    user: {}
  },
  getters: {
    user (state) {
      return state.user
    },
    normal (state) {
      return state.user.role==$config.USER.ROLE.NORMAL
    },
    admin (state) {
      return state.user.role==$config.USER.ROLE.ADMIN
    },
    owner (state) {
      return state.user.role==$config.USER.ROLE.OWNER
    },
    renter (state) {
      return state.user.role==$config.USER.ROLE.RENTER
    }
  },
  mutations: {
    user (state, user) {
      state.user = user
    }
  }
}
export default auth