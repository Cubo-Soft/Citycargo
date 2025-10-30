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
                $('input[name="nombre_propietario"]').val(d.Nombre || '');
                $('input[name="identificacion"]').val(d.Cedula || '');
                $('input[name="tipo_vehiculo"]').val(d.tipo_vehiculo || '');
                $('input[name="marca"]').val(d.marca || '');
                $('input[name="tipo_carroceria"]').val(d.tipo_carroceria || '');
            } else {
                $('input[name="nombre_propietario"]').val('');
                $('input[name="identificacion"]').val('');
                $('input[name="tipo_vehiculo"]').val('');
                $('input[name="marca"]').val('');
                $('input[name="tipo_carroceria"]').val('');
                Swal.fire('Info', response.message || 'No se encontraron datos.', 'info');
            }
        },
        error: function () {
            Swal.fire('Error', 'No se pudieron cargar los datos del vehículo.', 'error');
        }
    });
});

// ✅ GUARDAR INVENTARIO COMPLETO
$(document).on('submit', '#formInventario', function (e) {
    e.preventDefault(); // ← Evita la recarga

    let elementosValidos = 0;
    $(this).find('select[name$="[estado]"]').each(function () {
        if (['bueno', 'regular', 'mal'].includes($(this).val())) {
            elementosValidos++;
        }
    });

    if (elementosValidos === 0) {
        Swal.fire('Advertencia', 'Debe seleccionar al menos un estado para algún elemento.', 'warning');
        return;
    }

    const formData = new FormData(this);
    formData.append('action', 'guardarInventario');

    $.ajax({
        url: '../controllers/inventoryAjax.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                Swal.fire('Éxito', 'Inventario guardado correctamente.', 'success').then(() => {
                    $('#formInventario')[0].reset(); // Limpiar formulario
                    location.reload();
                });
            } else {
                Swal.fire('Error', res.message || 'Error al guardar.', 'error');
            }
        },
        error: function () {
            Swal.fire('Error', 'Error de conexión al guardar el inventario.', 'error');
        }
    });
});


// ✅ CARGAR DETALLE EN EL MODAL AL HACER CLIC EN "VER"
$(document).on('click', '.btn-inventario-detalle', function () {
    const idInventario = $(this).data('id');

    // Mostrar loader o limpiar contenido anterior
    $('#contenidoInventario').html('<div class="text-center">Cargando...</div>');

    $.ajax({
        url: '../controllers/inventoryAjax.php',
        type: 'POST',
        data: {
            action: 'obtenerDetalleInventario',
            id_inventario: idInventario
        },
        dataType: 'json',
        success: function (response) {
            if (response.success && response.data) {
                const e = response.data.encabezado;

                let html = `
                    <div class="row">
                        <div class="col-md-6"><strong>Placa:</strong> ${e.placa}</div>
                        <div class="col-md-6"><strong>Propietario:</strong> ${e.nombre_propietario}</div>
                        <div class="col-md-6"><strong>Identificación:</strong> ${e.identificacion}</div>
                        <div class="col-md-6"><strong>Tipo Vehículo:</strong> ${e.tipo_vehiculo}</div>
                        <div class="col-md-6"><strong>Marca:</strong> ${e.marca}</div>
                        <div class="col-md-6"><strong>Tipo Carrocería:</strong> ${e.tipo_carroceria}</div>
                        <div class="col-md-6"><strong>Kilometraje:</strong> ${e.kilometraje}</div>
                        <div class="col-md-6"><strong>Fecha:</strong> ${e.fecha_inven}</div>
                        <div class="col-12"><strong>Observaciones Generales:</strong> ${e.observaciones_generales || '—'}</div>
                    </div>`;

                // --- Agregar los elementos revisados ---
                if (response.data.detalle && response.data.detalle.length > 0) {
                    html += `<hr><h6 class="mt-3">Elementos Revisados</h6>`;

                    // Agrupar por sección
                    const secciones = {};
                    response.data.detalle.forEach(item => {
                        const sec = item.seccion || 'Otros';
                        if (!secciones[sec]) secciones[sec] = [];
                        secciones[sec].push(item);
                    });

                    // Mostrar cada sección
                    for (const [seccion, items] of Object.entries(secciones)) {
                        html += `<div class="row mt-2"><div class="col-12"><strong>${seccion}:</strong></div>`;
                        items.forEach(item => {
                            const estado = { 1: 'Bueno', 2: 'Regular', 3: 'Mal' }[item.id_estado_inve] || '—';
                            let itemTexto = `${item.elemento}: ${estado}`;
                            if (item.cantidad) itemTexto += ` (Cant: ${item.cantidad})`;
                            if (item.observacion_uno) itemTexto += `  ${item.observacion_uno}`;
                            if (item.fecha_vence) itemTexto += ` | Vence: ${item.fecha_vence}`;
                            html += `<div class="col-md-6">${itemTexto}</div>`;
                        });
                        html += `</div>`;
                    }
                }

                $('#contenidoInventario').html(html);
                $('#btnEditarInventario').data('id', response.data.encabezado.id);
            } else {
                $('#contenidoInventario').html('<p class="text-danger">Error al cargar.</p>');
            }
        },
        error: function () {
            $('#contenidoInventario').html('<p class="text-danger">Error de conexión.</p>');
        }
    });
});

