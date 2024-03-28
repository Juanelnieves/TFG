const tokenDropdown1 = document.getElementById('token1Dropdown');
const tokenIcon1 = document.getElementById('tokenIcon');
const tokenDropdown2 = document.getElementById('token2Dropdown');
const tokenIcon2 = document.getElementById('tokenIcon2');

// Función para actualizar el icono del token
function updateTokenIcon(dropdown, icon) {
    const selectedOption = dropdown.options[dropdown.selectedIndex];
    const iconUrl = selectedOption.getAttribute('data-icon-url');
    icon.src = iconUrl;
}

// Actualiza el icono del token cuando la página se carga
updateTokenIcon(tokenDropdown1, tokenIcon1);
updateTokenIcon(tokenDropdown2, tokenIcon2);

// Actualiza el icono del token cuando el usuario selecciona un nuevo token
tokenDropdown1.addEventListener('change', function() {
    updateTokenIcon(tokenDropdown1, tokenIcon1);
});
tokenDropdown2.addEventListener('change', function() {
    updateTokenIcon(tokenDropdown2, tokenIcon2);
});