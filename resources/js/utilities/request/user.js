import config from '../config'

class User {
  constructor () {
    this._user = JSON.parse(localStorage.getItem(config.user_store_key))
  }
  set data(data) {
    this._user = data
    localStorage.setItem(config.user_store_key, JSON.stringify(data))
  }
  get data() {
    return this._user
  }
  remove() {
    this._user = null
    localStorage.removeItem(config.user_store_key)
  }
}
export default function () {
  return new User()
}
