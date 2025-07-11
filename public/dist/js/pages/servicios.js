tableServicios = "";

$(document).ready(function () {
    $("#cmbtipocarga").on("change", function () {
        const valor = $(this).val();

        if (valor === "OTRO") {
            $("#txtcargaserv").show().val("").focus();
        } else {
            $("#txtcargaserv").hide().val("");
        }
    });
    // Ejecutar una vez al cargar por si hay valor preseleccionado
    $("#cmbtipocarga").trigger("change");
    // Event listener global para cuando se cierre el modal de buscar guía
    $("#mdlbuscarguia").on("hidden.bs.modal", function () {
        // Abrir automáticamente el modal de servicios
        $("#mdlservicios").modal("show");

        // Asegurar que el scroll funcione correctamente en el modal reabierto
        setTimeout(function () {
            const modalServiciosElement = $("#mdlservicios");
            if (modalServiciosElement.hasClass("show")) {
                // Forzar la correcta configuración del scroll
                modalServiciosElement.css("overflow-y", "auto");
                $("body").addClass("modal-open");
            }
        }, 300);
    });
    // Event listener para limpiar el modal de buscar guía al abrirlo
    $("#mdlbuscarguia").on("show.bs.modal", function () {
        const fechaActual = new Date();
        const primerDiaMes = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), 1);
        const fechaInicio = primerDiaMes.toISOString().split('T')[0];
        const fechaFin = fechaActual.toISOString().split('T')[0];
        $("#dtfiniguia").val(fechaInicio);
        $("#dtffinguia").val(fechaFin);
        $("#cmbsucursalguia option:first").prop('selected', true);
        if ($.fn.DataTable.isDataTable("#tblguias")) {
            $("#tblguias").DataTable().clear().destroy();
        }
        $("#tblguias tbody").empty();
    });
});

function abrirModalServicios(idviaje) {
    limpiarServicios();
    $("#txtidviaje").val(idviaje);
    $("#mdlservicios").modal("show");
    cargarServicios(idviaje);
}

function abrirModalGuia() {
    $("#mdlservicios").modal("hide");
    // Esperar a que el primer modal se cierre completamente antes de abrir el segundo
    $("#mdlservicios").on("hidden.bs.modal", function () {
        $(this).off("hidden.bs.modal"); // Remover el event listener después de usarlo
        $("#mdlbuscarguia").modal("show");
    });
}

function traerGuias() {
    const fechaInicio = $("#dtfiniguia").val();
    const fechaFin = $("#dtffinguia").val();
    const idsucursal = $("#cmbsucursalguia").val();
    const idviaje = $("#txtidviaje").val(); // Obtener el ID del viaje actual

    const url = baseURL + "guias/rango";

    var parametros = "fechaInicio=" + fechaInicio +
        "&fechaFin=" + fechaFin +
        "&codigosucursal=" + idsucursal +
        "&idviaje=" + idviaje; // Agregar idviaje a los parámetros
    $.ajax({
        "url": url,
        "method": "GET",
        "data": parametros,
        success: function (data) {
            //console.log(data);
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
                        "width": "7%",
                        "orderable": false,
                        "render": function (data, type, row, meta) {
                            return `<div class="d-flex flex-row gap-1 justify-content-center">
                                <button class="btn btn-primary btn-sm" onclick="llenarDatosInput(this)">
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

    // Solo cerrar el modal, el event listener global se encargará de abrir el otro
    $("#mdlbuscarguia").modal("hide");
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
    // Validaciones
    if (nguia.trim() === "") {
        Swal.fire('REGISTRO DE SERVICIO', 'El número de guía es obligatorio', 'error');
        $('#txtviajeprin').focus();
        return;
    }
    if (emisor.trim() === "") {
        Swal.fire('REGISTRO DE SERVICIO', 'El emisor es obligatorio', 'error');
        $('#txtemisor').focus();
        return;
    }
    if (receptor.trim() === "") {
        Swal.fire('REGISTRO DE SERVICIO', 'El receptor es obligatorio', 'error');
        $('#txtreceptor').focus();
        return;
    }
    if (origen.trim() === "") {
        Swal.fire('REGISTRO DE SERVICIO', 'El origen es obligatorio', 'error');
        $('#txtorigserv').focus();
        return;
    }
    if (llegada.trim() === "") {
        Swal.fire('REGISTRO DE SERVICIO', 'La llegada es obligatoria', 'error');
        $('#txtllegserv').focus();
        return;
    }
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
                }).then(() => {
                    // Recargar la tabla de servicios después del registro exitoso
                    cargarServicios(data.idviaje);
                    limpiarServicios();
                });
            }
        },
    });
}

function limpiarServicios() {
    $("#txtviajeprin").val("");
    var fechadHoy = new Date().toISOString().split('T')[0];
    $("#dtfserv").val(fechadHoy);
    $("#txtorigserv").val("");
    $("#txtllegserv").val("");
    $("#txtflete").val("");
    $("#txtemisor").val("");
    $("#txtreceptor").val("");
    $("#txtglosaserv").val("");
    $("#cmbtipocarga").val($("#cmbtipocarga option:first").val()).trigger('change');
    $("#cmbestadoserv").val("EN CAMINO");
}

function cargarServicios(cod) {
    const url = baseURL + "servicios/datatables";

    if ($.fn.DataTable.isDataTable("#tblservicios")) {
        $("#tblservicios").DataTable().clear().destroy();
    }

    tableServicios = $("#tblservicios").DataTable({
        "destroy": true,
        "language": Español,
        "lengthChange": true,
        "autoWidth": false,
        "responsive": true,
        "ajax": {
            'url': url,
            'method': 'GET',
            'data': { cod: cod },
            'dataSrc': function (json) {
                return json.data || json;
            }
        },
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
                "width": "12%",
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
                                        <option value="EN CAMINO" ${estado === 'EN CAMINO' ? 'selected' : ''} class="text-warning">EN CAMINO</option>
                                        <option value="ENTREGADO" ${estado === 'ENTREGADO' ? 'selected' : ''} class="text-success">ENTREGADO</option>
                                    </select>
                                </div>`;
                }
            },
            {
                "data": null,
                "orderable": false,
                "render": function (data, type, row) {
                    let botones = `
                                <div class="d-flex flex-row justify-content-center gap-1">
                                    <button class="btn btn-2 btn-warning btn-sm btn-pill" onclick="editarServicio(this, ${row.idservicio || 0})">
                                        <i class="fas fa-check"></i>
                                    </button>`;

                    // Agregar botón rojo si tiene venta asociada
                    if (row.tiene_venta && row.tiene_venta == 1) {
                        botones += `
                                    <button class="btn btn-danger btn-sm" onclick="verVenta('${row.n_guia}')" title="Ver venta asociada">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </button>`;
                    }

                    botones += `</div>`;
                    return botones;
                }
            }
        ]
    });

    // Agregar event listener para cambio de color inmediato en el select
    $('#tblservicios').on('change', 'select[data-field="estado"]', function () {
        const nuevoEstado = $(this).val().toUpperCase();
        const fila = $(this).closest('tr');
        const selectEstado = $(this);

        // Remover clases anteriores
        selectEstado.removeClass('text-warning text-success text-danger');
        fila.find('td').not(':last').css('color', '').removeClass('font-weight-bold');

        // Aplicar nuevos colores según el estado
        switch (nuevoEstado) {
            case 'EN CAMINO':
                selectEstado.addClass('text-warning');
                fila.find('td').not(':last').css('color', '#fd7e14').addClass('font-weight-bold');
                break;
            case 'ENTREGADO':
                selectEstado.addClass('text-success');
                fila.find('td').not(':last').css('color', '#28a745').addClass('font-weight-bold');
                break;
        }
    });
}

