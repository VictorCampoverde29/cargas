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

    // Event listener para limpiar event listeners cuando se cierre el modal de servicios
    $("#mdlservicios").on("hidden.bs.modal", function () {
        $(window).off('resize.servicios');
    });

    // Event listener para mantener el scroll en el modal de servicios cuando se cierre el modal PDF
    $("#modalpdf").on("hidden.bs.modal", function () {
        // Asegurar que el modal de servicios mantenga el scroll
        setTimeout(function () {
            const modalServicios = $("#mdlservicios");
            if (modalServicios.hasClass("show") && modalServicios.data("mantener-scroll")) {
                modalServicios.css("overflow-y", "auto");
                $("body").addClass("modal-open");
                // Solo quitar el overflow:hidden si no hay otros modales abiertos
                if ($('.modal.show').length === 1) {
                    // Solo el modal de servicios está abierto
                    $("body").css("overflow", "auto");
                }
                // Limpiar el flag
                modalServicios.removeData("mantener-scroll");
            }
        }, 100);
    });
});

// Función para actualizar la tabla de viajes en la página padre
function actualizarViajesEnPaginaPadre() {
    // Intentar diferentes métodos para actualizar la tabla de viajes
    if (typeof cargarViajes === 'function') {
        cargarViajes();
    } else if (window.parent && typeof window.parent.cargarViajes === 'function') {
        window.parent.cargarViajes();
    } else if (window.opener && typeof window.opener.cargarViajes === 'function') {
        window.opener.cargarViajes();
    } else {
        // Si no encuentra la función, recargar la página padre
        if (window.parent && window.parent !== window) {
            window.parent.location.reload();
        } else if (window.opener) {
            window.opener.location.reload();
        }
    }
}

function abrirModalServicios(idviaje, estadoViaje = '') {
    limpiarServicios();
    $("#txtidviaje").val(idviaje);
    $("#mdlservicios").modal("show");
    cargarServicios(idviaje);
    llenarSelectCarga();
    // Si el viaje está entregado, bloquear todo el modal
    if (estadoViaje && estadoViaje.trim().toUpperCase() === 'ENTREGADO') {
        bloquearModalServicios();
    } else {
        desbloquearModalServicios();
    }
}

function bloquearModalServicios() {
    // Mostrar mensaje informativo
    const mensajeBloqueo = `
        <div class="alert alert-warning mb-3" id="alertViajeEntregado">
            <i class="fas fa-lock"></i>
            <strong>VIAJE ENTREGADO:</strong> No se pueden realizar cambios en los servicios.
        </div>
    `;
    if ($('#alertViajeEntregado').length === 0) {
        $('#mdlservicios .modal-body').prepend(mensajeBloqueo);
    }
    // Ocultar el formulario de registro de servicios usando IDs específicos
    $('#txtviajeprin, #btneleremi, #txtflete, #txtglosaserv').closest('.row').hide();
    $('#txtemisor, #txtreceptor').closest('.row').hide();
    $('#txtorigserv, #txtllegserv').closest('.row').hide();
    $('#cmbtipocarga, #cmbestadoserv, #dtfserv').closest('.row').hide();
    // Marcar que el modal está bloqueado para aplicar el bloqueo después del redibujado
    $('#mdlservicios').data('bloqueado', true);
    // Deshabilitar elementos de la tabla inmediatamente
    aplicarBloqueoTabla();
}

function desbloquearModalServicios() {
    $('#alertViajeEntregado').remove();
    // Mostrar todas las filas del formulario usando IDs específicos
    $('#txtviajeprin, #btneleremi, #txtflete, #txtglosaserv').closest('.row').show();
    $('#txtemisor, #txtreceptor').closest('.row').show();
    $('#txtorigserv, #txtllegserv').closest('.row').show();
    $('#cmbtipocarga, #cmbestadoserv, #dtfserv').closest('.row').show();
    // Marcar que el modal ya no está bloqueado
    $('#mdlservicios').data('bloqueado', false);

    // Habilitar elementos de la tabla (incluyendo modo responsivo)
    $('#tblservicios select, #tblservicios button').prop('disabled', false);
    $('#tblservicios_wrapper .dtr-details select, #tblservicios_wrapper .dtr-details button').prop('disabled', false);
    $('#tblservicios_wrapper select[data-field="estado"], #tblservicios_wrapper button').prop('disabled', false);
}

