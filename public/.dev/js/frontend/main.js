
/**
 * @param {String} query
 *
 * @return HTMLElement
 */
function $(query) {
  return document.querySelector(query)
}
/**
 * Query Nodelist
 * @param {String} query
 *
 * @return Nodelist
 */
function $all(query) {
  return document.querySelectorAll(query)
}
