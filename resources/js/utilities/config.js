class Config {
  constructor () {}
  set (name, value) {
    this[`_${name}`] = value
    this.__defineGetter__(name, function(){return this[`_${name}`]})
    this.__defineSetter__(name, function(val){return this[`_${name}`]=val})
  }
}
const config = new Config()
config.set('base_url', 'http://rent.joker.com')
config.set('routes', new Config())
config.routes.set('login', '/api/login')
config.routes.set('refresh', '/api/refresh')
config.routes.set('logout', '/api/logout')
config.routes.set('user', '/api/user/auth')
config.set('token_keys', new Config())
config.token_keys.set('access', '_access_token')
config.token_keys.set('refresh', '_refresh_token')
config.token_keys.set('expires', '_expires_in')
config.set('user_store_key', '_auth')
export default config