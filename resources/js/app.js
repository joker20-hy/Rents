window.Vue = require('vue')
window.$eventHub = new Vue()
window.utf8 = require('utf8')
import { $auth } from './utilities/request/request'
window.$auth = $auth.init()
import { request } from './utilities/request/request'
window.$request = request
import serialize from './utilities/serialize'
window.serialize = serialize
import merge from './utilities/merge'
window.merge = merge
window.$old_route = ''

import router from './router.js'
import VModal from 'vue-js-modal'
import Paginate from 'vuejs-paginate'
import { store } from './stores/store.js'
import CKEditor from '@ckeditor/ckeditor5-vue'
import App from './pages/App'
import Clipboard from 'v-clipboard'
// import VueCarousel from 'vue-carousel';

// Vue.use(VueCarousel)
Vue.use(Clipboard)
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
