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
      return state.user.role==$config.user.ROLE.NORMAL
    },
    admin (state) {
      return state.user.role==$config.user.ROLE.ADMIN
    },
    owner (state) {
      return state.user.role==$config.user.ROLE.OWNER
    },
    renter (state) {
      return state.user.role==$config.user.ROLE.RENTER
    }
  },
  mutations: {
    user (state, user) {
      state.user = user
    }
  }
}
export default auth