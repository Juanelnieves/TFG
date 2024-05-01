document.addEventListener('DOMContentLoaded', function () {
    const token1Dropdown = document.getElementById('token1Dropdown');
    const token2Dropdown = document.getElementById('token2Dropdown');
    const token1AmountInput = document.getElementById('token1Amount');
    const token2AmountDisplay = document.getElementById('token2Amount');
    const swapForm = document.getElementById('swapForm');
    const token1Input = document.getElementById('token1Input');
    const token2Input = document.getElementById('token2Input');
    const amountInput = document.getElementById('amountInput');
    const tokenSelects = document.querySelectorAll('.token-select');

    tokenSelects.forEach(function(select) {
        const customOptions = Array.from(select.options).map(function(option) {
            const imageSrc = option.getAttribute('data-image');
            const label = option.textContent.trim();
            const balance = option.getAttribute('data-balance');
            return `<div class="token-option" data-value="${option.value}" data-balance="${balance}">
                        <img src="${imageSrc}" alt="${label}" class="inline-block h-4 w-4 mr-2">
                        <span>${label}</span>
                    </div>`;
        });

        const customDropdown = document.createElement('div');
        customDropdown.classList.add('token-dropdown');
        customDropdown.innerHTML = customOptions.join('');

        const customSelect = document.createElement('div');
        customSelect.classList.add('custom-select');
        customSelect.innerHTML = `
            <span class="selected-option">
                <img src="${select.options[select.selectedIndex].getAttribute('data-image')}" alt="${select.options[select.selectedIndex].textContent.trim()}" class="inline-block h-4 w-4 mr-2">
                <span>${select.options[select.selectedIndex].textContent.trim()}</span>
            </span>
            <i class="arrow"></i>
        `;

        const balanceElement = select.parentNode.parentNode.querySelector(`#user${select.id.replace('Dropdown', '')}Amount`);
        balanceElement.textContent = select.options[select.selectedIndex].getAttribute('data-balance');

        select.parentNode.insertBefore(customSelect, select);
        select.parentNode.insertBefore(customDropdown, select.nextSibling);

        customSelect.addEventListener('click', function(event) {
            event.stopPropagation();
            customDropdown.classList.toggle('show');
        });

        customDropdown.addEventListener('click', function(event) {
            const selectedOption = event.target.closest('.token-option');
            if (selectedOption) {
                const selectedValue = selectedOption.getAttribute('data-value');
                const selectedBalance = selectedOption.getAttribute('data-balance');
                select.value = selectedValue;
                customSelect.querySelector('.selected-option').innerHTML = `
                    <img src="${selectedOption.querySelector('img').getAttribute('src')}" alt="${selectedOption.textContent.trim()}" class="inline-block h-4 w-4 mr-2">
                    <span>${selectedOption.textContent.trim()}</span>
                `;
                balanceElement.textContent = selectedBalance;
                customDropdown.classList.remove('show');
            }
        });

        document.addEventListener('click', function(event) {
            if (!customSelect.contains(event.target)) {
                customDropdown.classList.remove('show');
            }
        });
    });
    
    function updateSwapRate() {
        const token1 = token1Dropdown.value;
        const token2 = token2Dropdown.value;
        const amount = token1AmountInput.value;
        if (token1 && token2 && amount > 0) {
            fetch(`/api/swap/rate?token1=${token1}&token2=${token2}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la solicitud');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                        token2AmountDisplay.value = 'Error';
                        return;
                    }
                    const rate = data.rate;
                    const amountToReceive = amount * rate;
                    if (!isNaN(amountToReceive)) {
                        token2AmountDisplay.value = amountToReceive.toFixed(2);
                    } else {
                        console.error('Error: amountToReceive is not a number.');
                        token2AmountDisplay.value = 'Error';
                    }
                })
                .catch(error => {
                    console.error('Error fetching swap rate:', error);
                    token2AmountDisplay.value = 'Error';
                });
        } else {
            token2AmountDisplay.value = '0';
        }
    }

    function updateTokenImages() {
        const token1Image = document.getElementById('token1Image');
        const token2Image = document.getElementById('token2Image');
        fetch(`/api/tokens/${token1Dropdown.value}`)
            .then(response => response.json())
            .then(data => {
                token1Image.src = data.url;
            })
            .catch(error => {
                console.error('Error fetching token 1 image URL:', error);
            });
        fetch(`/api/tokens/${token2Dropdown.value}`)
            .then(response => response.json())
            .then(data => {
                token2Image.src = data.url;
            })
            .catch(error => {
                console.error('Error fetching token 2 image URL:', error);
            });
    }

    function updateHiddenInputs() {
        token1Input.value = token1Dropdown.value;
        token2Input.value = token2Dropdown.value;
        console.log('Updated Hidden Inputs - Token 1:', token1Input.value);
        console.log('Updated Hidden Inputs - Token 2:', token2Input.value);
    }
    token1Dropdown.addEventListener('change', updateHiddenInputs);
    token2Dropdown.addEventListener('change', updateHiddenInputs);

    token1Dropdown.addEventListener('change', function () {
        updateSwapRate();
        updateTokenImages();
        updateHiddenInputs();
        updateUserTokenAmounts();
        console.log('Token 1 Dropdown Change - Selected Token:', token1Dropdown.value);

    });

    token2Dropdown.addEventListener('change', function () {
        updateSwapRate();
        updateTokenImages();
        updateHiddenInputs();
        updateUserTokenAmounts();
        console.log('Token 2 Dropdown Change - Selected Token:', token2Dropdown.value);

    });

    token1AmountInput.addEventListener('input', updateSwapRate);

    swapForm.addEventListener('submit', function (event) {
        event.preventDefault();
        updateHiddenInputs();
        amountInput.value = token1AmountInput.value;
        console.log('Form Submit - Token 1:', token1Input.value);
        console.log('Form Submit - Token 2:', token2Input.value);
        this.submit();

    });

    function updateUserTokenAmounts() {
        const token1 = token1Dropdown.value;
        const token2 = token2Dropdown.value;

        fetch(`/swap/user-token-amounts?token1=${token1}&token2=${token2}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('userToken1Amount').textContent = data.userToken1Amount;
                document.getElementById('userToken2Amount').textContent = data.userToken2Amount;
            })
            .catch(error => {
                console.error('Error fetching user token amounts:', error);
            });
    }


    function swapSelectedTokens() {
        const token1Value = token1Dropdown.value;
        const token2Value = token2Dropdown.value;

        token1Dropdown.value = token2Value;
        token2Dropdown.value = token1Value;

        updateSwapRate();
        updateTokenImages();
        updateHiddenInputs();
        updateUserTokenAmounts();
    }
    document.getElementById('swapTokensButton').addEventListener('click', swapSelectedTokens);

});