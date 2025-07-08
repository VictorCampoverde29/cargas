table = "";

$(document).ready(function () { });

function abrirModalServicios() {
    $("#mdlservicios").modal("show");
}

function abrirModalGuia() {
    $("#mdlbuscarguia").modal("show");
}

function traerGuias() {
    const fechaInicio = $("#dtfiniguia").val();
    const fechaFin = $("#dtffinguia").val();
    const idsucursal = $("#cmbsucursalguia").val();

    const url = baseURL + "guias/rango";

    var parametros = "fechaInicio=" + fechaInicio +
        "&fechaFin=" + fechaFin +
        "&codigosucursal=" + idsucursal;
    $.ajax({
        "url": url,
        "method": "GET",
        "data": parametros,
        success: function (data) {
            console.log(data);
            table = $("#tblguias").DataTable({
                "destroy": true,
                "language": Español,
                "autoWidth": true,
                "responsive": true,
                "data": data.data,
                "createdRow": function (row, data, dataIndex) {
                    setTimeout(function () {
                        var estadoSelect = $(row).find('select[data-field="estado"]');
                        var isInactivo =
                            data.estado && data.estado.trim().toUpperCase() === "ANULADA";

                        // Aplicar font-weight-bold al select de estado siempre
                        estadoSelect.addClass("font-weight-bold");

                        // Aplicar estilos según el estado
                        if (isInactivo) {
                            estadoSelect.addClass("text-danger");
                            $(row)
                                .find("input, select")
                                .not('[data-field="estado"]')
                                .css("color", "#dc3545");
                        } else {
                            estadoSelect.addClass("text-success");
                        }

                        // Listener para cambios de estado
                        estadoSelect.on("change", function () {
                            var nuevoInactivo = $(this).val() === "ANULADA";

                            if (nuevoInactivo) {
                                $(this).removeClass("text-success").addClass("text-danger");
                                $(row)
                                    .find("input, select")
                                    .not('[data-field="estado"]')
                                    .css("color", "#dc3545");
                            } else {
                                $(this).removeClass("text-danger").addClass("text-success");
                                $(row)
                                    .find("input, select")
                                    .not('[data-field="estado"]')
                                    .css("color", "");
                            }
                        });
                    }, 100);
                },
                "columns": [
                    { "data": "numero" },
                    { "data": "fecha_emision" },
                    { "data": "dir_partida" },
                    { "data": "dir_llegada" },
                    { "data": "idpaga_flete" },
                    { "data": "remitente_nombre" },
                    { "data": "destinatario_nombre" },
                    { "data": "glosa" },
                    { "data": "estado" }
                ],
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al obtener guías:", error);
        },
    });
}
