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
                    <div class="card mb-4 border-0">
                        <div class="card-header border-0" style="background-color: rgb(146, 189, 130);">
                            <h6 class="mb-0 fw-bold">📋 Información General</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-2"><strong>🚗 Placa:</strong><br><span class="text-dark">${e.placa}</span></div>
                                <div class="col-md-4 mb-2"><strong>👤 Propietario:</strong><br><span class="text-dark">${e.nombre_propietario}</span></div>
                                <div class="col-md-4 mb-2"><strong>📝 Identificación:</strong><br><span class="text-dark">${e.identificacion}</span></div>
                                <div class="col-md-4 mb-2"><strong>🚙 Tipo Vehículo:</strong><br><span class="text-dark">${e.tipo_vehiculo}</span></div>
                                <div class="col-md-4 mb-2"><strong>🏷️ Marca:</strong><br><span class="text-dark">${e.marca}</span></div>
                                <div class="col-md-4 mb-2"><strong>📦 Tipo Carrocería:</strong><br><span class="text-dark">${e.tipo_carroceria}</span></div>
                                <div class="col-md-4 mb-2"><strong>📊 Kilometraje:</strong><br><span class="text-dark">${e.kilometraje}</span></div>
                                <div class="col-md-4 mb-2"><strong>📅 Fecha:</strong><br><span class="text-dark">${e.fecha_inven}</span></div>
                                <div class="col-12 mt-2">
                                    <strong>📝 Observaciones Generales:</strong><br>
                                    <div class="border rounded p-2 bg-light mt-1">${e.observaciones_generales || '—'}</div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                // --- Elementos Revisados MEJORADO ---
                if (response.data.detalle && response.data.detalle.length > 0) {
                    html += `<div class="card border-0">
                                <div class="card-header border-0" style="background-color: rgb(146, 189, 130);">
                                    <h6 class="mb-0 fw-bold">🔍 Elementos Revisados</h6>
                                </div>
                                <div class="card-body p-0">`;

                    // Agrupar por sección
                    const secciones = {};
                    response.data.detalle.forEach(item => {
                        const sec = item.seccion || 'Otros';
                        if (!secciones[sec]) secciones[sec] = [];
                        secciones[sec].push(item);
                    });

                    // Mostrar cada sección como acordeón
                    let seccionCount = 0;
                    for (const [seccion, items] of Object.entries(secciones)) {
                        seccionCount++;
                        const seccionId = `seccion-${seccionCount}`;
                        
                        html += `
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header" id="heading-${seccionId}">
                                    <button class="accordion-button collapsed border-0 shadow-none" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#collapse-${seccionId}"
                                            style="background-color: #f8f9fa;">
                                        ${getSeccionIcon(seccion)} <span class="fw-semibold ms-2">${seccion}</span> 
                                        <span class="badge bg-secondary ms-2 mt-1 my-1">${items.length}</span>
                                    </button>
                                </h2>
                                <div id="collapse-${seccionId}" class="accordion-collapse collapse border-0" 
                                    data-bs-parent=".card-body">
                                    <div class="accordion-body p-2">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover mb-0" style="font-size: 0.875rem;">
                                                <thead style="background-color: #deffdd;">
                                                    <tr>
                                                        <th width="40%" class="border-0 fw-semibold">Elemento</th>
                                                        <th width="15%" class="border-0 fw-semibold">Estado</th>
                                                        <th width="15%" class="border-0 fw-semibold">Cantidad</th>
                                                        <th width="30%" class="border-0 fw-semibold">Observación</th>
                                                    </tr>
                                                </thead>
                                                <tbody>`;
                        
                        items.forEach(item => {
                            const estado = { 
                                1: '<span class="badge bg-success">Bueno</span>', 
                                2: '<span class="badge bg-warning text-dark">Regular</span>', 
                                3: '<span class="badge bg-danger">Mal</span>' 
                            }[item.id_estado_inve] || '<span class="badge bg-secondary">—</span>';
                            
                            html += `
                                <tr>
                                    <td class="border-0">${item.elemento}</td>
                                    <td class="border-0">${estado}</td>
                                    <td class="border-0">${item.cantidad || '—'}</td>
                                    <td class="border-0">${item.observacion_uno || '—'}</td>
                                </tr>
                            `;
                        });
                        
                        html += `
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                    }
                    
                    html += `</div></div>`;
                }

                $('#contenidoInventario').html(html);
                $('#btnEditarInventario').data('id', response.data.encabezado.id);
            } else {
                $('#contenidoInventario').html('<div class="alert alert-danger">Error al cargar los datos.</div>');
            }
        },
        error: function () {
            $('#contenidoInventario').html('<div class="alert alert-danger">Error de conexión al cargar el inventario.</div>');
        }
    });
});

// ✅ Función para obtener íconos según la sección
function getSeccionIcon(seccion) {
    const iconos = {
        'Cabina Interna': '🚗',
        'Cabina Externa': '🚙', 
        'Furgón': '📦',
        'Kit de Carretera': '🧰',
        'Botiquín': '🩹',
        'Otros Elementos': '🔧'
    };
    return iconos[seccion] || '📋';
}

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

    // ✅ USAR OBJETO NORMAL
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
    $('#formEdicionInventario select[name$="[estado]"], #formEdicionInventario input[name$="[cantidad]"], #formEdicionInventario input[name$="[observacion]"]').each(function () {
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


