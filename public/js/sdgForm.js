const mulitSelectElementList = Array.prototype.slice.call(document.querySelectorAll('.form-multi-select'))
const mulitSelectList = mulitSelectElementList.map(mulitSelectEl => {
  return new coreui.MultiSelect(mulitSelectEl, {
    // options
  })
})