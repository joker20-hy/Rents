// class Suggest {
//   /**
//    * @param {HTMLElement} el
//    */
//   constructor (query) {
//     this._el = $(query)
//     this._input = this._el.querySelector('input[type=search]')
//     this._suggest = this._el.querySelector('.suggest')
//     this._optionList = this._el.querySelector('.option-list')
//     this._el.addEventListener('click', e => e.stopPropagation())
//     document.addEventListener('click', () => this._suggest.classList.remove('show'))
//     this._input.addEventListener('focus', () => this.show())
//   }
//   getEvt () {
//     this._input.addEventListener('focus', function () {
//       if (this.value.length > 1) {
//         //
//       }
//     })
//   }
//   /**
//    * 
//    * @param {Array} data 
//    */
//   show (data=null) {
//     if (data!=null) {
//         this._optionList.querySelectorAll('.option').forEach(el => el.remove())
//         data.forEach(item => {
//             let option = document.createElement('div')
//             option.value = item.id
//             option.innerHTML = item.name
//             option.classList.add('option')
//             this._optionList.appendChild(option)
//             option.addEventListener('click', function () {
//                 console.log(this.value)
//             })
//           })
//     }
//     this._suggest.classList.add('show')
//   }
//   hide () {
//     this._suggest.classList.remove('show')
//   }
// }
