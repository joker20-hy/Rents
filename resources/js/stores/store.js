import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
  state: {
    sideMenu: {
      show: true
    }
  },
  getters: {
    sideMenu (state) {
      return state.sideMenu.show
    }
  },
  mutations: {
    toggleSideMenu (state) {
      state.sideMenu.show = !state.sideMenu.show
    }
  }
})