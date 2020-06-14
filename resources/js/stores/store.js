import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import users from './users'
import houses from './houses'
import directions from './directions'

Vue.use(Vuex)

export const store = new Vuex.Store({
  modules: {
    auth,
    users,
    houses,
    directions
  }
})