document.addEventListener('DOMContentLoaded', function () {
    // Seleccionar el campo de usuario y contraseña
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    
    // Función para permitir solo números y un máximo de 8 caracteres en el campo de usuario
    usernameInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, ''); // Eliminar cualquier cosa que no sea un número
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8); // Limitar a 8 dígitos
        }
    });

    // Función para limitar el campo de contraseña a un máximo de 8 caracteres
    passwordInput.addEventListener('input', function () {
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8); // Limitar a 8 caracteres
        }
    });
});
