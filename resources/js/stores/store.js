import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
  state: {
    sideMenu: {
      show: true
    },
    notification: {
      title: 'title',
      message: 'message',
      colors: {
        title: '',
        message: ''
      }
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
    notification (state, notification) {
      state.notification.title = notification.title
      state.notification.message = notification.message
      state.notification.colors = notification.colors==null ? {title: '', message: ''} : notification.colors
    }
  }
})