import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import users from './users'
import directions from './directions'
import houses from './houses'
import rooms from './rooms'
import services from './services'
import criterias from './criterias'

Vue.use(Vuex)

export const store = new Vuex.Store({
  modules: {
    auth,
    users,
    houses,
    directions,
    rooms,
    services,
    criterias
  }
})