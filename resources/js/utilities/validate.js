const rules = {
  email: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
  phone: /^[0]{1}[0-9]{9}$/
}
export default {
  isEmail(str) {
    if (str=="") return false
    return rules.email.test(str)
  },
  isPhone(str) {
    if (str=="") return false
    return rules.phone.test(str)
  }
}