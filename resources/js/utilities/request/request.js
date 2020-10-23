import axios from 'axios'
import config from '../config'
import $auth from './auth'
import $token from './token'

/**
 * Create ajax object
 */
function ajax() {
  return axios.create({
    baseURL: `${config.base_url}/`,
    headers: { Authorization: `Bearer ${$token.access}` }
  })
}
/**
 * Login
 * @param {Object} credentials 
 * @param {Function} success 
 * @param {Function} error 
 */
function login(credentials, success=null, error=null) {
  ajax().post($auth.api.login, credentials)
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
  ajax().post($auth.api.refresh, { refresh_token: $token.refresh })
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
  ajax().post($auth.api.logout)
  .then(() => {
    $auth.remove()
    if (success!=null) success()
  })
  .catch(err => {
    $auth.remove()
    if (error!=null) error(err)
  })
}
/**
 * Get user info
 * @param {Function} success 
 * @param {Function} error 
 */
function user(success, error) {
  ajax().get($auth.api.user)
  .then(res => success(res))
  .catch(err => error(err))
}
export { $auth, login, refresh, logout, user, ajax }