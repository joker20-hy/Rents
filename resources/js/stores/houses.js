const houses = {
  namespaced: true,
  state: {
    houses: []
  },
  getters: {
    houses (state) {
      return state.houses
    },
    find: state => id => {
      return state.houses.find(house => house.id==id)
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