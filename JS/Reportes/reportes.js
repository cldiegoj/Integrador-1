$(document).ready(function () {
    $('#producto').select2();

    // Variables para almacenar el filtro seleccionado
    let selectedProduct = "0";
    let startDate = moment("01-01-2021", "DD-MM-YYYY");
    let endDate = moment();

    // Función de filtrado combinada
    function applyFilters() {
        $('#registers-table tbody tr').each(function () {
            let productCell = $(this).find('td:eq(2)').text();
            let dateCell = $(this).find('td:eq(4)').text();
            let rowDate = moment(dateCell, 'DD-MM-YYYY'); // Convierte la fecha de la fila a un objeto moment

            // Verifica si cumple con ambos filtros
            let productMatch = (selectedProduct === "0" || productCell === selectedProduct);
            let dateMatch = rowDate.isBetween(startDate, endDate, null, '[]');

            // Mostrar/ocultar la fila según ambos filtros
            if (productMatch && dateMatch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // Filtro por producto
    $('#producto').on('change', function () {
        selectedProduct = $(this).val(); // Actualiza el producto seleccionado
        applyFilters(); // Aplica los filtros combinados
    });

    // Configuración inicial de las fechas
    moment.locale('es');
    function cb(start, end) {
        $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
    }

    // Inicializa el componente de rango de fechas
    $('#reportrange').daterangepicker({
        showDropdowns: true,
        minYear: 2021,
        linkedCalendars: false,
        startDate: startDate,
        endDate: endDate,
        ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')],
            'Mes Anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            format: 'DD-MM-YYYY',
            separator: ' - ',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Personalizado',
            daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            firstDay: 1
        }
    }, cb);

    cb(startDate, endDate);

    // Filtro por rango de fechas
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        // Actualiza las fechas seleccionadas
        startDate = picker.startDate;
        endDate = picker.endDate;
        applyFilters(); // Aplica los filtros combinados
    });

    document.getElementById('savePdf').addEventListener('click', function () {
        const producto = document.getElementById('producto').value;
        // Convertir startDate y endDate a formato 'DD-MM-YYYY'
        const startDateFormatted = startDate.format('YYYY-MM-DD');
        const endDateFormatted = endDate.format('YYYY-MM-DD');

        // Redirige a `generar_pdf.php` con los parámetros de producto y rango de fecha
        window.location.href = `generar_pdf.php?producto=${encodeURIComponent(producto)}&dateRange=${encodeURIComponent(startDateFormatted + ' - ' + endDateFormatted)}`;
    });
});


