table = '';

$(document).ready(function () {
    cargarDestinos();
});

function abrirModalDestino() {
    limpiarDestinos();
    $('#btnregistrard').removeClass('d-none');
    $('#btneditard').addClass('d-none');
    $('#lbltitulod').html('REGISTRAR NUEVO DESTINO');
    $('#mdldestino .modal-header').removeClass('bg-warning').addClass('bg-success');
    $('#mdldestino').modal('show');
}

function limpiarDestinos() {
    $('#txtdescripciondestino').val('');
    $('#cmbestadodestino').val('ACTIVO');
}

function cargarDestinos() {
    const url = baseURL + "mant_destino/datatables"; // Asegúrate que esta ruta coincide con la definida en Routes.php
    table = $("#tbldestinos").DataTable({
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
            { "data": "nombre" },
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
                    <button class="btn btn-sm btn-warning" onclick="mostrarDatosX('${row.iddestino}')" title="EDITAR"><i class="fas fa-pencil-alt"></i></button>
                    `;
                }
            }
        ],
    });
}

function agregarDestino(){
    var descripcion = $("#txtdescripciondestino").val();
    var estado = $("#cmbestadodestino").val();

    if (descripcion === "") {
        Swal.fire({
            title: "MANT. DESTINOS",
            text: "FALTA INGRESAR DESCRIPCION!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#txtdescripciondestino");

                // Enfocar el campo inmediatamente
                documentoField.focus();

                // Mantener el focus por más tiempo (vuelve a enfocar después de 300ms)
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    }else{
        var parametros ="descripcion=" + descripcion +
            "&estado=" + estado;
        $.ajax({
            type: "POST",
            url: baseURL + "mant_destino/agregar_destino",
            data: parametros,
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        title: "REGISTRO DESTINO",
                        text: response.error,
                        icon: "error",
                    });
                } else {
                    Swal.fire({
                        icon: "success",
                        title: "REGISTRO DESTINO",
                        text: response.message,
                    }).then(function () {
                        $('#tbldestinos').DataTable().ajax.reload(null, false);
                        $('#mdldestino').modal('hide');
                        limpiarDestinos();
                    });
                }
            },
        });
    }
}

function mostrarDatosX(cod) {
    var parametros = 'cod=' + cod;
    const url = baseURL + 'mant_destino/destinos_xcod';
    $.ajax({
        type: "GET",
        url: url,
        data: parametros,
        success: function (response) {
            $('#txtidd').val(cod);
            $('#txtdescripciondestino').val(response[0].nombre);
            $('#cmbestadodestino').val(response[0].estado);
            $('#btnregistrard').addClass('d-none');
            $('#btneditard').removeClass('d-none');
        },
        error: function () {
            Swal.fire('Error', 'No se pudieron cargar los datos del destino', 'error');
        }
    });
    $('#lbltitulod').html('EDITAR DESTINO');
    // Cambiar el color del header del modal a bg-warning
    $('#mdldestino .modal-header').removeClass('bg-success').addClass('bg-warning');
    $('#mdldestino').modal('show');
}

function editarDestino() {
    if ($('#txtdescripciondestino').val() === '') {
        Swal.fire('EDICIÓN DE CARGA', 'La descripción es obligatoria', 'error');
        $('#txtdescripciondestino').focus();
        return;
    }
    var parametros = {
        cod: $('#txtidd').val(),
        nombre: $('#txtdescripciondestino').val(),
        estado: $('#cmbestadodestino').val()
    };
    $.ajax({
        type: "POST",
        url: baseURL + 'mant_destino/editar_destino',
        data: parametros,
        success: function (response) {
            if (response.error) {
                Swal.fire({
                    icon: "error",
                    title: 'EDICIÓN DE DESTINO',
                    text: response.error
                });
            }
            else {
                Swal.fire({
                    icon: 'success',
                    title: 'EDICIÓN DE DESTINO',
                    text: response.message,
                }).then(function () {
                    $('#tbldestinos').DataTable().ajax.reload(null, false);
                    $('#mdldestino').modal('hide');
                });
            }
        },
        error: function () {
            Swal.fire('Error', 'Ha ocurrido un error al editar el destino', 'error');
        }
    });
}