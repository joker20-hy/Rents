const payments = {
  namespaced: true,
  state: {
    payments: []
  },
  getters: {
    payments (state) {
      return state.payments
    },
    find: state => id => {
      return state.payments.find(payment => payment.id==id)
    }
  },
  mutations: {
    payments (state, data) {
      state.payments = data
    }
  }
}
export default payments