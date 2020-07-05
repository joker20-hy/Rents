const criterias = {
  namespaced: true,
  state: {
    criterias: []
  },
  getters: {
    criterias (state) {
      return state.criterias
    }
  },
  mutations: {
    criterias (state, criterias) {
      state.criterias = criterias
    },
    add (state, criteria) {
      state.criterias.push(criteria)
    },
    remove (state, id) {
      state.criterias = state.criterias.filter(criteria => criteria.id!=id)
    }
  }
}
export default criterias