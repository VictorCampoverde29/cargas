var tableViajesConductorVinculados = "";
var tableViajesConductorDisponibles = "";
var idviajeActual = null;
var abriendoModalParadas = false;

$(document).ready(function () {
    $("#filtrofechaini_vc, #filtrofechafin_vc").on("change", function () {
        var idviaje = $("#txtidviajeconductor").val();
        if (idviaje && $("#mdlviajesconductor").hasClass("show")) {
            cargarViajesConductorDisponibles(idviaje);
        }
    });

    $("#mdlparadasgastos").on("hidden.bs.modal", function () {
        if (idviajeActual) {
            setTimeout(function() {
                if (!$("#mdlparadasgastos").hasClass("show")) {
                    $("#mdlviajesconductor").modal("show");
                    setTimeout(function() {
                        recargarTablasViajesConductor(idviajeActual);
                    }, 100);
                }
            }, 350);
        } else {
            limpiarModalParadasGastos();
        }
    });

    $("#mdlviajesconductor").on("shown.bs.modal", function () {
        if (tableViajesConductorVinculados && $.fn.DataTable.isDataTable("#tblviajesconductorvinculados")) {
            tableViajesConductorVinculados.columns.adjust().responsive.recalc();
        }
        if (tableViajesConductorDisponibles && $.fn.DataTable.isDataTable("#tblviajesconductordisponibles")) {
            tableViajesConductorDisponibles.columns.adjust().responsive.recalc();
        }
    });

    $("#mdlviajesconductor").on("hidden.bs.modal", function () {
        if (!$("#mdlparadasgastos").hasClass("show") && !abriendoModalParadas) {
            idviajeActual = null;
        }
    });
});

function abrirModalViajesConductor(idviaje) {
    limpiarViajesConductor();
    idviajeActual = idviaje;
    $("#txtidviajeconductor").val(idviaje);
    
    var hoy = new Date();
    var primerDiaMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
    $("#filtrofechaini_vc").val(primerDiaMes.toISOString().split('T')[0]);
    $("#filtrofechafin_vc").val(hoy.toISOString().split('T')[0]);
    
    $("#mdlviajesconductor").modal("show");
    cargarViajesConductorVinculados(idviaje);
    cargarViajesConductorDisponibles(idviaje);
}

function cargarViajesConductorVinculados(idviaje) {
    if ($.fn.DataTable.isDataTable("#tblviajesconductorvinculados")) {
        $("#tblviajesconductorvinculados").DataTable().destroy();
    }

    tableViajesConductorVinculados = $("#tblviajesconductorvinculados").DataTable({
        destroy: true,
        language: Español,
        autoWidth: false,
        responsive: true,
        ajax: {
            method: "GET",
            url: baseURL + "viajes_conductor/traer_vinculados",
            data: {
                idviaje: idviaje
            },
            dataSrc: function (json) {
                if (json.error) {
                    mostrarNotificacion("error", "Error", json.error);
                    return [];
                }
                return json.data || [];
            }
        },
        columns: [
            { data: "nombre_conductor", title: "CONDUCTOR" },
            { data: "nombre_unidad", title: "UNIDAD" },
            { data: "fecha_reg", title: "FECHA REGISTRO" },
            { data: "km_inicial", title: "KM INICIAL" },
            { data: "km_final", title: "KM FINAL" },
            { data: "partida", title: "PARTIDA" },
            { data: "llegada", title: "LLEGADA" },
            { 
                data: "estado", 
                title: "ESTADO",
                className: "text-center",
                render: function (data) {
                    const estado = data ? data.trim().toUpperCase() : '';
                    let badgeClass = 'badge-secondary';
                    if (estado === 'ACTIVO') badgeClass = 'badge-success';
                    else if (estado === 'INACTIVO') badgeClass = 'badge-danger';
                    
                    return `<span class="badge ${badgeClass}">${estado}</span>`;
                }
            },
            {
                data: null,
                className: "text-center",
                orderable: false,
                searchable: false,
                width: "60px",
                render: function (data, type, row) {
                    return `<button class="btn btn-success btn-sm" title="Ver Paradas y Gastos" onclick="abrirModalParadasGastos(${row.idviajes_conductor})"><i class="fas fa-dollar-sign"></i></button>`;
                }
            }
        ],
        order: [[2, 'desc']]
    });
}

