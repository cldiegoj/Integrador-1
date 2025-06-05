function fillForm(codigo,nombre,ruc,telefono,correo) {
    // Llenar los inputs con los datos de la fila seleccionada
    document.getElementById('codigo-input').value = codigo;
    document.getElementById('nombre-input').value = nombre;
    document.getElementById('ruc-input').value = ruc;
    document.getElementById('telefono-input').value = telefono;
    document.getElementById('correo_proveedor').value = correo;

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "show_products.php?query="+codigo, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const inventoryTableBody = document.querySelector("#productos-table");
            // Limpiar el tbody antes de insertar los nuevos resultados
            inventoryTableBody.innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}


function filterProveedores(query) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "filter_proveedores.php?query=" + encodeURIComponent(query), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const inventoryTableBody = document.querySelector("#proveedores-table");
            // Limpiar el tbody antes de insertar los nuevos resultados
            inventoryTableBody.innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}


function setAction(action) {
    // Cambia el valor del action del formulario según el botón presionado
    document.getElementById('productForm').action = 'controller.php?action=' + action;
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('ruc-input').addEventListener('keypress', function(event) {
        // Solo permite números
        const keyCode = event.keyCode || event.which;
        if (keyCode < 48 || keyCode > 57) {
            event.preventDefault(); // Bloquea el ingreso si no es un número
        }
    });

    document.getElementById('telefono-input').addEventListener('keypress', function(event) {
        // Solo permite números
        const keyCode = event.keyCode || event.which;
        if (keyCode < 48 || keyCode > 57) {
            event.preventDefault(); // Bloquea el ingreso si no es un número
        }
    });

});
