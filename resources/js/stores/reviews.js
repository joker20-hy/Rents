const reviews = {
    namespaced: true,
    state: {
      reviews: [],
      total: 0
    },
    getters: {
      reviews (state) {
        return state.reviews
      },
      total(state) {
        return state.total
      }
    },
    mutations: {
      reviews (state, reviews) {
        state.reviews = reviews
      },
      add (state, review) {
        state.reviews.push(review)
      },
      remove (state, id) {
        state.reviews = state.reviews.filter(review => review.id!=id)
      },
      total(state, total) {
        state.total = total
      }
    }
  }
  export default reviews