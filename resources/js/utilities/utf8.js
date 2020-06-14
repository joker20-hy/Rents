const utf8 = {
  encode () {
    return unescape(encodeURIComponent(s));
  },
  decode () {
    return decodeURIComponent(escape(s));
  }
}
export default utf8