var addSdg = document.getElementById('addSdg')

addSdg.addEventListener('show.bs.modal', function (event) {
  // Button that triggered the modal
  var button = event.relatedTarget;
  // Extract info from data-bs-* attributes
  var id_SDGs = button.getAttribute('data-bs-id');
  //
  // Update the modal's content.
  var modalTitle = addSdg.querySelector('.modal-title')
  var modalBodyInput = addSdg.querySelector('.modal-body input')

  modalTitle.textContent = 'New message to ' + id_SDGs;
  modalBodyInput.value = id_SDGs;
})



