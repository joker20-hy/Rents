export default {
  /**
   * Serialize object into query string
   * @param {Object} obj
   * @return String
   */
  fromObj (obj, glue='&') {
    let params = []
    for (let key in obj) {
      if (!(obj[key]===''||obj[key]==null)) {
        params.push(`${key}=${obj[key]}`)
      }
    }
    return params.join(glue)
  }
}