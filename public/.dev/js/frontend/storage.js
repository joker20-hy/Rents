class Record {
  constructor (array) {
    this._arr = array
  }
  like (feild, keywords) {
    return this._arr.filter(item => item[feild].toLowerCase().indexOf(keywords) > -1);
  }
}
