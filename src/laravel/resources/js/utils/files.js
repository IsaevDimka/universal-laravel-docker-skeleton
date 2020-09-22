/**
 * @param {Sting} url
 */
export function downloadFile(sUrl) {
  // iOS devices do not support downloading. We have to inform user about this.
  if (/(iP)/g.test(navigator.userAgent)) {
    window.open(sUrl, '_blank')
    return false
  }

  // Creating new link node.
  const link = document.createElement('a')
  link.href = sUrl
  link.setAttribute('target', '_blank')

  if (link.download !== undefined) {
    // Set HTML5 download attribute. This will prevent file from opening if supported.
    const fileName = sUrl.substring(sUrl.lastIndexOf('/') + 1, sUrl.length)
    link.download = fileName
  }

  // Dispatching click event.
  if (document.createEvent) {
    const e = document.createEvent('MouseEvents')
    e.initEvent('click', true, true)
    link.dispatchEvent(e)
    return true
  }

  // Force file download (whether supported by server).
  if (sUrl.indexOf('?') === -1) {
    sUrl += '?download'
  }

  window.open(sUrl, '_blank')
  return true
}

export function smartDownload(sUrl, filename) {
  if (!filename) filename = GetFilename(sUrl)
  fetch(sUrl).then(function(t) {
    return t.blob().then((b) => {
      const a = document.createElement('a')
      a.href = URL.createObjectURL(b)
      a.setAttribute('download', filename)
      a.click()
    })
  })
}

export function GetFilename(url) {
  let fileName = ''
  if (url) {
    const m = url.toString().match(/.*\/(.+?)\./)
    const e = url.split('.').pop()
    if (m && m.length > 1) fileName = m[1]
    if (e && e.length > 1) fileName = fileName + '.' + e
    return fileName
  }
  return ''
}
