var table = "";
let pagina = 1;
let terminoActual = "";
let paginaPorInput = {};

$(document).ready(function () {
    get_Gastos_Viaje();
    $(document).on("keyup", "#txtdest1, #txtdest2", function () {
        let inputId = $(this).attr("id");
        let termino = $(this).val();
        paginaPorInput[inputId] = 1;
        buscarDestinos(inputId, termino, true);
    });

    $('#cmbfiltrorigen').on('change', function () {

        const origenSeleccionado = $(this).val();

        $('#cmbfiltrodestino option').each(function () {
            $(this).prop('hidden', false);

            if ($(this).val() === origenSeleccionado) {
                $(this).prop('hidden', true);
            }
        });

        if ($('#cmbfiltrodestino').val() === origenSeleccionado) {
            $('#cmbfiltrodestino').val('');
        }
    });

    preciosCombustiblePorId();
    $('#cmbprecio').on('change', function () {
        preciosCombustiblePorId();
    });

    $('#txtgalonesref, #txtprecioref').on('input', calcularTotalViaje);
    // bloquearLetrasPorId('txtdistancia')
    // bloquearLetrasPorId('txtmonto');
    // bloquearLetrasPorId('numcantidad');
    // bloquearEspaciosPorId('txtdistancia');
    // bloquearEspaciosPorId('txtdest1');
    // bloquearEspaciosPorId('txtdest2');
    // bloquearEspaciosPorId('txtmonto');
    // bloquearEspaciosPorId('numcantidad');
    // bloquearEspaciosPorId('txttotal');
    $('#txtmonto, #numcantidad').on('input', calcularTotal);
});

function bloquearEspaciosPorId(id) {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('input', function () {
            this.value = this.value.replace(/\s+/g, '');
        });
    }
}

