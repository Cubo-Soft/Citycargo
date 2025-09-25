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

    // ✅ GUARDAR DESDE MODAL "NUEVA ORDEN"
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

});