import { MessageBox } from 'element-ui'

export function confirm(message, title, type, callbackFunction, errorFunction) {
  // prettier-ignore
  MessageBox.confirm(message, title, {
    confirmButtonText: '确认',
    cancelButtonText: '取消',
    type: type
  }).then(() => {
    if (callbackFunction) {
      callbackFunction()
    }
  }).catch(() => {
      if (errorFunction) {
      errorFunction()
    }
  })
}