// Función auxiliar para aplicar el bloqueo a la tabla
function aplicarBloqueoTabla() {
    if ($('#mdlservicios').data('bloqueado') === true) {
        // Deshabilitar todos los select y botones, excepto los que tengan la clase btn-nunca-bloquear
        $('#tblservicios select').prop('disabled', true);
        $('#tblservicios button:not(.btn-nunca-bloquear)').prop('disabled', true);

        // También buscar en los elementos expandidos del modo responsivo
        $('#tblservicios_wrapper .dtr-details select').prop('disabled', true);
        $('#tblservicios_wrapper .dtr-details button:not(.btn-nunca-bloquear)').prop('disabled', true);

        // Aplicar a todos los elementos de control dentro del wrapper de DataTables
        $('#tblservicios_wrapper select[data-field="estado"]').prop('disabled', true);
        $('#tblservicios_wrapper button:not(.btn-nunca-bloquear)').prop('disabled', true);

        // Usar un timeout pequeño para asegurar que se aplique después del renderizado
        setTimeout(function () {
            $('#tblservicios select').prop('disabled', true);
            $('#tblservicios button:not(.btn-nunca-bloquear)').prop('disabled', true);
            $('#tblservicios_wrapper .dtr-details select').prop('disabled', true);
            $('#tblservicios_wrapper .dtr-details button:not(.btn-nunca-bloquear)').prop('disabled', true);
            $('#tblservicios_wrapper select[data-field="estado"]').prop('disabled', true);
            $('#tblservicios_wrapper button:not(.btn-nunca-bloquear)').prop('disabled', true);
        }, 100);
    }
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
        url: url,
        method: "GET",
        data: parametros,
        success: function (data) {
            // Decodificar los campos de nombre antes de pasar a DataTable
            var decodedData = (data.data || data).map(function (item) {
                if (item.remitente_nombre) item.remitente_nombre = decodeHtml(item.remitente_nombre);
                if (item.destinatario_nombre) item.destinatario_nombre = decodeHtml(item.destinatario_nombre);
                return item;
            });
            table = $("#tblguias").DataTable({
                destroy: true,
                language: Español,
                autoWidth: true,
                responsive: true,
                data: decodedData,
                createdRow: function (row, data, dataIndex) {
                    if (data.estado.toUpperCase() === "ANULADA") {
                        $(row).css("color", "red");
                    } else if (data.estado.toUpperCase() === "REGISTRADA") {
                        $(row).css("color", "green");
                    }
                },
                columns: [
                    { data: "numero" },
                    { data: "fecha_emision" },
                    { data: "dir_partida" },
                    { data: "dir_llegada" },
                    { data: "pagaflete" },
                    { data: "remitente_nombre" },
                    { data: "destinatario_nombre" },
                    { data: "glosa" },
                    { data: "estado" },
                    {
                        data: null,
                        width: "7%",
                        orderable: false,
                        render: function (data, type, row, meta) {
                            const estado = row.estado ? row.estado.toUpperCase() : '';
                            const disabled = (estado === 'ANULADA') ? 'disabled' : '';
                            return `<div class="d-flex flex-row gap-1 justify-content-center">
                                <button class="btn btn-primary btn-sm" onclick="llenarDatosInput(this)" ${disabled}>
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>`;
                        }
                    }
                ]
            });
        },
        error: function (xhr, status, error) {
            Swal.fire({
                title: "Error",
                text: "Error al obtener guías",
                icon: "error"
            });
        }
    });
}

