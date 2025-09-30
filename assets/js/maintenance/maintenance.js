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
            $('input[name="placa"]').trigger('focus');
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

                    $('input[name="placa"]').val(data.placa).prop('readonly', true);
                    $('input[name="num_factura"]').val(data.factura).prop('readonly',);
                    $('input[name="costo"]').val(data.valor).prop('readonly',);

                    $('select[name="id_prestador"]').val(data.id_prestador);
                    $('select[name="id_tipo_manteni"]').val(data.id_tipo_manteni);

                    const fechaFormateada = formatearFechaAISO(data.fecha);
                    $('input[name="fecha"]').val(fechaFormateada);
                    $('input[name="kilometraje"]').val(data.kilometraje || '');
                    $('textarea[name="observaciones"]').val(data.observaciones || '');

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
    console.error("ERROR EN AJAX");
    console.log("Status:", status);
    console.log("Error:", errorThrown);
    console.log("Respuesta del servidor:", xhr.responseText);

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


});
