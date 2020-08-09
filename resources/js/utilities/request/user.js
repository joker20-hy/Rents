class User {
  constructor () {
    this._user = JSON.parse(localStorage.getItem(`_auth`))
  }
  set user(user) {
    this._user = user
    localStorage.setItem(`_auth`, JSON.stringify(user))
  }
  get user() {
    return this._user
  }
  remove() {
    this._user = null
    localStorage.removeItem(`_auth`)
  }
}
function user () { return new User() }
export default user