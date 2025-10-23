function formatearFechaAISO(fecha) {
    // Asume que la fecha viene como "dd-mm-yyyy" o "dd/mm/yyyy"
    if (!fecha.includes("-") && !fecha.includes("/")) return fecha;

    const sep = fecha.includes("/") ? "/" : "-";
    const [dd, mm, yyyy] = fecha.split(sep);
    return `${yyyy}-${mm.padStart(2, '0')}-${dd.padStart(2, '0')}`;
}



// ✅ GUARDAR NUEVA PLACA
$(document).on('click', '#btnGuardarPlaca', function () {
    const placa = $('#modalNuevaPlaca input[name="placa"]').val().trim();
    const id_marca = $('#modalNuevaPlaca select[name="id_marca"]').val();
    const tipovehiculo = $('#modalNuevaPlaca select[name="tipovehiculo"]').val();
    const tipocarroceria = $('#modalNuevaPlaca input[name="tipocarroceria"]').val().trim();
    const modelo = $('#modalNuevaPlaca input[name="modelo"]').val();
    const capacidadcarga = $('#modalNuevaPlaca input[name="capacidadcarga"]').val();

    if (!placa || !id_marca || !tipovehiculo || !tipocarroceria || !modelo || !capacidadcarga) {
        Swal.fire('Advertencia', 'Todos los campos son obligatorios.', 'warning');
        return;
    }

    $.ajax({
        url: '../controllers/inventoryAjax.php',
        type: 'POST',
        data: {
            action: 'crearVehiculo',
            placa: placa,
            id_marca: id_marca,
            tipovehiculo: tipovehiculo,
            tipocarroceria: tipocarroceria,
            modelo: modelo,
            capacidadcarga: capacidadcarga
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#modalNuevaPlaca').modal('hide');
                // Limpiar campos
                $('#modalNuevaPlaca input').val('');

                // Agregar al select
                $('#selectPlaca').append(
                    $('<option>', {
                        value: placa,
                        text: placa
                    })
                );
                $('#selectPlaca').val(placa);
                Swal.fire('Éxito', 'Vehículo registrado correctamente.', 'success');
            } else {
                Swal.fire('Error', res.message || 'No se pudo crear el vehículo.', 'error');
            }
        },
        error: function () {
            Swal.fire('Error', 'Error de conexión al crear el vehículo.', 'error');
        }
    });
});

// ✅ CARGAR DATOS DEL VEHÍCULO AL SELECCIONAR UNA PLACA
$(document).on('change', '#selectPlaca', function () {
    const placa = $(this).val();

    if (!placa || placa === 'nuevo') {
        $('#nombre_propietario').val('');
        $('#cedula_propietario').val('');
        $('#tipo_vehiculo').val('');
        $('#marca').val('');
        $('#tipo_carroceria').val('');
        return;
    }

    $.ajax({
        url: '../controllers/inventoryAjax.php',
        type: 'POST',
        data: {
            action: 'obtenerDatosVehiculo',
            placa: placa
        },
        dataType: 'json',
        success: function (response) {
            if (response.success && response.data) {
                const d = response.data;
                $('#nombre_propietario').val(d.Nombre || '');
                $('#cedula_propietario').val(d.Cedula || '');
                $('#tipo_vehiculo').val(d.tipo_vehiculo || '');
                $('#marca').val(d.marca || '');
                $('#tipo_carroceria').val(d.tipo_carroceria || '');
            } else {
                $('#nombre_propietario').val('');
                $('#cedula_propietario').val('');
                $('#tipo_vehiculo').val('');
                $('#marca').val('');
                $('#tipo_carroceria').val('');
                Swal.fire('Info', response.message || 'No se encontraron datos.', 'info');
            }
        },
        error: function () {
            Swal.fire('Error', 'No se pudieron cargar los datos del vehículo.', 'error');
        }
    });
});