// ✅ ABRIR MODAL DE EDICIÓN
$(document).on('click', '#btnEditarInventario', function () {
    const idInventario = $(this).data('id');

    if (!idInventario) {
        Swal.fire('Error', 'ID no disponible.', 'error');
        return;
    }

    // ✅ QUITAR EL FOCO DEL BOTÓN ANTES DE CERRAR
    $(this).blur();

    $('#modalDetalleInventario').modal('hide');

    $('#modalEditarInventario').on('shown.bs.modal', function () {
        $.ajax({
            url: '../controllers/inventoryAjax.php',
            type: 'POST',
            data: {
                action: 'obtenerDetalleInventario',
                id_inventario: idInventario
            },
            dataType: 'json',
            success: function (response) {
                if (response.success && response.data) {
                    precargarFormularioEdicion(response.data);
                } else {
                    Swal.fire('Error', 'No se pudo cargar los datos.', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Error al cargar datos de edición.', 'error');
            }
        });
    });

    // Abrir modal de edición
    $('#modalEditarInventario').modal('show');
});

// ✅ PRECARGAR FORMULARIO DE EDICIÓN
function precargarFormularioEdicion(data) {
    const e = data.encabezado;
    const detalle = data.detalle;

    // Encabezado
    $('#id_inventario_edit').val(e.id);
    $('input[name="placa_edit"]').val(e.placa);
    $('input[name="nombre_propietario_edit"]').val(e.nombre_propietario);
    $('input[name="identificacion_edit"]').val(e.identificacion);
    $('input[name="tipo_vehiculo_edit"]').val(e.tipo_vehiculo);
    $('input[name="marca_edit"]').val(e.marca);
    $('input[name="tipo_carroceria_edit"]').val(e.tipo_carroceria);
    $('input[name="fecha_edit"]').val(e.fecha_inven);
    $('input[name="kilometraje_edit"]').val(e.kilometraje);
    $('textarea[name="observaciones_generales_edit"]').val(e.observaciones_generales || '');

    // Resetear todos los campos del detalle
    $('#formEdicionInventario select[name$="[estado]"]').val('');
    $('#formEdicionInventario input[name$="[cantidad]"]').val('');
    $('#formEdicionInventario input[name$="[observacion]"]').val('');

    // Llenar los que existen
    const seccionMap = {
        'Cabina Interna': 'cabina_interna',
        'Cabina Externa': 'cabina_externa',
        'Furgón': 'furgon',
        'Kit de Carretera': 'kit_carretera',
        'Botiquín Médico': 'botiquin',
        'Otros Elementos': 'otros'
    };

    detalle.forEach(item => {
        const estadoVal = { 1: 'bueno', 2: 'regular', 3: 'mal' }[item.id_estado_inve] || '';
        const claveSeccion = seccionMap[item.seccion] || 'otros';

        $(`#formEdicionInventario select[name="detalle[${claveSeccion}][${item.elemento}][estado]"]`).val(estadoVal);
        $(`#formEdicionInventario input[name="detalle[${claveSeccion}][${item.elemento}][cantidad]"]`).val(item.cantidad || '');
        $(`#formEdicionInventario input[name="detalle[${claveSeccion}][${item.elemento}][observacion]"]`).val(item.observacion_uno || '');
    });

    // ✅ Forzar reinicio del acordeón
    $('#accordionEdicion').find('.collapse').removeClass('show');
}

// ✅ GUARDAR EDICIÓN
$(document).on('click', '#btnGuardarEdicionInventario', function () {
    // Validar elementos
    let elementosValidos = 0;
    $('#formEdicionInventario select[name$="[estado]"]').each(function () {
        if (['bueno', 'regular', 'mal'].includes($(this).val())) {
            elementosValidos++;
        }
    });
    if (elementosValidos === 0) {
        Swal.fire('Advertencia', 'Debe seleccionar al menos un estado.', 'warning');
        return;
    }

    // ✅ USAR OBJETO NORMAL EN LUGAR DE FormData
    const datos = {
        action: 'actualizarInventario',
        id_inventario: $('#id_inventario_edit').val(),
        placa: $('input[name="placa_edit"]').val(),
        nombre_propietario: $('input[name="nombre_propietario_edit"]').val(),
        identificacion: $('input[name="identificacion_edit"]').val(),
        tipo_vehiculo: $('input[name="tipo_vehiculo_edit"]').val(),
        marca: $('input[name="marca_edit"]').val(),
        tipo_carroceria: $('input[name="tipo_carroceria_edit"]').val(),
        fecha: $('input[name="fecha_edit"]').val(),
        kilometraje: $('input[name="kilometraje_edit"]').val(),
        observaciones_generales: $('textarea[name="observaciones_generales_edit"]').val()
    };

    // Agregar TODOS los campos del detalle
    $('#formEdicionInventario select[name$="[estado]"], #formEdicionInventario input[name$="[cantidad]"], #formEdicionInventario input[name$="[observacion]"]').each(function() {
        const name = $(this).attr('name');
        datos[name] = $(this).val();
    });

    console.log("Datos completos a enviar:", datos);

    $.ajax({
        url: '../controllers/inventoryAjax.php',
        type: 'POST',
        data: datos,
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                Swal.fire('Éxito', 'Inventario actualizado.', 'success').then(() => {
                    $('#modalEditarInventario').modal('hide');
                    location.reload();
                });
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        },
        error: function () {
            Swal.fire('Error', 'Error al guardar.', 'error');
        }
    });
});