// Función para decodificar entidades HTML recursivamente
function decodeHtml(html) {
    var txt = document.createElement('textarea');
    var last = html;
    var current = html;
    do {
        last = current;
        txt.innerHTML = last;
        current = txt.value;
    } while (current !== last);
    return current;
}

function llenarDatosInput(btn) {
    const table = $("#tblguias").DataTable();
    const fila = $(btn).closest("tr");
    const datos = table.row(fila.hasClass("child") ? fila.prev() : fila).data();

    if (!datos) {
        Swal.fire("Error", "No se pudo recuperar los datos de la guía", "error");
        return;
    }

    $("#txtviajeprin").val(datos.numero);
    $("#dtfserv").val(datos.fecha_emision);
    $("#txtorigserv").val(datos.dir_partida);
    $("#txtllegserv").val(datos.dir_llegada);
    $("#txtflete").val(datos.pagaflete);
    $("#txtemisor").val(decodeHtml(datos.remitente_nombre));
    $("#txtreceptor").val(decodeHtml(datos.destinatario_nombre));
    $("#txtglosaserv").val(datos.glosa);
    $("#txtestado").val(datos.estado);

    // Solo cerrar el modal, el event listener global se encargará de abrir el otro
    $("#mdlbuscarguia").modal("hide");
}

