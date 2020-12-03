const data = {}
class Config {
  constructor () {}
  set (name, value) {
    data[name] = value
    this.__defineGetter__(name, () => data[name])
    this.__defineSetter__(name, val => data[name]=val)
  }
}
const config = new Config()
config.set('base_url', $config.APP_URL)
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