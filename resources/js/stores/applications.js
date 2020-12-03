const applications = {
  namespaced: true,
  state: {
    applications: []
  },
  getters: {
    applications (state) {
      return state.applications
    },
    first: state => id => {
      return state.applications.find(item => item.id==id)
    }
  },
  mutations: {
    applications (state, applications) {
      state.applications = applications
    }
  }
}
export default applications