function editarServicio(btn, idservicio) {
    const table = $("#tblservicios").DataTable();
    const fila = $(btn).closest("tr");
    const datos = table.row(fila.hasClass("child") ? fila.prev() : fila).data();

    if (!datos) {
        console.error("No se pudo recuperar la fila correctamente.");
        return;
    }
    // Obtener el estado actual del select en la fila
    const selectEstado = $(fila).find('select[data-field="estado"]');
    const estadoActual = selectEstado.val();

    // Realizar la petición AJAX para actualizar directamente
    $.ajax({
        type: "POST",
        url: baseURL + "servicios/editar",
        data: {
            cod: idservicio,
            estado: estadoActual
        },
        success: function (response) {
            if (response.error) {
                Swal.fire({
                    title: "Error",
                    text: response.error,
                    icon: "error",
                    confirmButtonText: "OK"
                });
            } else {
                Swal.fire({
                    icon: "success",
                    title: "SERVICIO ACTUALIZADO",
                    text: "El estado del servicio se actualizó correctamente.",
                    confirmButtonText: "OK"
                });

                // Recargar la tabla para reflejar los cambios
                const idviaje = $("#txtidviaje").val();
                cargarServicios(idviaje);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error al actualizar servicio:", error);
            Swal.fire({
                title: "Error",
                text: "Error al actualizar el estado del servicio",
                icon: "error"
            });
        }
    });
}

function verVenta(numeroGuia) {
    $.ajax({
        type: "GET",
        url: baseURL + "servicios/verificar_venta",
        data: { numero_guia: numeroGuia },
        success: function (response) {
            if (response && response.length > 0) {
                let ventaInfo = response[0];
                Swal.fire({
                    title: "Venta Asociada",
                    html: `
                        <div class="text-left">
                            <p><strong>Número de Documento:</strong> ${ventaInfo.numero_doc}</p>
                            <p><strong>Fecha de Emisión:</strong> ${ventaInfo.fecha_emision}</p>
                            <p><strong>Importe Total:</strong> S/ ${parseFloat(ventaInfo.importe_total).toFixed(2)}</p>
                            <p><strong>Almacén:</strong> ${ventaInfo.almacen_nombre || 'N/A'} (ID: ${ventaInfo.idalmacen})</p>
                            <p><strong>Estado:</strong> <span class="badge ${ventaInfo.estado === 'ANULADA' ? 'badge-danger' : 'badge-success'}">${ventaInfo.estado}</span></p>
                        </div>
                    `,
                    icon: "info",
                    confirmButtonText: "Cerrar"
                });
            } else {
                Swal.fire({
                    title: "Sin ventas",
                    text: "No se encontraron ventas asociadas con esta guía en los almacenes de servicios (20, 39, 40).",
                    icon: "warning"
                });
            }
        },
        error: function (xhr) {
            console.error("Error al verificar venta:", xhr.responseText);
            Swal.fire({
                title: "Error",
                text: "Error al consultar la venta asociada.",
                icon: "error"
            });
        }
    });
}
