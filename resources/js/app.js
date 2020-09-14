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
import vueDebounce from 'vue-debounce'
 
Vue.use(vueDebounce)
 
// Or if you want to pass in the lock option
Vue.use(vueDebounce, {
  lock: true
})
 
// Setting a different event to listen to
Vue.use(vueDebounce, {
  listenTo: 'input'
})
 
// Listening to multiple events
Vue.use(vueDebounce, {
  listenTo: ['input', 'keyup']
})
 
// Setting a default timer This is set to '300ms' if not specified
Vue.use(vueDebounce, {
  defaultTime: '200ms'
})

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
