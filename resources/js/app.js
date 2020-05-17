require('./bootstrap')

window.Vue = require('vue');
import VueRouter from 'vue-router';
import router from './router.js'
import App from './components/App'
import { BootstrapVue } from 'bootstrap-vue'
import { store } from './stores/store.js'

Vue.use(BootstrapVue)
window.Vue.use(VueRouter)

const app = new Vue({
  el: '#app',
  router,
  store: store,
  components: { App },
  template: '<App/>'
});
