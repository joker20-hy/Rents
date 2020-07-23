import axios from 'axios'
import token from './token'

const $api = {
  login: '/api/login',
  refresh: '/api/refresh',
  logout: '/api/logout'  
}
const $auth = {
  get check() {
    if (token.access_token==null) return false
    let now = new Date()
    return now.getTime() <= token.expires
  },
  request: axios.create({
    baseURL: 'http://rent.joker.com/',
    headers: {
      Authorization: 'Bearer '+token.access_token
    }
  })
}
/**
 * Login
 * @param {Object} credentials 
 * @param {Function} success 
 * @param {Function} error 
 */
function login(credentials, success=null, error=null) {
  axios.post($api.login, credentials)
  .then(res => {
    token.store(res.data)
    if (success!=null) success()
  })
  .catch(err => {
    if (error!=null) error(err)
  })
}
/**
 * Refresh tokens
 * @param {Function} success 
 * @param {Function} error 
 */
function refresh(success=null, error=null) {
  $auth.request.post($api.refresh, {
    refresh_token: token.refresh_token
  })
  .then(res => {
    token.store(res.data)
    if (success!=null) success()
  })
  .catch(err => {
    if (error!=null) error(err)
  })
}
/**
 * Logout
 * @param {Function} success 
 * @param {Function} error 
 */
function logout(success=null, error=null) {
  $auth.request.post($api.logout)
  .then(() => {
    token.remove()
    if (success!=null) success()
  })
  .catch((err) => {
    if (error!=null) error(err)
  })
}
export { $auth, login, refresh, logout }