document.addEventListener('DOMContentLoaded', function () {
    const token1Dropdown = document.getElementById('token1Dropdown');
    const token2Dropdown = document.getElementById('token2Dropdown');
    const token1AmountInput = document.getElementById('token1Amount');
    const token2AmountDisplay = document.getElementById('token2Amount');
    const swapForm = document.getElementById('swapForm');
    const token1Input = document.getElementById('token1Input');
    const token2Input = document.getElementById('token2Input');
    const amountInput = document.getElementById('amountInput');

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