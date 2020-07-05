window.Vue = require('vue')
window.$eventHub = new Vue()
window.utf8 = require('utf8')

import router from './router.js'
import VModal from 'vue-js-modal'
import Paginate from 'vuejs-paginate'
import { store } from './stores/store.js'
import CKEditor from '@ckeditor/ckeditor5-vue'
import App from './pages/App'

Vue.use( CKEditor )
Vue.use(VModal, { dialog: true })
Vue.component('paginate', Paginate)

const app = new Vue({
  el: '#app',
  router,
  store: store,
  components: { App },
  template: '<App/>'
});
