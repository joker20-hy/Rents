import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import users from './users'
import directions from './directions'
import houses from './houses'
import rooms from './rooms'
import services from './services'
import criterias from './criterias'
import areas from './areas'
import provinces from './provinces'
import districts from './districts'
import reviews from './reviews'
import payments from './payments'
import paymethods from './paymethods'

Vue.use(Vuex)

export const store = new Vuex.Store({
  modules: {
    auth,
    users,
    houses,
    directions,
    rooms,
    services,
    criterias,
    areas,
    provinces,
    districts,
    reviews,
    payments,
    paymethods
  }
})