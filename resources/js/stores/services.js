const services = {
    namespaced: true,
    state: {
      services: []
    },
    getters: {
      services (state) {
        return state.services
      }
    },
    mutations: {
      services (state, services) {
        state.services = services
      },
      add (state, service) {
        state.services.push(service)
      },
      remove (state, id) {
        state.services = state.services.filter(serv => serv.id!=id)
      }
    }
  }
  export default services