table = '';
$(document).ready(function () {
    cargarParametros();
    cargarCondiciones();
    cargarCategorias();
    $('#cmborigenparametros, #cmbdestinosparametros').on('change', function() {
        let origen = $('#cmborigenparametros').val();
        let destino = $('#cmbdestinosparametros').val();
        $('#cmborigenparametros option, #cmbdestinosparametros option').show();
        if (destino) {
            $('#cmborigenparametros option[value="' + destino + '"]').hide();
        }
        if (origen) {
            $('#cmbdestinosparametros option[value="' + origen + '"]').hide();
        }
    });
    $('#cmborigenparametros, #cmbdestinosparametros').trigger('change');
});

function abrirModalCondicion() {
    limpiarCondciones();
    $('#btnregistrarcondi').removeClass('d-none');
    $('#btneditarcondi').addClass('d-none');
    $('#lbltitulocondicion').html('REGISTRAR NUEVA CONDICION');
    $('#mdlcondiciones .modal-header').removeClass('bg-warning').addClass('bg-success');
    $('#mdlcondiciones').modal('show');
}

function limpiarCondciones() {
    $('#txtdescripcioncondi').val('');
    $('#cmbestadocondi').val('ACTIVO');
}

function cargarParametros() {
    const url = baseURL + "mant_parametros/datatables"; // Asegúrate que esta ruta coincide con la definida en Routes.php
    table = $("#tblparametrosviaje").DataTable({
        "destroy": true,
        "language": Español,
        "autoWidth": false,
        "responsive": true,
        "createdRow": function (row, data, dataIndex) {
            if (data.carreta && data.carreta.trim().toUpperCase() === 'SI') {
                $(row).addClass('text-warning');
            }
        },
        "ajax": {
            "method": "GET",
            "url": url,
            "dataSrc": function (json) {
                //console.log(json);
                return json;
            }
        },
        "columns": [
            { "data": "viaje" },
            { "data": "unidad" },
            { "data": "condicion" },
            { "data": "carreta" },
            { "data": "galones" },
            { "data": "peajes" },
            {
                "data": null,
                "width": "5%",
                "className": "text-center",
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                    <button class="btn btn-sm btn-warning" onclick="mostrarDatosP('${row.idparametros_viaje}')" title="EDITAR"><i class="fas fa-pencil-alt"></i></button>
                    `;
                }
            }
        ],
    });
}

function abrirModalParametros() {
    $('#btnregistrarparametros').removeClass('d-none');
    $('#btneditarparametros').addClass('d-none');
    $('#lbltituloparametros').html('Registrar Nuevo Parametro');
    $('#mdlparametros .modal-header').removeClass('bg-warning').addClass('bg-success');
    $('#mdlparametros').modal('show');
}

function cargarCondiciones() {
    const url = baseURL + "mant_condiciones/datatables"; // Asegúrate que esta ruta coincide con la definida en Routes.php
    table = $("#tblcondicionesviaje").DataTable({
        "destroy": true,
        "language": Español,
        "autoWidth": false,
        "responsive": true,
        "createdRow": function (row, data, dataIndex) {
            if (data.estado && data.estado.trim().toUpperCase() === 'INACTIVO') {
                $(row).addClass('text-danger');
            }
        },
        "ajax": {
            "method": "GET",
            "url": url,
            "dataSrc": function (json) {
                //console.log(json);
                return json;
            }
        },
        "columns": [
            { "data": "descripcion" },
            {
                "data": "estado",
                "width": "12%",
                "className": "text-center",
                "render": function (data) {
                    // Convertir valores y asignar color
                    if (data === 'ACTIVO') {
                        return '<span class="text-success font-weight-bold">ACTIVO</span>';
                    } else if (data === 'INACTIVO') {
                        return '<span class="text-danger font-weight-bold">INACTIVO</span>';
                    }
                    return data;
                }
            },
            {
                "data": null,
                "width": "5%",
                "className": "text-center",
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                    <button class="btn btn-sm btn-warning" onclick="mostrarDatosX('${row.idcondiciones_parametros_gastoviaje}')" title="EDITAR"><i class="fas fa-pencil-alt"></i></button>
                    `;
                }
            }
        ],
    });
}