function cargarViajesConductorDisponibles(idviaje) {
    if ($.fn.DataTable.isDataTable("#tblviajesconductordisponibles")) {
        $("#tblviajesconductordisponibles").DataTable().destroy();
    }

    var fechaInicio = $("#filtrofechaini_vc").val();
    var fechaFin = $("#filtrofechafin_vc").val();

    tableViajesConductorDisponibles = $("#tblviajesconductordisponibles").DataTable({
        destroy: true,
        language: Español,
        autoWidth: false,
        responsive: true,
        ajax: {
            method: "GET",
            url: baseURL + "viajes_conductor/traer_disponibles",
            data: {
                idviaje: idviaje,
                fecha_inicio: fechaInicio,
                fecha_fin: fechaFin
            },
            dataSrc: function (json) {
                if (json.error) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: json.error
                    });
                    return [];
                }
                return json.data || [];
            }
        },
        columns: [
            {
                data: null,
                title: "",
                orderable: false,
                searchable: false,
                width: "30px",
                render: function (data, type, row) {
                    return `<input type="checkbox" class="checkDisponible" value="${row.idviajes_conductor}">`;
                }
            },
            { data: "nombre_conductor", title: "CONDUCTOR" },
            { data: "nombre_unidad", title: "UNIDAD" },
            { data: "fecha_reg", title: "FECHA REGISTRO" },
            { data: "km_inicial", title: "KM INICIAL" },
            { data: "km_final", title: "KM FINAL" },
            { data: "partida", title: "PARTIDA" },
            { data: "llegada", title: "LLEGADA" },
            { 
                data: "estado", 
                title: "ESTADO",
                render: function (data) {
                    const estado = data ? data.trim().toUpperCase() : '';
                    let badgeClass = 'badge-secondary';
                    if (estado === 'ACTIVO') badgeClass = 'badge-success';
                    else if (estado === 'INACTIVO') badgeClass = 'badge-danger';
                    
                    return `<span class="badge ${badgeClass}">${estado}</span>`;
                }
            }
        ],
        order: [[4, 'desc']]
    });
}

function seleccionarTodosDisponibles() {
    var checkAll = $("#checkAllDisponibles").is(":checked");
    $(".checkDisponible").each(function() {
        $(this).prop("checked", checkAll);
    });
}

function vincularViajesConductor() {
    var idviaje = $("#txtidviajeconductor").val();
    var idsSeleccionados = [];
    
    $(".checkDisponible:checked").each(function() {
        idsSeleccionados.push($(this).val());
    });
    
    if (idsSeleccionados.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Advertencia",
            text: "Debe seleccionar al menos un registro para vincular"
        });
        return;
    }
    
    if (!idviaje) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se encontró el ID del viaje"
        });
        return;
    }
    
    $.ajax({
        url: baseURL + "viajes_conductor/vincular",
        method: "POST",
        data: {
            ids_viajes_conductor: idsSeleccionados,
            idviaje: idviaje
        },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: "success",
                    title: "Éxito",
                    text: response.mensaje
                }).then(function() {
                    cargarViajesConductorVinculados(idviaje);
                    cargarViajesConductorDisponibles(idviaje);
                    $("#checkAllDisponibles").prop("checked", false);
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: response.mensaje
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Error al vincular los registros: " + error
            });
        }
    });
}

function abrirModalParadasGastos(idviajes_conductor) {
    var idviajeGuardado = idviajeActual || $("#txtidviajeconductor").val();
    abriendoModalParadas = true;
    
    $("#mdlviajesconductor").modal("hide");
    $("#mdlviajesconductor").one("hidden.bs.modal", function() {
        idviajeActual = idviajeGuardado;
        limpiarModalParadasGastos();
        $("#txtidviajesconductor_paradas").val(idviajes_conductor);
        $("#mdlparadasgastos").modal("show");
        cargarParadasYGastos(idviajes_conductor, function() {
            abriendoModalParadas = false;
        });
    });
}

