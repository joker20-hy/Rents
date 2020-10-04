import $token from './token'
import $user from './user'

export default {
  _data: {},
  remember (key, value) {
    this._data[key] = value 
  },
  forget (key) {
    this._data[key] = undefined
  },
  data (key) {
    return this._data[key]
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
  get user () {
    return this._user.user
  },
  get check() {
    if ($token.access==null||this.user==null) return false
    let now = new Date()
    return now.getTime() <= $token.expires
  },
  init (object=null) {
    this._user = $user()
    if (object!=null) this._user.user = object
    return this
  },
  clear() {
    this._user.remove()
    return true
  },
  remove() {
    this.clear()
    $token.remove()
  }
}
