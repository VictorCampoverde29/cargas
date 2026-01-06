var table = "";
let pagina = 1;
let terminoActual = "";
let paginaPorInput = {};

$(document).ready(function () {
    get_Gastos_Viaje();
    $(document).on("keyup", "#txtdest1, #txtdest2", function () {
        let inputId = $(this).attr("id");
        let termino = $(this).val();
        paginaPorInput[inputId] = 1; // Reiniciar página para este input específico
        buscarDestinos(inputId, termino, true);
    });
    bloquearLetrasPorId('txtdistancia')
    bloquearLetrasPorId('txtmonto');
    bloquearLetrasPorId('numcantidad');
    bloquearEspaciosPorId('txtdistancia');
    bloquearEspaciosPorId('txtdest1');
    bloquearEspaciosPorId('txtdest2');
    bloquearEspaciosPorId('txtglosagasto');
    bloquearEspaciosPorId('txtmonto');
    bloquearEspaciosPorId('numcantidad');
    bloquearEspaciosPorId('txttotal');
});

function bloquearEspaciosPorId(id) {
    document.getElementById(id).addEventListener('input', function () {
        this.value = this.value.replace(/\s+/g, '');
    });
}

function bloquearLetrasPorId(id) {
    document.getElementById(id).addEventListener("input", function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
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
                //console.log(data);
                let lista;
                let idLista = "resultados-articulos-" + inputId;
                if (limpiar) {
                    // Eliminar cualquier lista de resultados previa
                    $("#" + idLista).parent().remove();
                    // Encontrar el input-group que contiene el campo de texto
                    let inputGroup = $("#" + inputId).closest('.input-group');
                    let targetElement = inputGroup.length ? inputGroup : $("#" + inputId);
                    // Crear un contenedor para la lista con posición relativa
                    let contenedor = $('<div class="position-relative w-100" style="z-index: 1050;"></div>');
                    // Crear la lista como posición absoluta dentro del contenedor
                    lista = $('<ul id="' + idLista + '" class="list-group shadow-sm"></ul>');
                    // Añadir la lista al contenedor y el contenedor después del elemento objetivo
                    contenedor.append(lista);
                    targetElement.after(contenedor);
                } else {
                    lista = $("#" + idLista);
                    // Remover el botón "Cargar más" anterior si existe
                    lista.find(".btn-cargar-mas").parent().remove();
                }
                
                // Adaptación para manejar el nuevo formato de datos de destinos
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
                    
                    // Añadir opción para registrar nuevo destino si es necesario
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
                
                // Agregar botón "Cargar más" si hay más resultados disponibles
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

                // Agregar estilos para asegurar que la lista aparezca correctamente
                lista.css({
                    'max-height': '300px',
                    'overflow-y': 'auto',
                    'width': '100%',
                    'position': 'absolute',
                    'top': '100%', // Posiciona justo debajo del input
                    'left': '0',
                    'right': '0',
                    'z-index': '1000',
                    'margin-top': '2px' // Pequeño espacio entre el input y la lista
                });
            },
            error: function (xhr, status, error) {
                $("#resultados-articulos-" + inputId).parent().remove();

                // Crear contenedor y lista para el mensaje de error
                let contenedor = $('<div class="position-relative w-100"></div>');
                let lista = $('<ul id="resultados-articulos-' + inputId + '" class="list-group shadow-sm"></ul>');
                contenedor.append(lista);
                $("#" + inputId).after(contenedor);

                lista.append(`
                    <li class="list-group-item text-danger">Error al buscar destinos: ${error}</li>
                `);

                // Aplicar estilos
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
        // Eliminar tanto la lista como su contenedor
        $("#resultados-articulos-" + inputId).parent().remove();
        // Limpiar la página para este input cuando se borra el término
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
    
    // Mostrar check verde al seleccionar
    const listItem = $(`#resultados-articulos-${inputId} li`).filter(function() {
        return $(this).find('.destino-id').val() === idDestino;
    });
    
    listItem.find('.check-icon').removeClass('d-none').addClass('d-inline');
    
    // Cerrar la lista después de un breve delay para que se vea la selección
    setTimeout(function() {
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
                // Si el destino se registró correctamente, devolver el ID
                if (callback) {
                    // Hacer una búsqueda para obtener el ID del destino recién creado
                    $.ajax({
                        url: baseURL + "mant_viajes/buscar_destinos",
                        method: "GET",
                        data: { q: descripcion, page: 1 },
                        dataType: "json",
                        success: function(data) {
                            let destinosData = data.destinos || [];
                            if (destinosData.length > 0) {
                                // Encontrar el destino recién creado
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
                        error: function() {
                            callback(null);
                        }
                    });
                }
            }
        },
        error: function() {
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
    agregarDestino(nombreDestino, function(idDestinoNuevo) {
        Swal.close();
        
        if (idDestinoNuevo) {
            // Actualizar el input con el nombre y el campo oculto con el ID
            $("#" + inputId).val(nombreDestino);
            
            if (inputId === "txtdest1") {
                $("#hdnIdDesti1").val(idDestinoNuevo);
            } else if (inputId === "txtdest2") {
                $("#hdnIdDesti2").val(idDestinoNuevo);
            }
            
            // Cerrar la lista de resultados
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

function reg_Ruta_Viajes(){
    var dest1 = $("#hdnIdDesti1").val();
    var dest2 = $("#hdnIdDesti2").val();
    var unidad = $("#cmbunidad").val();
    var conductor = $("#cmbconductor").val();
    var distancia = $("#txtdistancia").val();

    if (dest1 === "") {
        Swal.fire({
            title: "MANT. GASTOS VIAJE",
            text: "FALTA INGRESAR DIRECCION DE ORIGEN!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#txtdest1");
                documentoField.focus();
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    } else if (dest2 === "") {
        Swal.fire({
            title: "MANT. GASTOS VIAJE",
            text: "FALTA INGRESAR DIRECCION DE DESTINO!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#txtdest2");
                documentoField.focus();
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    } else if (distancia === "" || distancia <= 0) {
        Swal.fire({
            title: "MANT. GASTOS VIAJE",
            text: "FALTA INGRESAR DISTANCIA!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#txtdistancia");
                documentoField.focus();
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    }
    
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
            { data: "conductor" },
            { data: "unidad" },
            { data: "tramo_km" },
            {
                data: null,
                orderable: false,
                width: "12%",
                className: "text-center",
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-warning" onclick="mostrarDetalleViajeGasto('${row.idgastos_viaje}', '${row.unidad}', '${row.viaje}', '${row.tramo_km}')" title="GASTOS"><i class="fas fa-pencil-alt"></i> GASTOS</button>
                    `;
                },
            },
        ],
    });
}

function mostrarDetalleViajeGasto(cod, unidad, viaje, tramo_km) {
    var parametros = "cod=" + cod;
    $("#txtunidad").val(unidad);
    $("#txtidviaje").val(cod);
    $("#txttramo").val(tramo_km);
    $("#tituloDetalleGasto").text("DETALLE GASTO VIAJE - " + viaje);
    $("#accordion").html("");
    const url = baseURL + "gastos_viajes/detalle_gastos_viaje";
    $.ajax({
        type: "GET",
        url: url,
        data: parametros,
        success: function (response) {
            //console.log(response);
            if (response.data && response.data.length > 0) {
                cargarGastosEnAcordeon(response.data);
            }
            limpiarmodalGastoViaje();
            $("#mdldetgastviaje").modal("show");
        },
    });
}

function cargarGastosEnAcordeon(gastos) {
    const gastosPorCategoria = {};
    gastos.forEach(function (gasto) {
        const catId = gasto.idcategoria_viajes;
        const catNombre = gasto.categoria;

        if (!gastosPorCategoria[catId]) {
            gastosPorCategoria[catId] = {
                categoria: catNombre,
                items: [],
            };
        }
        gastosPorCategoria[catId].items.push(gasto);
    });
    let primerAcordeon = true;
    for (const catId in gastosPorCategoria) {
        const categoria = gastosPorCategoria[catId];
        const acuerdoId = "accordion-cat-" + catId;
        const estaAbierto = primerAcordeon ? "show" : "";

        let html = `
            <div class="card card-info">
                <div class="card-header">
                    <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapse${catId}" aria-expanded="${primerAcordeon}">
                            ${categoria.categoria}
                        </a>
                    </h4>
                </div>
                <div id="collapse${catId}" class="collapse ${estaAbierto}" data-parent="#accordion">
                    <div class="card-body">
                        <table class="table table-sm table-bordered" id="${acuerdoId}">
                            <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th>Monto</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
        `;
        categoria.items.forEach(function (item) {
            html += `
                <tr>
                    <td>${item.descripcion}</td>
                    <td>${item.monto}</td>
                    <td>${item.cantidad}</td>
                    <td>${item.total}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;

        $("#accordion").append(html);
        primerAcordeon = false;
    }
}

function AgregarCatGasto() {
    const categoria = $("#cmbcategoria").val();
    const categoriaNombre = $("#cmbcategoria option:selected").text();
    const descripcion = $("#txtglosagasto").val();
    const monto = $("#txmonto").val();
    const cantidad = $("#numcantidad").val();
    const total = $("#txttotal").val();

    if (descripcion === "") {
        Swal.fire({
            title: "MANT. GASTOS VIAJE",
            text: "FALTA INGRESAR DESCRIPCION!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#txtglosagasto");
                documentoField.focus();
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    } else if (monto === "" || monto <= 0) {
        Swal.fire({
            title: "MANT. GASTOS VIAJE",
            text: "FALTA INGRESAR MONTO!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#txmonto");
                documentoField.focus();
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    } else if (cantidad === "" || cantidad <= 0) {
        Swal.fire({
            title: "MANT. GASTOS VIAJE",
            text: "FALTA INGRESAR CANTIDAD!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#numcantidad");
                documentoField.focus();
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    } else {
        const acuerdoId = "accordion-cat-" + categoria;
        const collapseId = "collapse" + categoria;
        
        // Buscar si ya existe un acordeón para esta categoría
        let tablaExistente = $("#" + acuerdoId);
        let acordeonExistente = $(`#${collapseId}`).closest(".card");

        if (tablaExistente.length === 0 || acordeonExistente.length === 0) {
            // Crear nuevo acordeón si no existe
            const nuevoAcordeon = `
            <div class="card card-info">
                <div class="card-header">
                    <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#${collapseId}" aria-expanded="false">
                            ${categoriaNombre}
                        </a>
                    </h4>
                </div>
                <div id="${collapseId}" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        <table class="table table-sm table-bordered" id="${acuerdoId}">
                            <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th>Monto</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            `;
            $("#accordion").append(nuevoAcordeon);
        }

        // Obtener la tabla nuevamente después de asegurar que existe
        const tablaGastos = $("#" + acuerdoId);
        
        const fila = `
            <tr>
                <td>${descripcion}</td>
                <td>${monto}</td>
                <td>${cantidad}</td>
                <td>${total}</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        tablaGastos.find("tbody").append(fila);

        // Limpiar formulario
        $("#txtglosagasto").val("");
        $("#txmonto").val("");
        $("#numcantidad").val("");
        $("#txttotal").val("");
        $("#cmbcategoria").val($("#cmbcategoria option:first").val());
    }
}