function cargarParadasYGastos(idviajes_conductor, callback) {
    $.ajax({
        url: baseURL + "viajes_conductor/traer_paradas_gastos",
        method: "GET",
        data: {
            idviajes_conductor: idviajes_conductor
        },
        dataType: "json",
        success: function(response) {
            if (response.error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: response.error
                });
                if (callback) callback();
                return;
            }

            var paradas = response.data || [];
            var gastos = response.gastos || [];
            var htmlParadas = "";
            var htmlGastos = "";
            var totalGastos = 0;

            if (paradas.length === 0) {
                htmlParadas = `
                    <div class="text-center py-5" style="color: #6c757d;">
                        <i class="fas fa-map-marker-alt" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i>
                        <p class="mb-0" style="font-size: 14px; font-weight: 500;">No se encontraron paradas registradas</p>
                    </div>
                `;
            } else {
                paradas.forEach(function(parada, index) {
                    htmlParadas += `
                        <div class="card mb-3" style="border: 1px solid #dee2e6;">
                            <div class="card-header bg-light" style="padding: 10px 15px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0" style="font-size: 15px;">
                                        <i class="fas fa-map-marker-alt text-success"></i> Parada ${index + 1}
                                    </h6>
                                    <span class="badge badge-${parada.estado === 'COMPLETADO' ? 'success' : 'warning'}" style="font-size: 12px;">
                                        ${parada.estado || '-'}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body" style="padding: 15px;">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="text-muted small mb-1" style="font-size: 12px;">KM Inicial</label>
                                        <div class="font-weight-bold" style="font-size: 15px;">${parada.km_inicial || '-'}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-muted small mb-1" style="font-size: 12px;">KM Final</label>
                                        <div class="font-weight-bold" style="font-size: 15px;">${parada.km_final || '-'}</div>
                                    </div>
                                    ${parada.observacion ? `
                                    <div class="col-md-6">
                                        <label class="text-muted small mb-1" style="font-size: 12px;">Observación</label>
                                        <div style="font-size: 14px;">${parada.observacion}</div>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });
            }

            if (gastos.length === 0) {
                htmlGastos = `
                    <div class="text-center py-5" style="color: #6c757d;">
                        <i class="fas fa-receipt" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i>
                        <p class="mb-0" style="font-size: 14px; font-weight: 500;">No se encontraron gastos registrados</p>
                    </div>
                `;
            } else {
                gastos.forEach(function(gasto, index) {
                    var importe = parseFloat(gasto.importe) || 0;
                    totalGastos += importe;
                    var borderClass = index < gastos.length - 1 ? 'border-bottom' : '';
                    htmlGastos += `
                        <div class="card mb-2 ${borderClass}" style="border: 1px solid #dee2e6;">
                            <div class="card-body" style="padding: 12px;">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <label class="text-muted small mb-0" style="font-size: 11px;">Factura</label>
                                        <div style="font-size: 14px;">${gasto.factura || '-'}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small mb-0" style="font-size: 11px;">Fecha</label>
                                        <div style="font-size: 14px;">${gasto.fecha || '-'}</div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <label class="text-muted small mb-0" style="font-size: 11px;">Importe</label>
                                        <div class="text-success font-weight-bold" style="font-size: 16px;">S/ ${importe.toFixed(2)}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }

            $("#contenidoParadas").html(htmlParadas);
            $("#contenidoGastos").html(htmlGastos);
            $("#totalGastosGeneral").text("S/ " + totalGastos.toFixed(2));
            if (callback) callback();
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Error al cargar los datos: " + error
            });
            if (callback) callback();
        }
    });
}

function limpiarModalParadasGastos() {
    $("#txtidviajesconductor_paradas").val("");
    $("#contenidoParadas").html("");
    $("#contenidoGastos").html("");
    $("#totalGastosGeneral").text("S/ 0.00");
    $('#tab-paradas').tab('show');
}

function recargarTablasViajesConductor(idviaje) {
    if ($.fn.DataTable.isDataTable("#tblviajesconductorvinculados")) {
        tableViajesConductorVinculados.ajax.reload(function() {
            tableViajesConductorVinculados.columns.adjust().responsive.recalc();
        }, false);
    } else {
        cargarViajesConductorVinculados(idviaje);
    }
    
    if ($.fn.DataTable.isDataTable("#tblviajesconductordisponibles")) {
        tableViajesConductorDisponibles.ajax.reload(function() {
            tableViajesConductorDisponibles.columns.adjust().responsive.recalc();
        }, false);
    } else {
        cargarViajesConductorDisponibles(idviaje);
    }
}

function limpiarViajesConductor() {
    $("#txtidviajeconductor").val("");
    $("#filtrofechaini_vc").val("");
    $("#filtrofechafin_vc").val("");
    $("#checkAllDisponibles").prop("checked", false);
    
    if ($.fn.DataTable.isDataTable("#tblviajesconductorvinculados")) {
        $("#tblviajesconductorvinculados").DataTable().clear().destroy();
    }
    if ($.fn.DataTable.isDataTable("#tblviajesconductordisponibles")) {
        $("#tblviajesconductordisponibles").DataTable().clear().destroy();
    }
    
    $("#tblviajesconductorvinculados tbody").empty();
    $("#tblviajesconductordisponibles tbody").empty();
}