function bloquearLetrasPorId(id) {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener("input", function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
}

function limpiar() {
    $("#txtdescripcion").val("");
    $("#cmbunidad").prop('selectedIndex', 0);
    $("#cmbconductor").prop('selectedIndex', 0);
    $("#txtdistancia").val("");
    $("#txtdest1").val("");
    $("#txtdest2").val("");
    $("#hdnIdDest1").val("");
    $("#hdnIdDesti2").val("");
    $("#cmbestado").val("ACTIVO");
}

function limpiarmodalGastoViaje() {
    $("#cmbcategoria").prop('selectedIndex', 0);
    $("#txtglosagasto").val("");
    $("#txtmonto").val("");
    $("#numcantidad").val("");
    $("#txttotal").val("");
}

function abrir_Modal_Ruta() {
    limpiar();
    $("#mdlgastoviaje").modal("show");
}

function buscarDestinos(inputId, termino, limpiar) {
    if (termino.length >= 3) {
        $.ajax({
            url: baseURL + "gastos_viajes/buscar_destinos",
            method: "GET",
            data: { q: termino, page: paginaPorInput[inputId] },
            dataType: "json",
            success: function (data) {
                console.log(data);
                let lista;
                let idLista = "resultados-articulos-" + inputId;
                if (limpiar) {
                    $("#" + idLista).parent().remove();
                    let inputGroup = $("#" + inputId).closest('.input-group');
                    let targetElement = inputGroup.length ? inputGroup : $("#" + inputId);
                    let contenedor = $('<div class="position-relative w-100" style="z-index: 1050;"></div>');
                    lista = $('<ul id="' + idLista + '" class="list-group shadow-sm"></ul>');
                    contenedor.append(lista);
                    targetElement.after(contenedor);
                } else {
                    lista = $("#" + idLista);
                    lista.find(".btn-cargar-mas").parent().remove();
                }

                let destinosData = data.destinos || [];

                if (destinosData.length > 0) {
                    $.each(destinosData, function (index, destino) {
                        lista.append(`
                            <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center cursor-pointer" 
                                onclick="seleccionarDestino('${inputId}', '${destino.iddestino}', '${destino.nombre}')"
                                style="user-select:none;">
                                <span><strong>${destino.nombre}</strong></span>
                                <span class="text-success d-none check-icon"><i class="fas fa-check"></i></span>
                                <input type="hidden" class="destino-id" value="${destino.iddestino}">
                                <input type="hidden" class="destino-nombre" value="${destino.nombre}">
                            </li>
                        `);
                    });

                    lista.append(`
                        <li class="list-group-item list-group-item-action text-info cursor-pointer" 
                            onclick="registrarNuevoDestino('${inputId}', '${termino}')"
                            style="user-select:none;">
                            <i class="fas fa-plus-circle mr-2"></i> Registrar "${termino}" como nuevo destino
                        </li>
                    `);
                } else {
                    lista.append(`
                        <li class="list-group-item list-group-item-action text-info cursor-pointer" 
                            onclick="registrarNuevoDestino('${inputId}', '${termino}')"
                            style="user-select:none;">
                            <i class="fas fa-plus-circle mr-2"></i> Registrar "${termino}" como nuevo destino
                        </li>
                    `);
                }

                let hayMas = (data.total && data.total > (paginaPorInput[inputId] * data.limite)) ||
                    destinosData.length >= (data.limite || 10);

                if (hayMas) {
                    lista.append(`
                        <li class="list-group-item text-center p-2" style="background-color: #f8f9fa;">
                            <button class="btn btn-sm w-100 btn-secondary btn-cargar-mas" 
                                    data-input-id="${inputId}" 
                                    data-termino="${termino}"
                                    data-tipo-resultado="destino">
                                <i class="fas fa-plus"></i> Cargar más destinos
                            </button>
                        </li>
                    `);
                }

                lista.css({
                    'max-height': '300px',
                    'overflow-y': 'auto',
                    'width': '100%',
                    'position': 'absolute',
                    'top': '100%',
                    'left': '0',
                    'right': '0',
                    'z-index': '1000',
                    'margin-top': '2px'
                });
            },
            error: function (xhr, status, error) {
                $("#resultados-articulos-" + inputId).parent().remove();

                let contenedor = $('<div class="position-relative w-100"></div>');
                let lista = $('<ul id="resultados-articulos-' + inputId + '" class="list-group shadow-sm"></ul>');
                contenedor.append(lista);
                $("#" + inputId).after(contenedor);

                lista.append(`
                    <li class="list-group-item text-danger">Error al buscar destinos: ${error}</li>
                `);

                lista.css({
                    'max-height': '300px',
                    'overflow-y': 'auto',
                    'width': '100%',
                    'position': 'absolute',
                    'top': '100%',
                    'left': '0',
                    'right': '0',
                    'z-index': '1000',
                    'margin-top': '2px'
                });
            }
        });
    } else {
        $("#resultados-articulos-" + inputId).parent().remove();
        paginaPorInput[inputId] = 1;
    }
}

function seleccionarDestino(inputId, idDestino, nombreDestino) {
    $("#" + inputId).val(nombreDestino);

    if (inputId === "txtdest1") {
        $("#hdnIdDesti1").val(idDestino);
    } else if (inputId === "txtdest2") {
        $("#hdnIdDesti2").val(idDestino);
    }
    const listItem = $(`#resultados-articulos-${inputId} li`).filter(function () {
        return $(this).find('.destino-id').val() === idDestino;
    });

    listItem.find('.check-icon').removeClass('d-none').addClass('d-inline');

    setTimeout(function () {
        $("#resultados-articulos-" + inputId).parent().remove();
    }, 300);
}

function agregarDestino(descripcion, callback) {
    if (descripcion === "") {
        Swal.fire({
            title: "REGISTRO DESTINO",
            text: "La descripción del destino no puede estar vacía",
            icon: "error"
        });
        return;
    }

    var parametros = "descripcion=" + encodeURIComponent(descripcion) + "&estado=ACTIVO";
    $.ajax({
        type: "POST",
        url: baseURL + "mant_destino/agregar_destino",
        data: parametros,
        success: function (response) {
            if (response.error) {
                Swal.fire({
                    title: "REGISTRO DESTINO",
                    text: response.error,
                    icon: "error"
                });
                if (callback) callback(null);
            } else {
                if (callback) {
                    $.ajax({
                        url: baseURL + "mant_viajes/buscar_destinos",
                        method: "GET",
                        data: { q: descripcion, page: 1 },
                        dataType: "json",
                        success: function (data) {
                            let destinosData = data.destinos || [];
                            if (destinosData.length > 0) {
                                let destinoCreado = destinosData.find(d => d.nombre.toUpperCase() === descripcion.toUpperCase());
                                if (destinoCreado) {
                                    callback(destinoCreado.iddestino);
                                } else {
                                    callback(null);
                                }
                            } else {
                                callback(null);
                            }
                        },
                        error: function () {
                            callback(null);
                        }
                    });
                }
            }
        },
        error: function () {
            Swal.fire({
                title: "REGISTRO DESTINO",
                text: "Error al registrar el destino",
                icon: "error"
            });
            if (callback) callback(null);
        }
    });
}

function registrarNuevoDestino(inputId, nombreDestino) {
    agregarDestino(nombreDestino, function (idDestinoNuevo) {
        Swal.close();
        if (idDestinoNuevo) {
            $("#" + inputId).val(nombreDestino);

            if (inputId === "txtdest1") {
                $("#hdnIdDesti1").val(idDestinoNuevo);
            } else if (inputId === "txtdest2") {
                $("#hdnIdDesti2").val(idDestinoNuevo);
            }
            $("#resultados-articulos-" + inputId).parent().remove();

            Swal.fire({
                icon: "success",
                title: "DESTINO REGISTRADO",
                text: `El destino "${nombreDestino}" ha sido registrado correctamente`,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "ERROR",
                text: "No se pudo registrar el destino"
            });
        }
    });
}

function preciosCombustiblePorId() {
    var cod = $('#cmbprecio').val();
    const url = baseURL + "gastos_viajes/precio_combustible";
    $.ajax({
        type: "GET",
        url: url,
        data: { cod: cod },
        success: function (response) {
            console.log(response);
            $('#txtprecioref').val(response.data.precio_combustible);
            calcularTotalViaje();
        },
    });
}

function calcularTotalViaje(){
    var precioGalon = parseFloat($('#txtprecioref').val()) || 0;
    var galones = parseFloat($('#txtgalonesref').val()) || 0;
    var totalViaje = precioGalon * galones;
    $('#txttotalcomb').val(totalViaje.toFixed(2));

}

function registrarRuta() {
    var parametros = {
        unidad: $('#cmbunidad').val(),
        distancia: $('#txtdistancia').val(),
        origen: $('#hdnIdDesti1').val(),
        destino: $('#hdnIdDesti2').val(),
    }
    if (parametros.distancia === "" || parametros.distancia <= 0) {
        Swal.fire({ icon: 'warning', title: 'Registrar Ruta', text: 'Seleccione una distancia válida', });
        return;
    }
    if (parametros.origen === "") {
        Swal.fire({ icon: 'warning', title: 'Registrar Ruta', text: 'Seleccione un origen', });
        return;
    }
    if (parametros.destino === "") {
        Swal.fire({ icon: 'warning', title: 'Registrar Ruta', text: 'Seleccione un destino', });
        return;
    }
    $.ajax({
        type: "POST",
        url: baseURL + "gastos_viajes/registrar",
        data: parametros,
        success: function (response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Registrar Ruta',
                    text: response.message,
                }).then(() => {
                    $("#mdlgastoviaje").modal("hide");
                    get_Gastos_Viaje();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Registrar Ruta',
                    text: response.message
                });
            }
        },
    });
}

