window.Vue = require('vue')
window.$eventHub = new Vue()

import router from './router.js'
import VModal from 'vue-js-modal'
import App from './components/App'
import Paginate from 'vuejs-paginate'
import { store } from './stores/store.js'

Vue.use(VModal, { dialog: true })
Vue.component('paginate', Paginate)

const app = new Vue({
  el: '#app',
  router,
  store: store,
  components: { App },
  template: '<App/>'
});
