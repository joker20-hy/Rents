import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
  state: {
    sideMenu: {
      show: true
    },
    notification: {
      title: 'this is title',
      message: 'this is message'
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
    },
    setNotification (state, notification) {
      state.notification = notification
    }
  }
})