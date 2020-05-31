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
    localStorage.setItem(this.keys.expires, now.valueOf()+data.expires_in)
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
    refresh: '/api/refresh'
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
    let now = new Date()
    return now.valueOf() <= $token.expires
  },
  /**
   * Login handler
   * @param {Object} credentials 
   */
  login (credentials) {
    axios.post(this.api.login, credentials)
    .then(res => {
      $token.store(res.data)
    })
    .catch(err => {
      console.log(err)
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
  logout () {
    //
  }
}
export { $auth }