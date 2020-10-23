const directions = {
  namespaced: true,
  state: {
    directions: []
  },
  getters: {
    directions (state) {
      return state.directions
    }
  },
  mutations: {
    directions (state, directions) {
      state.directions = directions
    }
  }
}