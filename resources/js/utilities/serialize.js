const $serialize = {
  fromObj (obj) {
    let str = []
    for (var p in obj) {
      if (obj.hasOwnProperty(p) && obj[p]!='' && obj[p]!=undefined && obj[p]!=null) str.push(`${encodeURIComponent(p)}=${encodeURIComponent(obj[p])}`)
    }
    return str.join("&")
  }
}
export default $serialize