function get_Gastos_Viaje() {
    const url = baseURL + "gastos_viajes/obtener_gastos_viaje";

    table = $("#tblgastoviajes").DataTable({
        destroy: true,
        language: Español,
        lengthChange: true,
        autoWidth: false,
        responsive: true,
        ajax: {
            url: url,
            method: "GET",
            dataSrc: function (json) {
                return json.data;
            },
        },
        columns: [
            { data: "idgastos_viaje", visible: false },
            { data: "viaje" },
            { data: "unidad" },
            { data: "tramo_km", width: "15%", className: "text-center" },
            {
                data: null,
                orderable: false,
                width: "12%",
                className: "text-center",
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-info" onclick="mostrarDetalleViajeGasto('${row.idgastos_viaje}', '${row.unidad}', '${row.viaje}', '${row.tramo_km}')" title="GASTOS"><i class="fas fa-info-circle"></i> GASTOS</button>
                    `;
                },
            },
        ],
    });
}

function mostrarDetalleViajeGasto(cod, unidad, viaje, tramo_km) {
    $("#txtunidad").val(unidad);
    $("#txtidviaje").val(cod);
    $("#txttramo").val(tramo_km);
    $("#tituloDetalleGasto").text("Detalle de gastos : " + viaje);
    $("#accordion").html("");
    limpiarmodalGastoViaje();
    $("#mdldetgastviaje").modal("show");
    cargarDetalle();
}

function cargarDetalle() {
    var cod = $("#txtidviaje").val();
    const url = baseURL + "gastos_viajes/detalle_gastos_viaje";
    $.ajax({
        type: "GET",
        url: url,
        data: { cod: cod },
        success: function (response) {
            console.log(response);
            if (response.data && response.data.length > 0) {
                cargarGastosEnAcordeon(response.data);
            }
        },
    });
}

function cargarGastosEnAcordeon(gastos) {
    $("#accordion").empty();
    const gastosPorCategoria = {};
    gastos.forEach(function (gasto) {
        const catNombre = gasto.categoria;
        if (!gastosPorCategoria[catNombre]) {
            gastosPorCategoria[catNombre] = [];
        }
        gastosPorCategoria[catNombre].push(gasto);
    });
    Object.keys(gastosPorCategoria).forEach(function (catNombre) {
        const items = gastosPorCategoria[catNombre];
        const acuerdoId = "accordion-cat-" + catNombre.replace(/[^a-zA-Z0-9_-]/g, "_");
        const estaAbierto = "";
        const ariaExpanded = "false";
        // Calcular el total de la categoría
        const totalCategoria = items.reduce(function (sum, item) {
            return sum + (parseFloat(item.total) || 0);
        }, 0);
        let html = `
                <style>
                .acordeon-header-outline {
                    background: #fff !important;
                    border: 2px solid #17a2b8 !important;
                    transition: background 0.2s, color 0.2s;
                }
                .acordeon-header-outline a {
                    color: #17a2b8 !important;
                    text-decoration: none;
                    font-weight: 600;
                    transition: background 0.2s, color 0.2s;
                }
                .acordeon-header-outline:hover, .acordeon-header-outline:focus-within {
                    background: #17a2b8 !important;
                }
                .acordeon-header-outline:hover a, .acordeon-header-outline:focus-within a {
                    color: #fff !important;
                }
                .acordeon-header-outline .total-categoria {
                    float: right;
                    font-size: 1rem;
                    font-weight: 600;
                    color: #0c0c0cff !important;
                }
                </style>
                <div class="card card-info">
                    <div class="card-header acordeon-header-outline">
                        <h4 class="card-title w-100 mb-0 d-flex justify-content-between align-items-center">
                            <a class="d-block py-2 px-3 flex-grow-1" data-toggle="collapse" href="#collapse${acuerdoId}" aria-expanded="${ariaExpanded}">
                                ${catNombre}
                            </a>
                            <span class="total-categoria pr-3">Total: S/ ${totalCategoria.toFixed(2)}</span>
                        </h4>
                    </div>
                    <div id="collapse${acuerdoId}" class="collapse ${estaAbierto}" data-parent="#accordion">
                        <div class="card-body">
                            <table class="table table-sm table-bordered" id="${acuerdoId}">
                                <thead>
                                    <tr>
                                        <th>DESCRIPCIÓN</th>
                                        <th>MONTO</th>
                                        <th>CANTIDAD</th>
                                        <th>TOTAL</th>
                                        <th>ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;
        $("#accordion").append(html);
        $("#" + acuerdoId).closest('.table-responsive').remove();
        $("#" + acuerdoId).wrap('<div class="table-responsive"></div>');

        $("#" + acuerdoId).DataTable({
            data: items,
            columns: [
                { data: "descripcion" },
                { data: "monto" },
                { data: "cantidad" },
                { data: "total" },
                {
                    data: null,
                    orderable: false,
                    width: "10%",
                    className: "text-center",
                    render: function (data, type, row) {
                        return `<button class=\"btn btn-sm btn-danger\" onclick="eliminarGasto(${row.iddet_gastos_viaje})"><i class='fas fa-trash'></i></button>`;
                    }
                }
            ],
            language: Español,
            lengthChange: false,
            autoWidth: false,
            responsive: true,
            scrollX: true,
            paging: false
        });
    });
}

function eliminarGasto(idDetGasto) {
    console.log(idDetGasto);
    Swal.fire({
        title: 'Eliminar Gasto',
        text: "¿Está seguro de eliminar este gasto?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: baseURL + "gastos_viajes/eliminar_dt",
                data: { iddet_gastos_viaje: idDetGasto },
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminar Gasto',
                            text: response.message,
                        }).then(() => {
                            cargarDetalle();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Eliminar Gasto',
                            text: response.message
                        });
                    }
                },
            });
        }
    });
}

