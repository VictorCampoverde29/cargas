table = "";

$(document).ready(function () {
    $("#cmbtipocarga").on("change", function () {
        const valor = $(this).val();

        if (valor === "OTRO") {
            $("#txtcargaserv").prop("disabled", false).val("").focus();
        } else {
            $("#txtcargaserv").prop("disabled", true).val();
        }
    });

    // Ejecutar una vez al cargar por si hay valor preseleccionado
    $("#cmbtipocarga").trigger("change");
});

function abrirModalServicios(idviaje) {
    $("#txtidguia").val(idviaje);
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

    $("#txtviajeprin").val(datos.numero);
    $("#dtfserv").val(datos.fecha_emision);
    $("#txtorigserv").val(datos.dir_partida);
    $("#txtllegserv").val(datos.dir_llegada);
    $("#txtflete").val(datos.pagaflete);
    $("#txtemisor").val(datos.remitente_nombre);
    $("#txtreceptor").val(datos.destinatario_nombre);
    $("#txtglosaserv").val(datos.glosa);
    $("#txtestado").val(datos.estado);
}

function registrarServicio() {
    var idviaje = $("#txtidguia").val();
    var nguia = $("#txtviajeprin").val();
    var fservicio = $("#dtfserv").val();
    var flete = $("#txtflete").val();
    var glosa = $("#txtglosaserv").val();
    var emisor = $("#txtemisor").val();
    var receptor = $("#txtreceptor").val();
    var origen = $("#txtorigserv").val();
    var llegada = $("#txtllegserv").val();
    var tcarga = $("#cmbtipocarga").val();
    var tinputcarga = $("#txtcargaserv").val();
    var estado = $("#cmbestadoserv").val();
    console.log("Ejecutando registrarServicio()");
    if (nguia.trim() === "") {
        Swal.fire({
            title: "REGISTRO DE SERVICIO",
            text: "FALTA INGRESAR NUMERO DE GUIA!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#txtviajeprin");

                // Enfocar el campo inmediatamente
                documentoField.focus();

                // Mantener el focus por más tiempo (vuelve a enfocar después de 300ms)
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    } else if (emisor.trim() === "") {
        Swal.fire({
            title: "REGISTRO DE SERVICIO",
            text: "FALTA INGRESAR EMISOR!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#txtemisor");

                // Enfocar el campo inmediatamente
                documentoField.focus();

                // Mantener el focus por más tiempo (vuelve a enfocar después de 300ms)
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    } else if (receptor.trim() === "") {
        Swal.fire({
            title: "REGISTRO DE SERVICIO",
            text: "FALTA INGRESAR RECEPTOR!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#txtreceptor");

                // Enfocar el campo inmediatamente
                documentoField.focus();

                // Mantener el focus por más tiempo (vuelve a enfocar después de 300ms)
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    } else if (origen.trim() === "") {
        Swal.fire({
            title: "REGISTRO DE SERVICIO",
            text: "FALTA INGRESAR ORIGEN!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#txtorigserv");

                // Enfocar el campo inmediatamente
                documentoField.focus();

                // Mantener el focus por más tiempo (vuelve a enfocar después de 300ms)
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    } else if (llegada.trim() === "") {
        Swal.fire({
            title: "REGISTRO DE SERVICIO",
            text: "FALTA INGRESAR LLEGADA!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#txtllegserv");

                // Enfocar el campo inmediatamente
                documentoField.focus();

                // Mantener el focus por más tiempo (vuelve a enfocar después de 300ms)
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    } else {
        if (tcarga === "OTRO") {
            $.ajax({
                "type": "POST",
                "url": baseURL + "mant_carga/agregar_carga",
                "data": { "descripcion": tinputcarga, estado: "ACTIVO" },
                success: function (response) {
                    if (response.idcarga) {
                        registrarServicioFinal({
                            idviaje, idcarga: response.idcarga, nguia, fservicio, origen, llegada,
                            flete, emisor, receptor, glosa, estado
                        });
                    } else {
                        Swal.fire("Error", "No se pudo registrar el nuevo tipo de carga.", "error");
                    }
                },
                error: function () {
                    Swal.fire("Error", "Error al registrar la nueva carga.", "error");
                }
            });
        } else {
            registrarServicioFinal({
                idviaje, idcarga: tcarga, nguia, fservicio, origen, llegada,
                flete, emisor, receptor, glosa, estado
            });
        }

    }
}

function registrarServicioFinal(data) {
    var parametros = {
        "idviaje": data.idviaje,
        "idcarga": data.idcarga,
        "nguia": data.nguia,
        "f_servicio": data.fservicio,
        "origen": data.origen,
        "destino": data.llegada,
        "flete": data.flete,
        "emisor": data.emisor,
        "receptor": data.receptor,
        "glosa": data.glosa,
        "estado": data.estado
    };
    $.ajax({
        type: "POST",
        url: baseURL + "servicios/reg_servicio",
        data: parametros,
        success: function (response) {
            if (response.error) {
                Swal.fire({
                    title: "REGISTRO SERVICIO",
                    text: response.error,
                    icon: "error",
                });
            } else {
                Swal.fire({
                    icon: "success",
                    title: "REGISTRO SERVICIO",
                    text: response.message,
                }).then(function () {
                    var paginaActual = table.page.info().page;
                    table.ajax.reload();
                    setTimeout(function () {
                        table.page(paginaActual).draw("page");
                    }, 800);
                });
            }
            $('#mdlservicios').modal('hide');
        },
    });
}
