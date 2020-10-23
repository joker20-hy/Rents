const areas = {
  namespaced: true,
  state: {
    areas: []
  },
  getters: {
    areas (state) {
      return state.areas
    }
  },
  mutations: {
    areas (state, areas) {
      state.areas = areas
    },
    add (state, area) {
      state.areas.push(area)
    },
    remove (state, id) {
      state.areas = state.areas.filter(area => area.id!=id)
    }
  }
}
export default areas