function agregarParametros() {
    var parametros = {
        origen: $('#cmborigenparametros').val(),
        destino: $('#cmbdestinosparametros').val(),
        unidad: $('#cmbvehiculosparametros').val(),
        condicion: $('#cmbcondicionesparametros').val(),
        carreta: $('#cmbcarretaparametros').val(),
        galones: $('#txtgalonesparametros').val(),
        peajes: $('#txtpeajesparametros').val(),
        estado: $('#cmbestadoparametros').val()
    };
    if (parametros.origen === parametros.destino) {
        Swal.fire('¡Atención!','El origen y destino no pueden ser iguales.','warning');
        return;
    }
    if (parametros.galones === '') {
        Swal.fire('¡Atención!','El campo de galones no puede estar vacío.','warning');
        return;
    }
    if (parametros.peajes === '') {
        Swal.fire('¡Atención!','El campo de peajes no puede estar vacío.','warning');
        return;
    }

    console.log(parametros);
    $.ajax({
        type: "POST",
        url: baseURL + "mant_parametros/registrar",
        data: parametros,
        success: function (response) {
            console.log(response);
            $('#mdlparametros').modal('hide');
            table.ajax.reload();
            Swal.fire(
                '¡Registrado!',
                'El parametro de viaje ha sido registrado exitosamente.',
                'success'
            );
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            Swal.fire(
                '¡Error!',
                'Hubo un problema al registrar el parametro de viaje.',
                'error'
            );
        }
    });
}

function mostrarDatosP(idparametro) {
    console.log("ID del parametro a editar:", idparametro);
    $.ajax({
        type: "POST",
        url: baseURL + "mant_parametros/parametros_xcod",
        data: { idparametros_viaje: idparametro },
        success: function (response) {
            console.log(response);
            $('#txtidparametros').val(response.idparametros_viaje);
            $('#cmborigenparametros').val(response.destino_origen);
            $('#cmbdestinosparametros').val(response.destino_destino);
            $('#cmbvehiculosparametros').val(response.unidad);
            $('#cmbcondicionesparametros').val(response.condicion);
            $('#cmbcarretaparametros').val(response.carreta);
            $('#txtgalonesparametros').val(response.galones);
            $('#txtpeajesparametros').val(response.peajes);
            $('#cmbestadoparametros').val(response.estado);
            $('#btnregistrarparametros').addClass('d-none');
            $('#btneditarparametros').removeClass('d-none');
            $('#lbltituloparametros').html('Editar Parametros de Viaje');
            $('#mdlparametros .modal-header').removeClass('bg-success').addClass('bg-warning');
            $('#mdlparametros').modal('show');
        }
    });
}

function editarParametros() {
    var parametros = {
        idparametros_viaje: $('#txtidparametros').val(),
        origen: $('#cmborigenparametros').val(),
        destino: $('#cmbdestinosparametros').val(),
        unidad: $('#cmbvehiculosparametros').val(),
        condicion: $('#cmbcondicionesparametros').val(),
        carreta: $('#cmbcarretaparametros').val(),
        galones: $('#txtgalonesparametros').val(),
        peajes: $('#txtpeajesparametros').val(),
        estado: $('#cmbestadoparametros').val()
    };
    if (parametros.origen === parametros.destino) {
        Swal.fire('¡Atención!','El origen y destino no pueden ser iguales.','warning');
        return;
    }
    if (parametros.galones === '') {
        Swal.fire('¡Atención!','El campo de galones no puede estar vacío.','warning');
        return;
    }
    if (parametros.peajes === '') {
        Swal.fire('¡Atención!','El campo de peajes no puede estar vacío.','warning');
        return;
    }
    console.log(parametros);
    $.ajax({
        type: "POST",
        url: baseURL + "mant_parametros/editar",
        data: parametros,
        success: function (response) {
            console.log(response);
            $('#mdlparametros').modal('hide');
            table.ajax.reload();
            Swal.fire(
                '¡Actualizado!',
                'El parametro de viaje ha sido actualizado exitosamente.',
                'success'
            );
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            Swal.fire(
                '¡Error!',
                'Hubo un problema al actualizar el parametro de viaje.',    
                'error'
            );
        }
    });
}

function cargarCategorias() {
     const url = baseURL + "mant_categoria/datatables"; // Asegúrate que esta ruta coincide con la definida en Routes.php
    table = $("#tblcategoriasviaje").DataTable({
        "destroy": true,
        "language": Español,
        "autoWidth": false,
        "responsive": true,
        "createdRow": function (row, data, dataIndex) {
            if (data.estado && data.estado.trim().toUpperCase() === 'INACTIVO') {
                $(row).addClass('text-warning');
            }
        },
        "ajax": {
            "method": "GET",
            "url": url,
            "dataSrc": function (json) {
                //console.log(json);
                return json;
            }
        },
        "columns": [
            { "data": "descripcion" },
            {
                "data": "estado",
                "width": "12%",
                "className": "text-center",
                "render": function (data) {
                    // Convertir valores y asignar color
                    if (data === 'ACTIVO') {
                        return '<span class="text-success font-weight-bold">ACTIVO</span>';
                    } else if (data === 'INACTIVO') {
                        return '<span class="text-danger font-weight-bold">INACTIVO</span>';
                    }
                    return data;
                }
            },
            {
                "data": null,
                "width": "5%",
                "className": "text-center",
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                    <button class="btn btn-sm btn-warning" onclick="mostrarDatosP('${row.idparametros_viaje}')" title="EDITAR"><i class="fas fa-pencil-alt"></i></button>
                    `;
                }
            }
        ],
    });
}