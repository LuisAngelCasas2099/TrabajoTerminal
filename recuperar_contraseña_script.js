function recoverPassword() {
    const email = document.getElementById('email').value;

    fetch('verificar_correo.php', {
        method: 'POST',
        body: JSON.stringify({ email: email }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud.');
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            const errorMessage = document.querySelector('.error-message');
            errorMessage.textContent = data.error;
            errorMessage.style.display = 'block';
        } else {
            // Continuar con la lógica para recuperar la contraseña
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function closeErrorMessage() {
    document.querySelector('.error-message').style.display = 'none';
}
