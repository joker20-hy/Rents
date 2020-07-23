const rooms = {
    namespaced: true,
    state: {
      rooms: []
    },
    getters: {
      rooms (state) {
        return state.rooms
      },
      first (state) {
        return state.rooms[0]
      }
    },
    mutations: {
      rooms (state, rooms) {
        state.rooms = rooms
      },
      add (state, room) {
        state.rooms.push(room)
      }
    }
  }
  export default rooms