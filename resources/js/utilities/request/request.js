import axios from 'axios'
import $token from './token'
import $user from './user'

const $auth = {
  get api() {
    return {
      login: '/api/login',
      refresh: '/api/refresh',
      logout: '/api/logout'
    }
  },
  get user () {
    return this._user.user
  },
  get check() {
    if ($token.access_token==null||this._user==null) return false
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
/**
 * Login
 * @param {Object} credentials 
 * @param {Function} success 
 * @param {Function} error 
 */
function login(credentials, success=null, error=null) {
  axios.post($auth.api.login, credentials)
  .then(res => {
    $token.store(res.data)
    if (success!=null) success()
  })
  .catch(err => error!=null?error(err):'')
}
/**
 * Refresh tokens
 * @param {Function} success 
 * @param {Function} error 
 */
function refresh(success=null, error=null) {
  $request.post($auth.api.refresh, {
    refresh_token: $token.refresh_token
  })
  .then(res => {
    $token.store(res.data)
    if (success!=null) success()
  })
  .catch(err => error!=null?error(err):'')
}
/**
 * Logout
 * @param {Function} success 
 * @param {Function} error 
 */
function logout(success=null, error=null) {
  $request.post($auth.api.logout)
  .then(() => {
    $auth.remove()
    if (success!=null) success()
  })
  .catch(err => {
    $auth.remove()
    if (error!=null) error(err)
  })
}
const request = axios.create({
  baseURL: 'http://rent.joker.com/',
  headers: {
    Authorization: `Bearer ${$token.access_token}`
  }
})
export { $auth, login, refresh, logout, request }