function calcularTotal() {
    var monto = parseFloat($('#txtmonto').val()) || 0;
    var cantidad = parseFloat($('#numcantidad').val()) || 0;
    var total = monto * cantidad;
    $('#txttotal').val(total.toFixed(2));
}

function agregarGasto() {
    var parametros = {
        idgastos_viaje: $('#txtidviaje').val(),
        idcategorias_viajes: $('#cmbcategoria').val(),
        descripcion: $('#txtglosagasto').val(),
        monto: $('#txtmonto').val(),
        cantidad: $('#numcantidad').val(),
        total: $('#txttotal').val()
    }
    if (parametros.descripcion === "") {
        Swal.fire({ icon: 'warning', title: 'Agregar Gasto', text: 'Ingrese una descripción para el gasto', });
        return;
    }
    if (parametros.monto === "" || parametros.monto <= 0) {
        Swal.fire({ icon: 'warning', title: 'Agregar Gasto', text: 'Ingrese un monto para el gasto', });
        return;
    }
    if (parametros.cantidad === "" || parametros.cantidad <= 0) {
        Swal.fire({ icon: 'warning', title: 'Agregar Gasto', text: 'Ingrese una cantidad para el gasto', });
        return;
    }
    console.log(parametros);
    $.ajax({
        type: "POST",
        url: baseURL + "gastos_viajes/registrar_dt",
        data: parametros,
        success: function (response) {
            console.log(response);
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Agregar Gasto',
                    text: response.message,
                }).then(() => {
                    cargarDetalle();
                    limpiarmodalGastoViaje();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Agregar Gasto',
                    text: response.message
                });
            }
        }
    });
}

