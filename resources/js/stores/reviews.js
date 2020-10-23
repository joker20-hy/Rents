const reviews = {
    namespaced: true,
    state: {
      reviews: []
    },
    getters: {
      reviews (state) {
        return state.reviews
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
      }
    }
  }
  export default reviews