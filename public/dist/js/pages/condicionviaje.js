table = '';
$(document).ready(function () {
    cargarCondiciones();
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