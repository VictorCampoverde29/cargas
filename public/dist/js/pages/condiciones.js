table = '';
$(document).ready(function () {
    if ($('#tab-condiciones').hasClass('active')) {
        cargarCondiciones();
    }
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href");
        if (target === '#panel-condiciones') {
            cargarCondiciones();
        } else if (target === '#panel-categorias') {
            cargarCategorias();
        }
    });
    $('#cmborigenparametros, #cmbdestinosparametros').on('change', function () {
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
    limpiarCondiciones();
    $('#btnregistrarcondi').removeClass('d-none');
    $('#btneditarcondi').addClass('d-none');
    $('#lbltitulocondicion').html('Registrar Nueva Condición');
    $('#mdlcondiciones .modal-header').removeClass('bg-warning').addClass('bg-success');
    $('#mdlcondiciones').modal('show');
}

function limpiarCondiciones() {
    $('#txtdescripcioncondi').val('');
    $('#cmbestadocondi').val('ACTIVO');
}

function cargarCondiciones() {
    const url = baseURL + "mant_condiciones/datatables"; // Asegúrate que esta ruta coincide con la definida en Routes.php
    tableCondiciones = $("#tblcondicionesviaje").DataTable({
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
                    <button class="btn btn-sm btn-warning" onclick="mostrarDatosCondi('${row.idcondicion_gastoviaje}')" title="EDITAR"><i class="fas fa-pencil-alt"></i></button>
                    `;
                }
            }
        ],
    });
}

function cargarCategorias() {
    const url = baseURL + "mant_categoria/datatables";
    tableCategorias = $("#tblcategoriasviaje").DataTable({
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
                    <button class="btn btn-sm btn-warning" onclick="mostrarDatosCat('${row.idcategoria_viajes}')" title="EDITAR"><i class="fas fa-pencil-alt"></i></button>
                    `;
                }
            }
        ],
    });
}

function agregarCondicion() {
    var condicion = {
        descripcion: $('#txtdescripcioncondi').val(),
        estado: $('#cmbestadocondi').val()
    };
    // console.log(condicion);
    $.ajax({
        type: "POST",
        url: baseURL + "mant_condiciones/registrar",
        data: condicion,
        success: function (response) {
            // console.log(response);
            Swal.fire('¡Registrado!', 'La condición ha sido registrada exitosamente.', 'success').then(() => {
                $('#mdlcondiciones').modal('hide');
                var paginaActual = tableCondiciones.page();
                tableCondiciones.ajax.reload(function () {
                    tableCondiciones.page(paginaActual).draw(false);
                });
            });
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            Swal.fire('¡Error!', 'Hubo un problema al registrar la condición.', 'error');
        }
    });
}

function mostrarDatosCondi(idcondicion) {
    // console.log("ID de la condición a editar:", idcondicion);
    $.ajax({
        type: "POST",
        url: baseURL + "mant_condiciones/condiciones_xcod",
        data: { idcondicion_gastoviaje: idcondicion },
        success: function (response) {
            // console.log(response);
            $('#txtidcondicion').val(response.idcondicion_gastoviaje);
            $('#txtdescripcioncondi').val(response.descripcion);
            $('#cmbestadocondi').val(response.estado);
            $('#btnregistrarcondi').addClass('d-none');
            $('#btneditarcondi').removeClass('d-none');
            $('#lbltitulocondicion').html('Editar Condición de Viaje');
            $('#mdlcondiciones .modal-header').removeClass('bg-success').addClass('bg-warning');
            $('#mdlcondiciones').modal('show');
        }
    });
}

function editarCondicion() {
    var condicion = {
        idcondicion_gastoviaje: $('#txtidcondicion').val(),
        descripcion: $('#txtdescripcioncondi').val(),
        estado: $('#cmbestadocondi').val()
    };
    // console.log(condicion);
    $.ajax({
        type: "POST",
        url: baseURL + "mant_condiciones/editar",
        data: condicion,
        success: function (response) {
            // console.log(response);
            Swal.fire('¡Actualizado!', 'La condición ha sido actualizada exitosamente.', 'success').then(() => {
                $('#mdlcondiciones').modal('hide');
                var paginaActual = tableCondiciones.page();
                tableCondiciones.ajax.reload(function () {
                    tableCondiciones.page(paginaActual).draw(false);
                });
            });
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            Swal.fire('¡Error!', 'Hubo un problema al actualizar la condición.', 'error');
        }
    });
}

function abrirModalCategorias() {
    limpiarCategorias();
    $('#btnregistrarcategoria').removeClass('d-none');
    $('#btneditarcategoria').addClass('d-none');
    $('#lbltitulocategoria').html('Registrar Nueva Categoría');
    $('#mdlcategorias .modal-header').removeClass('bg-warning').addClass('bg-success');
    $('#mdlcategorias').modal('show');
}

function limpiarCategorias() {
    $('#txtdescripcioncategoria').val('');
    $('#cmbestadocategoria').val('ACTIVO');
}

function agregarCategoria() {
    var categoria = {
        descripcion: $('#txtdescripcioncategoria').val(),
        estado: $('#cmbestadocategoria').val()
    };
    // console.log(categoria);
    $.ajax({
        type: "POST",
        url: baseURL + "mant_categoria/registrar",
        data: categoria,
        success: function (response) {
            // console.log(response);
            Swal.fire('¡Registrado!', 'La categoría ha sido registrada exitosamente.', 'success').then(() => {
                $('#mdlcategorias').modal('hide');
                var paginaActual = tableCategorias.page();
                tableCategorias.ajax.reload(function () {
                    tableCategorias.page(paginaActual).draw(false);
                });
            });
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            Swal.fire('¡Error!', 'Hubo un problema al registrar la categoría.', 'error');
        }
    });
}

function mostrarDatosCat(idcategoria) {
    // console.log("ID de la categoría a editar:", idcategoria);
    $.ajax({
        type: "POST",
        url: baseURL + "mant_categoria/categoria_xcod",
        data: { idcategoria_viajes: idcategoria },
        success: function (response) {
            // console.log(response);
            $('#txtidcategoria').val(response.idcategoria_viajes);
            $('#txtdescripcioncategoria').val(response.descripcion);
            $('#cmbestadocategoria').val(response.estado);
            $('#btnregistrarcategoria').addClass('d-none');
            $('#btneditarcategoria').removeClass('d-none');
            $('#lbltitulocategoria').html('Editar Categoría');
            $('#mdlcategorias .modal-header').removeClass('bg-success').addClass('bg-warning');
            $('#mdlcategorias').modal('show');
        }
    });
}

function editarCategoria() {
    var categoria = {
        idcategoria_viajes: $('#txtidcategoria').val(),
        descripcion: $('#txtdescripcioncategoria').val(),
        estado: $('#cmbestadocategoria').val()
    };
    // console.log(categoria);
    $.ajax({
        type: "POST",
        url: baseURL + "mant_categoria/editar",
        data: categoria,
        success: function (response) {
            // console.log(response);
            Swal.fire('¡Actualizado!', 'La categoría ha sido actualizada exitosamente.', 'success').then(() => {
                $('#mdlcategorias').modal('hide');
                var paginaActual = tableCategorias.page();
                tableCategorias.ajax.reload(function () {
                    tableCategorias.page(paginaActual).draw(false);
                });
            });
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            Swal.fire('¡Error!', 'Hubo un problema al actualizar la categoría.', 'error');
        }
    });
}