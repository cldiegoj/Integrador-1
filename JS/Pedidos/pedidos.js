function setAction(action) {
    // Cambia el valor del action del formulario según el botón presionado
    document.getElementById('productForm').action = 'controller.php?action=' + action;
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('cantidad-input').addEventListener('keypress', function(event) {
        // Solo permite números
        const keyCode = event.keyCode || event.which;
        if (keyCode < 48 || keyCode > 57) {
            event.preventDefault(); // Bloquea el ingreso si no es un número
        }
    });
});

document.getElementById('productForm').addEventListener('submit', function(event) {
    const productoSelect = document.getElementById('producto');
    const tipoSelect = document.getElementById('tipo');

    // Verificar si "Producto" o "Tipo" están en la opción deshabilitada
    if (productoSelect.value === "" || tipoSelect.value === "") {
        event.preventDefault(); // Evita el envío del formulario
        alert("Por favor, seleccione tanto el Producto como el Tipo antes de enviar.");
    }
});
