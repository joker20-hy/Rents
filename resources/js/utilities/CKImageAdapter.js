import { $token } from '../auth'

const $config = {
  api: '/api/image',
  method: 'POST',
  responseType: 'JSON'
}

class ImageAdapter {
  constructor(loader) {
    this.loader = loader
  }
  upload() {
    return this.loader.file
      .then( file => new Promise((resolve, reject) => {
        this._initRequest();
        this._initListeners(resolve, reject, file);
        this._sendRequest(file);
      }));
    }
    abort() {
      if(this.xhr) this.xhr.abort()
    } 
    _initRequest() {
      const xhr = this.xhr = new XMLHttpRequest();
      xhr.open($config.method, $config.api, true);
      xhr.setRequestHeader("Authorization", `Bearer ${$token.access_token}`)
      xhr.responseType = 'json';
    }
    _initListeners( resolve, reject, file ) {
      const xhr = this.xhr;
      const loader = this.loader;
      const genericErrorText = `Couldn't upload file: ${ file.name }.`;
      xhr.addEventListener('error', () => reject(genericErrorText));
      xhr.addEventListener('abort', () => reject());
      xhr.addEventListener('load', () => {
        const response = xhr.response;
        if (!response || response.error) {
          return reject(response && response.error ? response.error.message : genericErrorText);
        }
        console.log(response)
        resolve({
          default: response.urls[0]
        });
      } );
      if (xhr.upload) {
        xhr.upload.addEventListener('progress', evt => {
          if ( evt.lengthComputable ) {
            loader.uploadTotal = evt.total;
            loader.uploaded = evt.loaded;
          }
        });
      }
    }
    _sendRequest( file ) {
      const data = new FormData();
      data.append('images[0]', file);
      this.xhr.send(data);
    }
}
/**
 * 
 * @param {*} editor 
 * @param {URL} api 
 * @param {String} method POST||PUT
 * @param {String} imageUrl 
 * @param {String} responseType 
 */
function adapter(editor) {
  editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
    return new ImageAdapter(loader)
  }
}
export default adapter
