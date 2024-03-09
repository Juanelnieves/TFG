function openModal() {
  // Show the modal backdrop and content
  document.getElementById('modal-backdrop').classList.remove('hidden');
  document.getElementById('modal-container').classList.remove('hidden');
}

function closeModal() {
  // Hide the modal backdrop and content
  document.getElementById('modal-backdrop').classList.add('hidden');
  document.getElementById('modal-container').classList.add('hidden');
}