const districts = {
    namespaced: true,
    state: {
      districts: []
    },
    getters: {
      districts (state) {
        return state.districts
      }
    },
    mutations: {
      districts (state, districts) {
        state.districts = districts
      },
      add (state, district) {
        state.districts.push(district)
      },
      remove (state, id) {
        state.districts = state.districts.filter(district => district.id!=id)
      }
    }
  }
  export default districts