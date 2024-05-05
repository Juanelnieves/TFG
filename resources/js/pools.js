document.addEventListener('DOMContentLoaded', function () {

    const addLiquidityButtons = document.querySelectorAll('[data-bs-target="#addLiquidityModal"]');
    addLiquidityButtons.forEach(button => {
        button.addEventListener('click', function () {
            console.log('Botón de añadir liquidez clickeado');

            const poolId = this.getAttribute('data-pool-id');
            const poolIdInput = document.querySelector('#poolIdInput');
            poolIdInput.value = poolId;
            console.log('Pool ID actualizado en el formulario:', poolIdInput.value);
        });
    });
    const addLiquidityForm = document.querySelector('#addLiquidityForm');
    addLiquidityForm.addEventListener('submit', function (event) {
        const formData = new FormData(this);
        console.log('Datos del formulario:', Object.fromEntries(formData));
    });
});