function registrarServicio() {
    var idviaje = $("#txtidviaje").val();
    var nguia = $("#txtviajeprin").val();
    // Normalizar número de guía: V###-00000000
    var normalizado = nguia.trim().toUpperCase();
    var matchNorm = normalizado.match(/^V(\d{3})-(\d{1,8})$/);
    if (matchNorm) {
        var serie = matchNorm[1];
        var correlativo = matchNorm[2].padStart(8, '0');
        normalizado = `V${serie}-${correlativo}`;
        // Actualizar el input para que el usuario vea el formato correcto
        $("#txtviajeprin").val(normalizado);
        nguia = normalizado;
    }
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
    // Validar formato de número de guía: V###-00000000 (8 dígitos correlativo, siempre con ceros a la izquierda)
    const guiaRegex = /^V\d{3}-\d{8}$/;
    if (!guiaRegex.test(nguia)) {
        Swal.fire('REGISTRO DE SERVICIO', 'El formato del número de guía es incorrecto. Correlativo + (8 dígitos con ceros a la izquierda)', 'error');
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
                    Swal.fire("REGISTRO DE SERVICIO", "Falta ingresar carga!", "error");
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
                    llenarSelectCarga();
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
        "drawCallback": function (settings) {
            // Aplicar bloqueo después de cada redibujado (incluye modo responsivo)
            aplicarBloqueoTabla();
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
                    let botones = `<div class="d-flex flex-row justify-content-center gap-1">
                                    <button class="btn btn-2 btn-warning btn-sm btn-pill" onclick="editarServicio(this, ${row.idservicio || 0})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-nunca-bloquear" onclick="verPdf('${row.n_guia}')" title="Ver PDF">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </button>`;

                    // Agregar botón azul si tiene venta asociada
                    if (row.tiene_venta && row.tiene_venta == 1) {
                        botones += `
                                    <button class="btn btn-primary btn-sm btn-nunca-bloquear" onclick="verVenta('${row.n_guia}')" title="Ver Venta">
                                        <i class="fa fa-file-invoice"></i>
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

    // Event listener para filas responsivas (cuando se expanden/colapsan)
    $('#tblservicios').on('click', 'td.dtr-control', function () {
        // Aplicar bloqueo después de que se expanda/colapse la fila responsiva
        setTimeout(function () {
            aplicarBloqueoTabla();
        }, 150);
    });

    // Event listener para cambios de tamaño de ventana que afecten el modo responsivo
    $(window).on('resize.servicios', function () {
        if ($('#mdlservicios').hasClass('show')) {
            setTimeout(function () {
                aplicarBloqueoTabla();
            }, 200);
        }
    });

    // Aplicar bloqueo inicial después de cargar los datos
    aplicarBloqueoTabla();
}

function editarServicio(btn, idservicio) {
    const table = $("#tblservicios").DataTable();
    const fila = $(btn).closest("tr");
    const datos = table.row(fila.hasClass("child") ? fila.prev() : fila).data();

    if (!datos) {
        Swal.fire("Error", "No se pudo recuperar los datos del servicio", "error");
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
                // Verificar si el mensaje indica que el viaje fue marcado como entregado
                const mensajeViajeEntregado = response.message && response.message.includes("viaje marcado como entregado");

                Swal.fire({
                    icon: "success",
                    title: "SERVICIO ACTUALIZADO",
                    text: response.message || "El estado del servicio se actualizó correctamente.",
                    confirmButtonText: "OK"
                }).then(() => {
                    // Recargar la tabla para reflejar los cambios
                    const idviaje = $("#txtidviaje").val();
                    cargarServicios(idviaje);

                    // Si el viaje fue marcado como entregado, actualizar inmediatamente
                    if (mensajeViajeEntregado) {
                        // Actualizar la tabla de viajes inmediatamente
                        actualizarViajesEnPaginaPadre();

                        // Cerrar el modal después de un breve delay solo para que el usuario vea el mensaje
                        setTimeout(() => {
                            $("#mdlservicios").modal("hide");
                        }, 600);
                    }
                });
            }
        },
        error: function (xhr, status, error) {
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
                if (response.length === 1) {
                    let ventaInfo = response[0];
                    let html = `<div class="text-left">
                        <p><strong>Número de Guía:</strong> ${ventaInfo.numero_guia || numeroGuia}</p>
                        <p><strong>Número de Documento:</strong> ${ventaInfo.numero_doc}</p>
                        <p><strong>Fecha de Emisión:</strong> ${ventaInfo.fecha_emision}</p>
                        <p><strong>Importe IGV:</strong> S/ ${parseFloat(ventaInfo.importe_igv).toFixed(2)}</p>
                        <p><strong>Sub Total:</strong> S/ ${parseFloat(ventaInfo.subtotal).toFixed(2)}</p>
                        <p><strong>Importe Total:</strong> S/ ${parseFloat(ventaInfo.importe_total).toFixed(2)}</p>
                        <p><strong>Sucursal:</strong> ${ventaInfo.sucursal_nombre || 'N/A'}</p>
                        <p><strong>Estado:</strong> <span class="badge ${ventaInfo.estado === 'ANULADA' ? 'badge-danger' : 'badge-success'}">${ventaInfo.estado}</span></p>
                    </div>`;
                    Swal.fire({
                        title: "Venta Asociada",
                        html: html,
                        icon: "info",
                        confirmButtonText: "Cerrar"
                    });
                } else {
                    // Modal tipo carrusel para navegar entre ventas
                    let idx = 0;
                    function mostrarVenta(index) {
                        let ventaInfo = response[index];
                        let html = `<div class='text-left'>
                            <p><strong>Número de Guía:</strong> ${ventaInfo.numero_guia || numeroGuia}</p>
                            <p><strong>Número de Documento:</strong> ${ventaInfo.numero_doc}</p>
                            <p><strong>Fecha de Emisión:</strong> ${ventaInfo.fecha_emision}</p>
                            <p><strong>Importe IGV:</strong> S/ ${parseFloat(ventaInfo.importe_igv).toFixed(2)}</p>
                            <p><strong>Sub Total:</strong> S/ ${parseFloat(ventaInfo.subtotal).toFixed(2)}</p>
                            <p><strong>Importe Total:</strong> S/ ${parseFloat(ventaInfo.importe_total).toFixed(2)}</p>
                            <p><strong>Sucursal:</strong> ${ventaInfo.sucursal_nombre || 'N/A'}</p>
                            <p><strong>Estado:</strong> <span class='badge ${ventaInfo.estado === 'ANULADA' ? 'badge-danger' : 'badge-success'}'>${ventaInfo.estado}</span></p>
                            <div class='mt-3 text-center'>
                                <button type='button' id='btnAnteriorVenta' class='btn btn-secondary btn-sm' ${index === 0 ? 'disabled' : ''} style='margin-right:10px;'>Anterior</button>
                                <span>Página ${index + 1} de ${response.length}</span>
                                <button type='button' id='btnSiguienteVenta' class='btn btn-secondary btn-sm' ${index === response.length - 1 ? 'disabled' : ''} style='margin-left:10px;'>Siguiente</button>
                            </div>
                        </div>`;
                        Swal.fire({
                            title: "Ventas Asociadas",
                            html: html,
                            icon: "info",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar",
                            didOpen: () => {
                                document.getElementById('btnAnteriorVenta').addEventListener('click', function (e) {
                                    if (idx > 0) {
                                        idx--;
                                        Swal.close();
                                        setTimeout(() => mostrarVenta(idx), 200);
                                    }
                                });
                                document.getElementById('btnSiguienteVenta').addEventListener('click', function (e) {
                                    if (idx < response.length - 1) {
                                        idx++;
                                        Swal.close();
                                        setTimeout(() => mostrarVenta(idx), 200);
                                    }
                                });
                            }
                        });
                    }
                    mostrarVenta(idx);
                }
            } else {
                Swal.fire({
                    title: "Sin ventas",
                    text: "No se encontraron ventas asociadas con esta guía en los almacenes de servicios (20, 39, 40).",
                    icon: "warning"
                });
            }
        },
        error: function (xhr) {
            Swal.fire({
                title: "Error",
                text: "Error al consultar la venta asociada.",
                icon: "error"
            });
        }
    });
}

function verPdf(numeroGuia) {
    $.ajax({
        type: "GET",
        url: baseURL + "servicios/obtener_id_guia",
        data: { numero_guia: numeroGuia },
        success: function (response) {
            if (response && response.idguia) {
                const pdfUrl = baseURL + 'ventas/generarPDF/' + response.idguia;
                const visor = baseURL + 'public/pdfjs/web/viewer.html?file=';
                const urlCompleta = visor + encodeURIComponent(pdfUrl);

                const iframe = document.getElementById('iframepdf');
                iframe.src = urlCompleta;

                // Asegurar que el modal de servicios mantenga su estado
                const modalServicios = $("#mdlservicios");
                if (modalServicios.hasClass("show")) {
                    modalServicios.data("mantener-scroll", true);
                }

                $("#modalpdf").modal("show");
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Guia no encontrada.",
                    icon: "error"
                });
            }
        },
        error: function (xhr, status, error) {
            Swal.fire({
                title: "Error",
                text: "Error al obtener la información de la guía.",
                icon: "error"
            });
        }
    });
}

function llenarSelectCarga() {
    var url = baseURL + 'mant_viajes/select_cargas';
    $.ajax({
        type: "GET",
        url: url,
        success: function (response) {
            const cargaSelect = $('#cmbtipocarga');
            cargaSelect.empty(); // Limpia el select existente
            // Llena el select con las cargas, excepto OTRO
            if (response.data && Array.isArray(response.data)) {
                response.data.forEach(function (carga) {
                    if (carga.descripcion && carga.descripcion.toUpperCase() !== 'OTRO') {
                        cargaSelect.append(
                            $('<option>', { value: carga.idcarga, text: carga.descripcion })
                        );
                    }
                });
            }
            // Agregar OTRO al final
            cargaSelect.append($('<option>', { value: 'OTRO', text: 'OTRO' }));
            // Disparar el evento change para actualizar el input si es necesario
            cargaSelect.trigger('change');
        },
        error: function (jqXHR, textStatus) {
            console.log('Error: ' + textStatus);
        }
    });
}
// Mostrar/ocultar el input de carga personalizada según selección
$(document).on('change', '#cmbtipocarga', function () {
    if ($(this).val() === 'OTRO') {
        $('#txtcargaserv').show().focus();
    } else {
        $('#txtcargaserv').hide();
    }
});
