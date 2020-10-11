import $token from './token'
import $user from './user'

const _data = {
  old_route: {},
  user: {}
}
export default {
  remember (key, value) {
    _data.old_route[key] = value
  },
  forget (key) {
    _data.old_route[key] = undefined
  },
  data (key) {
    return _data.old_route[key]
  },
  get api() {
    return {
      login: '/api/login',
      refresh: '/api/refresh',
      logout: '/api/logout',
      user: '/api/user/auth'
    }
  },
  get baseUrl () {
    return 'http://rent.joker.com'
  },
  set user(obj) {
    _data.user = obj
  },
  get user () {
    return _data.user.data
  },
  get check() {
    if ($token.access==null||this.user==null) return false
    let now = new Date()
    return now.getTime() <= $token.expires
  },
  init (object=null) {
    this.user = $user()
    if (object!=null) this.user.data = object
    return this
  },
  clear() {
    _data.user.remove()
    return true
  },
  remove() {
    this.clear()
    $token.remove()
  }
}
