/**
 * Token handlers
 * @private
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
export default $token