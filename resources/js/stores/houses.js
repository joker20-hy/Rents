const houses = {
  namespaced: true,
  state: {
    houses: []
  },
  getters: {
    houses (state) {
      return state.houses
    }
  },
  mutations: {
    houses (state, houses) {
      state.houses = houses
    },
    add (state, house) {
      state.houses.push(house)
    }
  }
}
export default houses