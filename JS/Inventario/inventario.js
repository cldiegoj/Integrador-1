function fillForm(codigo, producto, descripcion, entradas, salidas, proveedor) {
    // Llenar los inputs con los datos de la fila seleccionada
    document.getElementById('codigo-input').value = codigo;
    document.getElementById('producto-input').value = producto;
    document.getElementById('descripcion-input').value = descripcion;
    document.getElementById('entradas-input').value = entradas;
    document.getElementById('salidas-input').value = salidas;
    document.getElementById('codigo_proveedor').value = proveedor;
}


function filterProducts(query) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "filter_products.php?query=" + encodeURIComponent(query), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const inventoryTableBody = document.querySelector("#inventory-table");
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

function validarEntradas(input) {
    if (input.value < 0) {
        input.value = 0; // Restablecer a 0 si se intenta ingresar un número negativo
    }
}

function validarSalidas(input) {
    if (input.value < 0) {
        input.value = 0; // Restablecer a 0 si se intenta ingresar un número negativo
    }
}