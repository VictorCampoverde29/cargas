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
    $("#txtviajeprin").val("");
    $("#dtfserv").val("");
    $("#txtorigserv").val("");
    $("#txtllegserv").val("");
    $("#txtflete").val("");
    $("#txtemisor").val("");
    $("#txtreceptor").val("");
    $("#txtglosaserv").val("");
    $("#txtestado").val("");
    $("#txtidviaje").val(idviaje);
    $("#mdlservicios").modal("show");
    cargarServicios(idviaje);
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
    $("#mdlbuscarguia").modal("hide");
    // Forzar que el <body> mantenga la clase modal-open si hay otro modal abierto
    setTimeout(function () {
        if ($(".modal.show").length > 0) {
            $("body").addClass("modal-open");
        }
    }, 500);
}

function registrarServicio() {
    var idviaje = $("#txtidviaje").val();
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
                })
            }
        },
    });
}

function cargarServicios(cod) {
    $.ajax({
        type: "GET",
        url: baseURL + "servicios/datatables",
        data: { cod: cod },
        success: function (response) {
            if ($.fn.DataTable.isDataTable("#tblservicios")) {
                $("#tblservicios").DataTable().clear().destroy();
            }

            table = $("#tblservicios").DataTable({
                "language": Español,
                "autoWidth": true,
                "responsive": true,
                "data": response,
                "columnDefs": [{ "targets": 0, "visible": false }],
                "createdRow": function (row, data, dataIndex) {
                    const estado = data.estado ? data.estado.trim().toUpperCase() : '';
                    const selectEstado = $(row).find('select[data-field="estado"]');
                    selectEstado.removeClass('text-warning text-success text-danger');
                    $(row).find('td').not(':last').css('color', '').removeClass('font-weight-bold');

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
                },
                "columns": [
                    { "data": "idservicio" },
                    { "data": "n_guia" },
                    { "data": "fecha_servicio" },
                    { "data": "flete" },
                    { "data": "glosa" },
                    { "data": "emisor" },
                    { "data": "receptor" },
                    { "data": "origen" },
                    { "data": "destino" },
                    { "data": "nombre_carga" },
                    {
                        "data": "estado",
                        "width": "50px",
                        "render": function (data, type, row) {
                            const estado = data ? data.trim().toUpperCase() : '';
                            let claseColor = '';
                            if (estado === 'EN CAMINO') claseColor = 'text-warning';
                            else if (estado === 'ENTREGADO') claseColor = 'text-success';
                            else if (estado === 'INACTIVO') claseColor = 'text-danger';

                            return `
                                <div class="input-group input-group-sm">
                                    <select class="form-control form-control-sm perfil-input font-weight-bold ${claseColor}" 
                                            data-field="estado" 
                                            data-id="${row.idviaje || ''}">
                                        <option value="EN CAMINO" ${estado === 'EN CAMINO' ? 'selected' : ''}>EN CAMINO</option>
                                        <option value="ENTREGADO" ${estado === 'ENTREGADO' ? 'selected' : ''}>ENTREGADO</option>
                                    </select>
                                </div>`;
                        }
                    },
                    {
                        "data": null,
                        "orderable": false,
                        "render": function (data, type, row) {
                            return `
                                <div class="d-flex flex-row justify-content-center">
                                    <button class="btn btn-2 btn-warning btn-sm btn-pill w-80" onclick="editarViaje(this, ${row.idservicio || 0})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>`;
                        }
                    }
                ]
            });
        },
        error: function (xhr) {
            console.error("Error al traer servicios:", xhr.responseText);
        }
    });
}
