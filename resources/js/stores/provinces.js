const provinces = {
    namespaced: true,
    state: {
      provinces: []
    },
    getters: {
      provinces (state) {
        return state.provinces
      }
    },
    mutations: {
      provinces (state, provinces) {
        state.provinces = provinces
      },
      add (state, province) {
        state.provinces.push(province)
      },
      remove (state, id) {
        state.provinces = state.provinces.filter(province => province.id!=id)
      }
    }
  }
  export default provinces