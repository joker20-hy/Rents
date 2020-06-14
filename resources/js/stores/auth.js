const auth = {
  namespaced: true,
  state: {
    user: {}
  },
  getters: {
    user (state) {
      return state.user
    }
  },
  mutations: {
    user (state, user) {
      state.user = user
    }
  }
}
export default auth