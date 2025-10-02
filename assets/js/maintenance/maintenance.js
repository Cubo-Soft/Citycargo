function formatearFechaAISO(fecha) {
    // Asume que la fecha viene como "dd-mm-yyyy" o "dd/mm/yyyy"
    if (!fecha.includes("-") && !fecha.includes("/")) return fecha;

    const sep = fecha.includes("/") ? "/" : "-";
    const [dd, mm, yyyy] = fecha.split(sep);
    return `${yyyy}-${mm.padStart(2, '0')}-${dd.padStart(2, '0')}`;
}

$(document).ready(function () {

    // ✅ CARGAR DETALLE DESDE AJAX
    $(document).on('click', '.btn-ver-detalle', function () {
        let id = $(this).data('id');
        $.ajax({
            url: '../controllers/maintenanceAjax.php',
            type: 'POST',
            data: { action: 'getDetail', id: id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    let html = `
                        <div class="row">
                            <div class="col-md-6"><strong>Factura:</strong> ${res.data.factura}</div>
                            <div class="col-md-6"><strong>Placa:</strong> ${res.data.placa}</div>
                            <div class="col-md-6"><strong>Empresa:</strong> ${res.data.empresa}</div>
                            <div class="col-md-6"><strong>Fecha:</strong> ${res.data.fecha}</div>
                            <div class="col-md-6"><strong>Valor:</strong> $${res.data.valor}</div> 
                            <div class="col-md-6"><strong>Servicio:</strong> ${res.data.servicio}</div>                            
                            <div class="col-12"><strong>Observaciones:</strong> ${res.data.observaciones}</div>
                        </div>`;
                    $('#detailContent').html(html);
                } else {
                    $('#detailContent').html('<p class="text-danger">Error al cargar detalles.</p>');
                }
            },
            error: function () {
                $('#detailContent').html('<p class="text-danger">Error de conexión.</p>');
            }
        });
    });

    // ✅ GUARDAR DESDE MODAL "NUEVO MANTENIMIENTO"
    $('#saveNew').on('click', function () {
        let formData = $('#formNew').serializeArray();
        formData.push({ name: 'action', value: 'save' });

        $.ajax({
            url: '../controllers/maintenanceAjax.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#modalNew').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Mantenimiento guardado correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión.'
                });
            }
        });
    });

    // ✅ GUARDAR DESDE FORMULARIO RÁPIDO
    $('#quickForm').on('submit', function (e) {
        e.preventDefault();
        let formData = $(this).serializeArray();
        formData.push({ name: 'action', value: 'quickSave' });

        $.ajax({
            url: '../controllers/maintenanceAjax.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#quickForm')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Registro creado con éxito.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión.'
                });
            }

        });
    });


    // ✅ EDITAR DESDE EL MODAL DE DETALLE
    $('#modalDetail').on('show.bs.modal', function () {
        $('#btn-edit-maintenance').data('id', '');
    });
    $('#modalDetail').on('hidden.bs.modal', function () {
        $('#btn-edit-maintenance').data('id', '');
    });

    // ✅ VER DETALLE (SOLO MUESTRA LOS DATOS EN EL MODAL DE DETALLE)
    $(document).on('click', '.btn-ver-detalle', function () {
        let id = $(this).data('id');
        if (!id) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'No se encontró el ID del mantenimiento.',
            });
            return;
        }

        $.ajax({
            url: '../controllers/maintenanceAjax.php',
            type: 'POST',
            data: { action: 'getDetail', id: id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    let data = res.data;

                    let html = `
                    <div class="row">
                        <div class="col-md-6"><strong>Factura:</strong> ${data.factura}</div>
                        <div class="col-md-6"><strong>Placa:</strong> ${data.placa}</div>
                        <div class="col-md-6"><strong>Empresa:</strong> ${data.empresa}</div>
                        <div class="col-md-6"><strong>Fecha:</strong> ${data.fecha}</div>
                        <div class="col-md-6"><strong>Valor:</strong> $${data.valor}</div> 
                        <div class="col-md-6"><strong>Servicio:</strong> ${data.servicio}</div>                            
                        <div class="col-12"><strong>Observaciones:</strong> ${data.observaciones}</div>
                    </div>`;

                    $('#detailContent').html(html);
                    $('#btn-edit-maintenance').data('id', id); // ✅ ¡ASIGNAMOS EL ID CORRECTO!
                } else {
                    $('#detailContent').html('<p class="text-danger">Error al cargar detalles.</p>');
                    $('#btn-edit-maintenance').data('id', '');
                }
            },
            error: function () {
                $('#detailContent').html('<p class="text-danger">Error de conexión.</p>');
                $('#btn-edit-maintenance').data('id', '');
            }
        });
    });

    // ✅ EDITAR DESDE EL MODAL DE DETALLE (botón "Editar")
    $(document).on('click', '#btn-edit-maintenance', function () {
        let id = $(this).data('id');
        if (!id) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'No se encontró el ID del mantenimiento.',
            });
            return;
        }

        $('#modalDetail').modal('hide');

        $('#modalDetail').modal('hide');

        setTimeout(() => {
            $('#modalNew').modal('show');
            // Mover el foco a un campo del nuevo modal (por ejemplo, el campo "placa")
            $('#formNew input[name="placa"]').trigger('focus');
        }, 300);

        $.ajax({
            url: '../controllers/maintenanceAjax.php',
            type: 'POST',
            data: { action: 'getDetail', id: id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    let data = res.data;

                    $('#modalNew').modal('show');

                    // ✅ Solo en el formulario del modal (#formNew)
                    $('#formNew input[name="placa"]').val(data.placa).prop('readonly', true);
                    $('#formNew input[name="num_factura"]').val(data.factura).prop('readonly', true);
                    $('#formNew input[name="costo"]').val(data.valor).prop('readonly', true);

                    $('#formNew select[name="id_prestador"]').val(data.id_prestador);
                    $('#formNew select[name="id_tipo_manteni"]').val(data.id_tipo_manteni);

                    const fechaFormateada = formatearFechaAISO(data.fecha);
                    $('#formNew input[name="fecha"]').val(fechaFormateada);
                    $('#formNew input[name="kilometraje"]').val(data.kilometraje || '');
                    $('#formNew textarea[name="observaciones"]').val(data.observaciones || '');

                    $('#saveNew').text('Actualizar').off('click').on('click', function () {
                        let formData = $('#formNew').serialize();
                        formData += '&action=update&id=' + id;

                        $.ajax({
                            url: '../controllers/maintenanceAjax.php',
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            success: function (res) {
                                if (res.success) {
                                    $('#modalNew').modal('hide');
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Éxito!',
                                        text: 'Mantenimiento actualizado correctamente',
                                        timer: 2000,
                                        showConfirmButton: false,
                                    }).then(() => location.reload());
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: res.message,
                                    });
                                }
                            },
                            error: function (xhr, status, errorThrown) {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error de conexión: ' + errorThrown,
                                });
                            }
                        });
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar los datos.',
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los datos del mantenimiento.',
                });
            }
        });
    });


    // ✅ RESETAR EL MODAL DE NUEVO CUANDO SE CIERRE
    $('#modalNew').on('hidden.bs.modal', function () {
        $('#formNew')[0].reset(); // Limpia todos los campos
        $('input[name="placa"], input[name="num_factura"], input[name="costo"]').prop('readonly', false); // Desbloquea
        $('#saveNew').text('Guardar').off('click').on('click', function () {
            // Vuelve a activar el evento normal de guardar (no update)
            let formData = $('#formNew').serializeArray();
            formData.push({ name: 'action', value: 'save' });

            $.ajax({
                url: '../controllers/maintenanceAjax.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        $('#modalNew').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Mantenimiento guardado correctamente',
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message,
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error de conexión.',
                    });
                }
            });
        });
    });


    // ✅ GUARDAR "NUEVA PROXIMA REVISIÓN"
    $(document).on('click', '#saveNewRev', function () {
        let formData = $('#formRev').serializeArray();
        formData.push({ name: 'action', value: 'saveRev' });

        $.ajax({
            url: '../controllers/maintenanceAjax.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#modalNewRev').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Proxima revisión guardada correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message
                    });
                }
            },
            error: function (xhr, status, error) {
                let mensaje = 'Error de conexión.';
                if (xhr.responseText) {
                    try {
                        let res = JSON.parse(xhr.responseText);
                        mensaje = res.message || mensaje;
                    } catch (e) {
                        mensaje = 'Error del servidor: ' + xhr.responseText.substring(0, 150) + '...';
                    }
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje
                });
            }
        });
    });

    // ✅ GUARDAR EDICIÓN DE PROXIMA REVISIÓN
    $(document).on('click', '#saveEditRev', function () {
        const id = $('#id_mantenimiento').val();
        const placa = $('#placa_edit').val();
        const id_tipo_manteni = $('select[name="id_tipo_manteni"]').val();
        const fecha = $('#fecha_edit').val();
        const km = $('#kilometraje_edit').val();

        if (!id || !placa || !id_tipo_manteni || !fecha || km === '' || km < 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor complete todos los campos correctamente.'
            });
            return;
        }

        $.ajax({
            url: '../controllers/maintenanceAjax.php',
            type: 'POST',
            data: {
                action: 'updateRevision',
                id: id,
                placa: placa,
                id_tipo_manteni: id_tipo_manteni,
                fecha_programada: fecha,
                kilometraje_programado: km
            },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#modalEditRev').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Revisión actualizada correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión al actualizar.'
                });
            }
        });
    });

    // ✅ Detectar selección de "Nueva empresa"
    $('#selectEmpresa').on('change', function () {
        if ($(this).val() === 'nueva') {
            // Abrir submodal
            const subModal = new bootstrap.Modal(document.getElementById('modalNuevaEmpresa'));
            subModal.show();

            // Restaurar el select a "Seleccionar empresa"
            $(this).val('');
        }
    });

    // ✅ GUARDAR NUEVA EMPRESA
    $(document).on('click', '#btnGuardarEmpresa', function () {
        // Usar selectores dentro del modal específico
        const nombre = $('#modalNuevaEmpresa input[name="nombre_empresa"]').val().trim();
        const nit = $('#modalNuevaEmpresa input[name="nit"]').val().trim();
        const direccion = $('#modalNuevaEmpresa input[name="direccion"]').val().trim();
        const contacto = $('#modalNuevaEmpresa input[name="contacto"]').val().trim();
        const email = $('#modalNuevaEmpresa input[name="email"]').val().trim();

        if (!nombre || !nit) {
            Swal.fire('Advertencia', 'Nombre y NIT son obligatorios.', 'warning');
            return;
        }

        $.ajax({
            url: '../controllers/maintenanceAjax.php',
            type: 'POST',
            data: {
                action: 'crearPrestador',
                nombre: nombre,
                nit: nit,
                direccion: direccion,
                contacto: contacto,
                email: email
            },
            dataType: 'json',
            success: function (res) {
                if (res.success && res.id) {
                    $('#modalNuevaEmpresa').modal('hide');
                    // Limpiar solo los campos del submodal
                    $('#modalNuevaEmpresa input[name="nombre_empresa"]').val('');
                    $('#modalNuevaEmpresa input[name="nit"]').val('');
                    $('#modalNuevaEmpresa input[name="direccion"]').val('');
                    $('#modalNuevaEmpresa input[name="contacto"]').val('');
                    $('#modalNuevaEmpresa input[name="email"]').val('');

                    // Agregar al select principal
                    $('#selectEmpresa').append(
                        $('<option>', {
                            value: res.id,
                            text: nombre
                        })
                    );
                    $('#selectEmpresa').val(res.id);
                    Swal.fire('Éxito', 'Empresa creada correctamente.', 'success');
                } else {
                    Swal.fire('Error', res.message || 'No se pudo crear la empresa.', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Error de conexión al crear la empresa.', 'error');
            }
        });
    });

    // ✅ Detectar selección de "Nuevo Servicio"
    $('#selectServicio').on('change', function () {
        if ($(this).val() === 'nueva') {
            // Abrir submodal
            const subModal = new bootstrap.Modal(document.getElementById('modalNuevoServicio'));
            subModal.show();

            // Restaurar el select a "Seleccionar servicio"
            $(this).val('');
        }
    });

    // ✅ GUARDAR NUEVO SERVICIO
    $(document).on('click', '#btnGuardarServicio', function () {
        // Usar selectores dentro del modal específico
        const nombre = ($('#modalNuevoServicio input[name="nombre_servicio"]').val() || '').trim();
        const descripcion = ($('#modalNuevoServicio textarea[name="descripcion_servicio"]').val() || '').trim();


        if (!nombre || !descripcion) {
            Swal.fire('Advertencia', 'Nombre y Descripción obligatorios.', 'warning');
            return;
        }

        $.ajax({
            url: '../controllers/maintenanceAjax.php',
            type: 'POST',
            data: {
                action: 'crearServicio',
                nombre: nombre,
                descripcion: descripcion,
            },
            dataType: 'json',
            success: function (res) {
                if (res.success && res.id) {
                    $('#modalNuevoServicio').modal('hide');
                    // Limpiar solo los campos del submodal
                    $('#modalNuevoServicio input[name="nombre_servicio"]').val('');
                    $('#modalNuevoServicio input[name="descripcion_servicio"]').val('');

                    // Agregar al select principal
                    $('#selectServicio').append(
                        $('<option>', {
                            value: res.id,
                            text: nombre
                        })
                    );
                    $('#selectServicio').val(res.id);
                    Swal.fire('Éxito', 'Servicio creado correctamente.', 'success');
                } else {
                    Swal.fire('Error', res.message || 'No se pudo crear el servicio.', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Error de conexión al crear el servicio.', 'error');
            }
        });
    });






});


// ✅ FUNCIÓN GLOBAL PARA CARGAR DATOS DE REVISIÓN
function cargarDatosRevision(id) {
    if (!id) return;

    // Mostrar el modal
    const modalEditRev = new bootstrap.Modal(document.getElementById('modalEditRev'));
    modalEditRev.show();

    // Limpiar formulario
    document.getElementById('formEditRev').reset();

    // Cargar datos con jQuery.ajax (igual que el resto del archivo)
    $.ajax({
        url: '../controllers/maintenanceAjax.php',
        type: 'POST',
        data: { action: 'getRevisionById', id: id },
        dataType: 'json',
        success: function (res) {
            if (res.success && res.data) {
                const d = res.data;
                $('#id_mantenimiento').val(d.id_mantenimiento);
                $('#placa_edit').val(d.placa);
                $('#fecha_edit').val(d.fecha_programada);
                $('#kilometraje_edit').val(d.kilometraje_programado);
                $('select[name="id_tipo_manteni"]').val(d.id_manteni);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar la revisión.'
                });
                modalEditRev.hide();
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión al cargar la revisión.'
            });
            modalEditRev.hide();
        }
    });
}