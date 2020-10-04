import config from '../config'

class User {
  constructor () {
    this._store_key = config.user_store_key
    this._user = JSON.parse(localStorage.getItem(this._store_key))
  }
  set user(user) {
    this._user = user
    localStorage.setItem(this._store_key, JSON.stringify(user))
  }
  get user() {
    return this._user
  }
  remove() {
    this._user = null
    localStorage.removeItem(this._store_key)
  }
}
export default function () {
  return new User()
}