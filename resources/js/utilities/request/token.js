import config from '../config'

export default {
  get keys () {
    return config.token_keys
  },
  /**
   * Store tokens
   * @param {*} data
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
  get access () {
    return localStorage.getItem(this.keys.access)
  },
  /**
   * Get refresh token
   */
  get refresh () {
    return localStorage.getItem(this.keys.refresh)
  },
  /**
   * Get expire time of token
   */
  get expires () {
    return parseInt(localStorage.getItem(this.keys.expires))
  }
}