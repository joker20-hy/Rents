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
    },
    add (state, data) {
      state.payments.push(data)
    },
    delete (state, id) {
      state.payments = state.payments.filter(payment => payment.id!=id)
    }
  }
}
export default payments