import axios from 'axios'

/**
 * Token handlers
 */
const $token = {
  /**
   * Keys to tokens
   */
  keys: {
    access: '_access_token',
    refresh: '_refresh_token',
    expires: '_expires_in'
  },
  /**
   * Store tokens
   * @param {String} accessToken
   * @param {String} refreshToken
   */
  store (data) {
    localStorage.setItem(this.keys.access, data.access_token)
    localStorage.setItem(this.keys.refresh, data.refresh_token)
    let now = new Date()
    localStorage.setItem(this.keys.expires, now.valueOf()+data.expires_in*1000)
  },
  remove () {
    localStorage.removeItem(this.keys.access)
    localStorage.removeItem(this.keys.refresh)
    localStorage.removeItem(this.keys.expires)
  },
  /**
   * Get access token
   */
  get access_token () {
    return localStorage.getItem(this.keys.access)
  },
  /**
   * Get refresh token
   */
  get refresh_token () {
    return localStorage.getItem(this.keys.refresh)
  },
  get expires () {
    return localStorage.getItem(this.keys.expires)
  }
}
const $auth = {
  api: {
    login: '/api/login',
    refresh: '/api/refresh',
    logout: '/api/logout'
  },
  /**
   * Object to make authentication request
   */
  request: axios.create({
    baseURL: 'http://rent.joker.com/',
    headers: {
      Authorization: 'Bearer '+$token.access_token
    }
  }),
  check () {
    if ($token.access_token==null) return false
    let now = new Date()
    return now.valueOf() <= $token.expires
  },
  /**
   * Login handler
   * @param {Object} credentials 
   */
  login (credentials, success=null, error=null) {
    axios.post(this.api.login, credentials)
    .then(res => {
      $token.store(res.data)
      if (success!=null) success()
    })
    .catch(err => {
      if (error!=null) error(err)
    })
  },
  refresh () {
    this.request.post(this.api.refresh, {
      refresh_token: $token.refresh_token
    })
    .then(res => {
      $token.store(res.data)
    })
    .catch(err => {
      console.log(err)
    })
  },
  logout (success=null, error=null) {
    this.request.post(this.api.logout)
    .then(() => {
      $token.remove()
      if (success!=null) success()
    })
    .catch((err) => {
      if (error!=null) error(err)
    })
  }
}
export { $auth, $token }