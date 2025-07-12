table = '';

$(document).ready(function () {
    cargarViajes();
});

function abrirModalViaje() {
    limpiar();
    $('#mdlviaje').modal('show');
}

function cargarViajes() {
    const url = baseURL + "mant_viajes/datatables";
    table = $("#tblviajes").DataTable({
        destroy: true,
        language: Español,
        autoWidth: false,
        responsive: true,
        createdRow: function (row, data, dataIndex) {
            setTimeout(function () {
                const estado = data.estado ? data.estado.trim().toUpperCase() : '';
                const selectEstado = $(row).find('select[data-field="estado"]');

                // Limpiar estilos previos
                selectEstado.removeClass('text-warning text-success text-danger');
                $(row).find('td').not(':last').css('color', '').removeClass('font-weight-bold');

                // Determinar color según estado
                switch (estado) {
                    case 'EN CAMINO':
                        selectEstado.addClass('text-warning');
                        $(row).find('td').not(':last').css('color', '#fd7e14').addClass('font-weight-bold');
                        break;
                    case 'ENTREGADO':
                        selectEstado.addClass('text-success');
                        $(row).find('td').not(':last').css('color', '#28a745').addClass('font-weight-bold');
                        break;
                }

                // Listener para cambio de estado
                selectEstado.off('change').on('change', function () {
                    const nuevoEstado = $(this).val().trim().toUpperCase();

                    // Limpiar clases anteriores
                    $(this).removeClass('text-warning text-success text-danger');
                    $(row).find('td').not(':last').css('color', '').removeClass('font-weight-bold');

                    // Aplicar nuevo estilo
                    switch (nuevoEstado) {
                        case 'EN CAMINO':
                            $(this).addClass('text-warning');
                            $(row).find('td').not(':last').css('color', '#fd7e14').addClass('font-weight-bold');
                            break;
                        case 'ENTREGADO':
                            $(this).addClass('text-success');
                            $(row).find('td').not(':last').css('color', '#28a745').addClass('font-weight-bold');
                            break;
                    }
                });
            }, 50);
        },
        ajax: {
            method: "GET",
            url: url,
            dataSrc: function (json) {
                //console.log(json);
                return json;
            }
        },
        columns: [
            { data: "conductor" },
            { data: "unidad" },
            { data: "fecha_inicio" },
            { data: "fecha_fin" },
            { data: "observaciones" },
            { data: "dest_origen" },
            { data: "dest_llegada" },
            {
                data: "estado",
                width: "12%",
                render: function (data, type, row) {
                    const estado = data ? data.trim().toUpperCase() : '';
                    let claseColor = '';
                    if (estado === 'EN CAMINO') claseColor = 'text-warning';
                    else if (estado === 'ENTREGADO') claseColor = 'text-success';
                    else if (estado === 'INACTIVO') claseColor = 'text-danger';

                    return `
                        <div class="input-group input-group-sm">
                            <select class="form-control form-control-sm perfil-input font-weight-bold ${claseColor}" 
                                    data-field="estado" 
                                    data-id="${row.idviaje}">
                                <option value="EN CAMINO" ${estado === 'EN CAMINO' ? 'selected' : ''} class="text-warning">EN CAMINO</option>
                                <option value="ENTREGADO" ${estado === 'ENTREGADO' ? 'selected' : ''} class="text-success">ENTREGADO</option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-warning btn-sm" onclick="editarViaje(this, ${row.idviaje})">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </div>
                    `;
                }
            },
            {
                data: null,
                orderable: false,
                width: "5%",
                render: function (data, type, row) {
                    return `
                        <div class="d-flex flex-row justify-content-center">
                            <button class="btn btn-info" onclick="abrirModalServicios(${row.idviaje}, '${row.estado}')">
                                <i class="fa fa-file-alt"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });
}

function limpiar() {
    // Limpiar descripción
    $("#txtdescripcion").val("");

    // Restablecer selects a su valor inicial (primera opción)
    $("#cmbconductor").prop('selectedIndex', 0);
    $("#cmbvehiculo").prop('selectedIndex', 0);
    $("#cmborigen").prop('selectedIndex', 0);
    $("#cmbdestino").prop('selectedIndex', 0);

    // Establecer fecha actual
    var fechaHoy = new Date().toISOString().split('T')[0];
    $("#dtfinicio").val(fechaHoy);
    $("#dtffin").val(fechaHoy);
}

function registrarViaje() {
    var idconductor = $("#cmbconductor").val();
    var idunidad = $("#cmbvehiculo").val();
    var fecha_inicio = $("#dtfinicio").val();
    var fecha_fin = $("#dtffin").val();
    var descripcion = $("#txtdescripcion").val();
    var desti_origen = $("#cmborigen").val();
    var desti_llegada = $("#cmbdestino").val();
    if ($('#txtdescripcion').val() === '') {
        Swal.fire('REGISTRO DE VIAJE', 'La descripción es obligatoria', 'error');
        $('#txtdescripcion').focus();
        return;
    } else {
        var parametros = "idconductor=" + idconductor +
            "&idunidad=" + idunidad +
            "&f_inicio=" + fecha_inicio +
            "&f_fin=" + fecha_fin +
            "&observaciones=" + descripcion +
            "&destorigen=" + desti_origen +
            "&destllegada=" + desti_llegada;
        //console.log(parametros);
        $.ajax({
            type: "POST",
            url: baseURL + "mant_viajes/registrar_viaje",
            data: parametros,
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        title: "REGISTRO DE VIAJE",
                        text: response.error,
                        icon: "error",
                    });
                } else {
                    Swal.fire({
                        icon: "success",
                        title: "REGISTRO DE VIAJE",
                        text: response.message,
                    }).then(function () {
                        $('#tblviajes').DataTable().ajax.reload(null, false);
                        $('#mdlviaje').modal('hide');
                        limpiar();
                        // Esperar a que el modal se cierre completamente antes de abrir el de servicios
                        if (response.idviaje) {
                            $('#mdlviaje').on('hidden.bs.modal', function() {
                                $(this).off('hidden.bs.modal');
                                abrirModalServicios(response.idviaje, 'EN CAMINO');
                            });
                        }
                    });
                }
            },
        });
    }
}

function editarViaje(btn, idviaje) {
    var row = $(btn).closest('tr');
    var estado = row.find('select[data-field="estado"]').val()

    // Si el estado es ENTREGADO, validar servicios primero
    if (estado.toUpperCase() === 'ENTREGADO') {
        $.ajax({
            type: "POST",
            url: baseURL + 'mant_viajes/validar_estado_servicios',
            data: 'idviaje=' + idviaje,
            success: function (validationResponse) {
                if (validationResponse.error) {
                    Swal.fire({
                        icon: "error",
                        title: 'VALIDACIÓN SERVICIOS',
                        text: validationResponse.error
                    });
                } else if (!validationResponse.puede_entregar) {
                    Swal.fire({
                        icon: "warning",
                        title: 'SERVICIOS PENDIENTES',
                        text: 'No se puede marcar el viaje como ENTREGADO. El viaje tiene servicios pendientes.',
                        confirmButtonText: 'Entendido'
                    }).then(function () {
                        $('#tblviajes').DataTable().ajax.reload(null, false);
                    });
                } else {
                    // Todos los servicios están entregados, proceder con el cambio
                    ejecutarCambioEstado(estado, idviaje);
                }
            }
        });
    } else {
        // Si no es ENTREGADO, proceder normalmente
        ejecutarCambioEstado(estado, idviaje);
    }
}

function ejecutarCambioEstado(estado, idviaje) {
    var parametros = 'estado=' + estado + '&cod=' + idviaje;
    $.ajax({
        type: "POST",
        url: baseURL + 'mant_viajes/editar_viaje',
        data: parametros,
        success: function (response) {
            //console.log(response);
            if (response.error) {
                Swal.fire({
                    icon: "error",
                    title: 'EDICION VIAJE',
                    text: response.error
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'EDICION VIAJE',
                    text: response.message,
                }).then(function () {
                    $('#tblviajes').DataTable().ajax.reload(null, false);
                });
            }
        }
    });
}