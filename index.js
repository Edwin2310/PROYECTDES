// Mostrar mensajes de error si existen
if (typeof errorMessage !== 'undefined' && errorMessage !== '') {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errorMessage
    });
}

// Mostrar/Ocultar contraseña
const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');

togglePassword.addEventListener('click', function(e) {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
});
