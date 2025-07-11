table = '';

$(document).ready(function () {
    cargarCarga();
});

function abrirModalCarga() {
    limpiarCarga();
    $('#btnregistrarc').removeClass('d-none');
    $('#btneditarc').addClass('d-none');
    $('#lbltituloc').html('REGISTRAR NUEVA FORMA DE PAGO');
    $('#mdlcarga .modal-header').removeClass('bg-warning').addClass('bg-success');
    $('#mdlcarga').modal('show');
}

function limpiarCarga() {
    $('#txtdescripcion').val('');
    $('#cmbestadocarga').val('ACTIVO');
}

function cargarCarga() {
    const url = baseURL + "mant_carga/datatables";
    table = $("#tblcarga").DataTable({
        "destroy": true,
        "language": Español,
        "autoWidth": false,
        "responsive": true,
        "ajax": {
            "method": "GET",
            "url": url,
            "dataSrc": function (json) {
                return json;
            }
        },
        "createdRow": function (row, data, dataIndex) {
            if (data.estado && data.estado.trim().toUpperCase() === 'INACTIVO') {
                $(row).addClass('text-danger');
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
                    <button class="btn btn-sm btn-warning" onclick="mostrarDatos('${row.idcarga}')" title="EDITAR"><i class="fas fa-pencil-alt"></i></button>
                    `;
                }
            }
        ],
    });
}

function agregarCarga() {
    var descripcion = $("#txtdescripcion").val();
    var estado = $("#cmbestadocarga").val();

    if (descripcion === "") {
        Swal.fire({
            title: "MANT. CARGA",
            text: "FALTA INGRESAR DESCRIPCION!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#txtdescripcion");

                // Enfocar el campo inmediatamente
                documentoField.focus();

                // Mantener el focus por más tiempo (vuelve a enfocar después de 300ms)
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    } else {
        var parametros = "descripcion=" + descripcion +
            "&estado=" + estado;
        $.ajax({
            type: "POST",
            url: baseURL + "mant_carga/agregar_carga",
            data: parametros,
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        title: "REGISTRO CARGA",
                        text: response.error,
                        icon: "error",
                    });
                } else {
                    Swal.fire({
                        icon: "success",
                        title: "REGISTRO DE CARGA",
                        text: response.message,
                    }).then(function () {
                        $('#tblcarga').DataTable().ajax.reload(null, false);
                        $('#mdlcarga').modal('hide');
                        limpiarCarga();
                    });
                }
            },
        });
    }
}

function mostrarDatos(cod) {
    var parametros = 'cod=' + cod;
    const url = baseURL + 'mant_carga/carga_xcod';
    $.ajax({
        type: "GET",
        url: url,
        data: parametros,
        success: function (response) {
            $('#txtidc').val(cod);
            $('#txtdescripcion').val(response[0].descripcion);
            $('#cmbestadocarga').val(response[0].estado);
            $('#btnregistrarc').addClass('d-none');
            $('#btneditarc').removeClass('d-none');
        },
        error: function () {
            Swal.fire('Error', 'No se pudieron cargar los datos de la carga', 'error');
        }
    });
    $('#lbltituloc').html('EDITAR CARGA');
    // Cambiar el color del header del modal a bg-warning
    $('#mdlcarga .modal-header').removeClass('bg-success').addClass('bg-warning');
    $('#mdlcarga').modal('show');
}

function editarCarga() {
    if ($('#txtdescripcion').val() === '') {
        Swal.fire('EDICIÓN DE CARGA', 'La descripción es obligatoria', 'error');
        $('#txtdescripcion').focus();
        return;
    }
    var parametros = {
        cod: $('#txtidc').val(),
        descripcion: $('#txtdescripcion').val(),
        estado: $('#cmbestadocarga').val()
    };

    $.ajax({
        type: "POST",
        url: baseURL + 'mant_carga/editar_carga',
        data: parametros,
        success: function (response) {
            if (response.error) {
                Swal.fire({
                    icon: "error",
                    title: 'EDICIÓN DE CARGA',
                    text: response.error
                });
            }
            else {
                Swal.fire({
                    icon: 'success',
                    title: 'EDICIÓN DE CARGA',
                    text: response.message,
                }).then(function () {
                    $('#tblcarga').DataTable().ajax.reload(null, false);
                    $('#mdlcarga').modal('hide');
                });
            }
        },
        error: function () {
            Swal.fire('Error', 'Ha ocurrido un error al editar la carga', 'error');
        }
    });
}