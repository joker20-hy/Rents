const paymethods = {
  namespaced: true,
  state: {
    paymethods: []
  },
  getters: {
    paymethods (state) {
      return state.paymethods
    }
  },
  mutations: {
    paymethods (state, paymethods) {
      state.paymethods = paymethods
    },
    remove (state, id) {
      state.paymethods = state.paymethods.filter(item => item.id!=id)
    }
  }
}
export default paymethods