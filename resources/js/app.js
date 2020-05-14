require('./bootstrap')

window.Vue = require('vue');
import VueRouter from 'vue-router';
import router from './router.js'
import App from './components/App'

window.Vue.use(VueRouter)

const app = new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
});
