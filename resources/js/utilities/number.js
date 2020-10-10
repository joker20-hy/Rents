export default {
  /**
  * Range number by ','
  * @param {String} num 1000000
  */
  range (num) {
    try {
      return num.replace(/,/g, '').replace(/(\d)(?=(\d{3})+\b)/g, '$1,')
    } catch (error) {
      console.log(error)   
    }
  },
  /**
   * Get number from string
   * @param {String} str 1,000,000
   */
  unrange (str) {
    try {
      return parseFloat(str.replace(/,/g, ''))
    } catch (error) {
      console.log(error)   
    }
  }
}