function cargarGastosEnAcordeonConsulta(gastos) {
    $("#accordion").empty();
    let totalGeneral = gastos.reduce(function (sum, item) {
        return sum + (parseFloat(item.total) || 0);
    }, 0);
    $("#txttotalgasto").val(totalGeneral.toFixed(2));
    const gastosPorCategoria = {};
    gastos.forEach(function (gasto) {
        const catNombre = gasto.categoria;
        if (!gastosPorCategoria[catNombre]) {
            gastosPorCategoria[catNombre] = [];
        }
        gastosPorCategoria[catNombre].push(gasto);
    });
    Object.keys(gastosPorCategoria).forEach(function (catNombre) {
        const items = gastosPorCategoria[catNombre];
        const acuerdoId = "accordion-cat-" + catNombre.replace(/[^a-zA-Z0-9_-]/g, "_");
        const estaAbierto = "";
        const ariaExpanded = "false";
        // Calcular el total de la categoría
        const totalCategoria = items.reduce(function (sum, item) {
            return sum + (parseFloat(item.total) || 0);
        }, 0);
        let html = `
                <style>
                .acordeon-header-outline {
                    background: #fff !important;
                    border: 2px solid #17a2b8 !important;
                    transition: background 0.2s, color 0.2s;
                }
                .acordeon-header-outline a {
                    color: #17a2b8 !important;
                    text-decoration: none;
                    font-weight: 600;
                    transition: background 0.2s, color 0.2s;
                }
                .acordeon-header-outline:hover, .acordeon-header-outline:focus-within {
                    background: #17a2b8 !important;
                }
                .acordeon-header-outline:hover a, .acordeon-header-outline:focus-within a {
                    color: #fff !important;
                }
                .acordeon-header-outline .total-categoria {
                    float: right;
                    font-size: 1rem;
                    font-weight: 600;
                    color: #17a2b8 !important;
                }
                </style>
                <div class="card card-sm card-info">
                    <div class="card-header acordeon-header-outline">
                        <h4 class="card-title w-100 mb-0 d-flex justify-content-between align-items-center">
                            <a class="d-block py-2 px-3 flex-grow-1" data-toggle="collapse" href="#collapse${acuerdoId}" aria-expanded="${ariaExpanded}">
                                ${catNombre}
                            </a>
                            <span class="total-categoria pr-3">Total: S/ ${totalCategoria.toFixed(2)}</span>
                        </h4>
                    </div>
                    <div id="collapse${acuerdoId}" class="collapse ${estaAbierto}" data-parent="#accordion">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="${acuerdoId}">
                                    <thead>
                                        <tr>
                                            <th>DESCRIPCIÓN</th>
                                            <th>MONTO</th>
                                            <th>CANTIDAD</th>
                                            <th>TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        $("#accordion").append(html);
        $("#" + acuerdoId).DataTable({
            data: items,
            columns: [
                { data: "descripcion" },
                { data: "monto" },
                { data: "cantidad" },
                { data: "total" },
            ],
            language: Español,
            lengthChange: true,
            autoWidth: false,
            responsive: true
        });
    });
}

function obtenerGastosViajes() {
    var parametros = {
        orig: $('#cmbfiltrorigen').val(),
        dest: $('#cmbfiltrodestino').val(),
        uni: $('#cmbfiltrounidad').val(),
    }
    const url = baseURL + "consultar_gv";
    $.ajax({
        type: "GET",
        url: url,
        data: parametros,
        success: function (response) {
            $("#accordion").empty();
            if (!response.data || !response.data.idgastos_viaje) {
                $('#txtidviajes').val('');
                $('#txtviaje').val('');
                $('#txtuni').val('');
                Swal.fire({
                    icon: "info",
                    title: "NO EXISTE VIAJE",
                    text: "El viaje no existe en la base de datos",
                    timerProgressBar: true
                });
                $('#carddetalle').addClass('d-none');
                return;
            } else {
                $('#txtidviajes').val(response.data.idgastos_viaje);
                $('#txtviaje').val(response.data.viaje);
                $('#txtuni').val(response.data.unidad);
                if (!response.data || !response.data.idgastos_viaje) {
                    Swal.fire({
                        icon: "error",
                        title: "GASTOS NO EXISTEN",
                        text: "No se encontraron gastos para el viaje",
                        timerProgressBar: true
                    });
                    return;
                } else {
                    var cod = $("#txtidviajes").val();
                    const url = baseURL + "det_gasto_consul";
                    $.ajax({
                        type: "GET",
                        url: url,
                        data: { cod: cod },
                        success: function (response) {
                            if (response.data) {
                                cargarGastosEnAcordeonConsulta(response.data);
                                $('#carddetalle').removeClass('d-none');
                            }
                        },
                    });
                }
            }

        },
    });


}