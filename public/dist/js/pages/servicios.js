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
                "data": data,
                "createdRow": function (row, data, dataIndex) {
                    if (data.estado.toUpperCase() === "ANULADA") {
                        $(row).css("color", "red");
                    } else if (data.estado.toUpperCase() === "REGISTRADA") {
                        $(row).css("color", "green");
                    }
                },
                "columns": [
                    { "data": "numero" },
                    { "data": "fecha_emision" },
                    { "data": "dir_partida" },
                    { "data": "dir_llegada" },
                    { "data": "pagaflete" },
                    { "data": "remitente_nombre" },
                    { "data": "destinatario_nombre" },
                    { "data": "glosa" },
                    { "data": "estado" },
                    {
                        "data": null,
                        "width": "10%",
                        "orderable": false,
                        "render": function (data, type, row, meta) {
                            return `<div class="d-flex flex-row gap-1 justify-content-center">
                                <button class="btn btn-warning btn-sm" onclick="llenarDatosInput(this)">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>`;
                        }
                    }
                ],
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al obtener guías:", error);
        },
    });
}

function llenarDatosInput(btn) {
    const table = $("#tblguias").DataTable();
    const fila = $(btn).closest("tr");
    const datos = table.row(fila.hasClass("child") ? fila.prev() : fila).data();

    console.log("Datos seleccionados:", datos);

    if (!datos) {
        console.error("No se pudo recuperar la fila correctamente.");
        return;
    }

    $("#txtnguiaserv").val(datos.numero);
    $("#txtviajeprin").val(datos.numero);
    $("#dtfserv").val(datos.fecha_emision);
    $("#txtremi").val(datos.dir_partida);
    $("#txtdesti").val(datos.dir_llegada);
    $("#txtflete").val(datos.pagaflete);
    $("#txtemisor").val(datos.remitente_nombre);
    $("#txtreceptor").val(datos.destinatario_nombre);
    $("#txtglosaserv").val(datos.glosa);
    $("#txtestado").val(datos.estado);
}

function registrarServicio(){
    var idconductor = $("#cmbconductor").val();
    var idunidad = $("#cmbvehiculo").val();
    var fecha_inicio = $("#dtfinicio").val();
    var fecha_fin = $("#dtffin").val();
    var descripcion = $("#txtdescripcion").val();
    var desti_origen = $("#cmborigen").val();
    var desti_llegada = $("#cmbdestino").val();

    if (descripcion === "") {
        Swal.fire({
            title: "REGISTRO DE VIAJE",
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
    }else{
        var parametros = "idconductor=" + idconductor +
            "&idunidad=" + idunidad +
            "&f_inicio=" + fecha_inicio +
            "&f_fin=" + fecha_fin +
            "&observaciones=" + descripcion +
            "&destorigen=" + desti_origen +
            "&destllegada=" + desti_llegada;
        console.log(parametros);
        $.ajax({
            type: "POST",
            url: baseURL + "mant_viajes/registrar_viaje",
            data: parametros,
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        title: "REGISTRO VIAJE",
                        text: response.error,
                        icon: "error",
                    });
                } else {
                    Swal.fire({
                        icon: "success",
                        title: "REGISTRO DE VIAJE",
                        text: response.message,
                    }).then(function () {
                        var paginaActual = table.page.info().page;
                        table.ajax.reload();
                        setTimeout(function () {
                            table.page(paginaActual).draw("page");
                        }, 800);
                    });
                }
                $('#mdlviaje').modal('hide');
            },
        });
    }
} 
