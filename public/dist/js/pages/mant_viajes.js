let pagina = 1;
let terminoActual = "";
let paginaPorInput = {}; // Para manejar páginas independientes por input
var table = "";

$(document).ready(function () {
    cargarViajes();
    
    // Búsqueda directa en los inputs de origen y destino
    $(document).on("keyup", "#txtorigen, #txtdestino", function () {
        let inputId = $(this).attr("id");
        let termino = $(this).val();
        paginaPorInput[inputId] = 1; // Reiniciar página para este input específico
        buscarDestinos(inputId, termino, true);
    });
});

function abrirModalViaje() {
    limpiar();
    // Asegurarse de que los campos ocultos para los destinos existan
    if ($('#hdnIdOrigen').length === 0) {
        $('#txtorigen').after('<input type="hidden" id="hdnIdOrigen" name="hdnIdOrigen">');
    }
    if ($('#hdnIdDestino').length === 0) {
        $('#txtdestino').after('<input type="hidden" id="hdnIdDestino" name="hdnIdDestino">');
    }
    $('#mdlviaje').modal('show');
}

function cargarViajes() {
    const url = baseURL + "mant_viajes/datatables";
    table = $("#tblviajes").DataTable({
        destroy: true,
        language: Español,
        autoWidth: false,
        responsive: true,
        createdRow: function (row, data, dataIndex) {
            setTimeout(function () {
                const estado = data.estado ? data.estado.trim().toUpperCase() : '';
                const selectEstado = $(row).find('select[data-field="estado"]');

                // Limpiar estilos previos
                selectEstado.removeClass('text-warning text-success text-danger');
                $(row).find('td').not(':last').css('color', '').removeClass('font-weight-bold');

                // Determinar color según estado
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

                // Listener para cambio de estado
                selectEstado.off('change').on('change', function () {
                    const nuevoEstado = $(this).val().trim().toUpperCase();

                    // Limpiar clases anteriores
                    $(this).removeClass('text-warning text-success text-danger');
                    $(row).find('td').not(':last').css('color', '').removeClass('font-weight-bold');

                    // Aplicar nuevo estilo
                    switch (nuevoEstado) {
                        case 'EN CAMINO':
                            $(this).addClass('text-warning');
                            $(row).find('td').not(':last').css('color', '#fd7e14').addClass('font-weight-bold');
                            break;
                        case 'ENTREGADO':
                            $(this).addClass('text-success');
                            $(row).find('td').not(':last').css('color', '#28a745').addClass('font-weight-bold');
                            break;
                    }
                });
            }, 50);
        },
        ajax: {
            method: "GET",
            url: url,
            dataSrc: function (json) {
                //console.log(json);
                return json;
            }
        },
        columns: [
            { data: "conductor" },
            { data: "unidad" },
            { data: "fecha_inicio" },
            { data: "fecha_fin" },
            { data: "observaciones" },
            { data: "dest_origen" },
            { data: "dest_llegada" },
            {
                data: "estado",
                width: "12%",
                render: function (data, type, row) {
                    const estado = data ? data.trim().toUpperCase() : '';
                    let claseColor = '';
                    if (estado === 'EN CAMINO') claseColor = 'text-warning';
                    else if (estado === 'ENTREGADO') claseColor = 'text-success';
                    else if (estado === 'INACTIVO') claseColor = 'text-danger';

                    return `
                        <div class="input-group input-group-sm">
                            <select class="form-control form-control-sm perfil-input font-weight-bold ${claseColor}" 
                                    data-field="estado" 
                                    data-id="${row.idviaje}">
                                <option value="EN CAMINO" ${estado === 'EN CAMINO' ? 'selected' : ''} class="text-warning">EN CAMINO</option>
                                <option value="ENTREGADO" ${estado === 'ENTREGADO' ? 'selected' : ''} class="text-success">ENTREGADO</option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-warning btn-sm" onclick="editarViaje(this, ${row.idviaje})">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </div>
                    `;
                }
            },
            {
                data: null,
                orderable: false,
                width: "5%",
                render: function (data, type, row) {
                    return `
                        <div class="d-flex flex-row justify-content-center gap-2">
                            <button class="btn btn-info btn-gestionar-servicios" title="Gestionar Servicios" onclick="abrirModalServicios(${row.idviaje}, '${row.estado}', '${row.fecha_inicio}', '${row.fecha_fin}')">
                                <i class="fa fa-file-alt"></i>
                            </button>&nbsp;
                            <button class="btn btn-danger" onclick="eliminarViaje(${row.idviaje}, '${row.estado}')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });
}

// Evento para el botón "Cargar más"
$(document).on("click", ".btn-gestionar-servicios", function (e) {
    $("#cmbsucursalguia").prop('selectedIndex', 0);
});

function eliminarViaje(idviaje) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "Esta acción eliminará el viaje y sus servicios relacionados. ¡No podrá revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#3085d6',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Eliminando...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            // Enviar petición AJAX
            $.ajax({
                url: baseURL + 'mant_viajes/eliminar_viaje',
                type: 'POST',
                data: {
                    idviaje: idviaje
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.error) {
                        // Mostrar error
                        Swal.fire({
                            title: 'Error',
                            text: response.error,
                            icon: 'error',
                            confirmButtonColor: '#3085d6'
                        });
                    } else {
                        // Mostrar éxito
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                cargarViajes();
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
        }
    });
}

function limpiar() {
    // Limpiar descripción y inputs de origen/destino
    $("#txtdescripcion").val("");
    $("#txtorigen").val("");
    $("#txtdestino").val("");
    
    // Limpiar campos ocultos de origen/destino
    $("#hdnIdOrigen").val("");
    $("#hdnIdDestino").val("");

    // Restablecer selects a su valor inicial (primera opción)
    $("#cmbconductor").prop('selectedIndex', 0);
    $("#cmbvehiculo").prop('selectedIndex', 0);

    // Establecer fecha actual
    var fechaHoy = new Date().toISOString().split('T')[0];
    $("#dtfinicio").val(fechaHoy);
    $("#dtffin").val(fechaHoy);
}

// Función para agregar un nuevo destino
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

function registrarViaje() {
    var idconductor = $("#cmbconductor").val();
    var idunidad = $("#cmbvehiculo").val();
    var fecha_inicio = $("#dtfinicio").val();
    var fecha_fin = $("#dtffin").val();
    var descripcion = $("#txtdescripcion").val();
    var desti_origen = $("#hdnIdOrigen").val();
    var desti_llegada = $("#hdnIdDestino").val();
    var nombreOrigen = $("#txtorigen").val().trim();
    var nombreDestino = $("#txtdestino").val().trim();
    
    // Validaciones
    if (descripcion === '') {
        Swal.fire('REGISTRO DE VIAJE', 'La descripción es obligatoria', 'error');
        $('#txtdescripcion').focus();
        return;
    }
    
    if (nombreOrigen === '') {
        Swal.fire('REGISTRO DE VIAJE', 'Debe ingresar un origen', 'error');
        $('#txtorigen').focus();
        return;
    }
    
    if (nombreDestino === '') {
        Swal.fire('REGISTRO DE VIAJE', 'Debe ingresar un destino', 'error');
        $('#txtdestino').focus();
        return;
    }
    
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Procesando...',
        text: 'Verificando y registrando destinos',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Función para continuar con el registro del viaje
    const continuarRegistroViaje = function(idOrigenFinal, idDestinoFinal) {
        var parametros = "idconductor=" + idconductor +
            "&idunidad=" + idunidad +
            "&f_inicio=" + fecha_inicio +
            "&f_fin=" + fecha_fin +
            "&observaciones=" + descripcion +
            "&destorigen=" + idOrigenFinal +
            "&destllegada=" + idDestinoFinal;
        
        $.ajax({
            type: "POST",
            url: baseURL + "mant_viajes/registrar_viaje",
            data: parametros,
            success: function (response) {
                Swal.close(); // Cerrar el indicador de carga
                
                if (response.error) {
                    Swal.fire({
                        title: "REGISTRO DE VIAJE",
                        text: response.error,
                        icon: "error",
                    });
                } else {
                    Swal.fire({
                        icon: "success",
                        title: "REGISTRO DE VIAJE",
                        text: response.message,
                    }).then(function () {
                        $('#tblviajes').DataTable().ajax.reload(null, false);
                        $('#mdlviaje').modal('hide');
                        
                        // Esperar a que el modal se cierre completamente antes de abrir el de servicios
                        if (response.idviaje) {
                            // Guardar las fechas antes de limpiar el formulario
                            const fechaInicio = fecha_inicio;
                            const fechaFin = fecha_fin;
                            
                            limpiar();
                            
                            $('#mdlviaje').on('hidden.bs.modal', function() {
                                $(this).off('hidden.bs.modal');
                                // Pasar también las fechas al abrir el modal de servicios
                                abrirModalServicios(response.idviaje, 'EN CAMINO', fechaInicio, fechaFin);
                            });
                        } else {
                            limpiar();
                        }
                    });
                }
            },
            error: function() {
                Swal.close(); // Cerrar el indicador de carga
                Swal.fire({
                    title: "ERROR",
                    text: "Error al registrar el viaje",
                    icon: "error",
                });
            }
        });
    };
    
    // Verificar y registrar origen si es necesario
    if (!desti_origen) {
        agregarDestino(nombreOrigen, function(idOrigenNuevo) {
            if (idOrigenNuevo) {
                $("#hdnIdOrigen").val(idOrigenNuevo);
                
                // Verificar y registrar destino si es necesario
                if (!desti_llegada) {
                    agregarDestino(nombreDestino, function(idDestinoNuevo) {
                        if (idDestinoNuevo) {
                            $("#hdnIdDestino").val(idDestinoNuevo);
                            continuarRegistroViaje(idOrigenNuevo, idDestinoNuevo);
                        } else {
                            Swal.close();
                            Swal.fire('ERROR', 'No se pudo registrar el destino', 'error');
                        }
                    });
                } else {
                    continuarRegistroViaje(idOrigenNuevo, desti_llegada);
                }
            } else {
                Swal.close();
                Swal.fire('ERROR', 'No se pudo registrar el origen', 'error');
            }
        });
    } 
    // Si ya existe origen pero no destino
    else if (!desti_llegada) {
        agregarDestino(nombreDestino, function(idDestinoNuevo) {
            if (idDestinoNuevo) {
                $("#hdnIdDestino").val(idDestinoNuevo);
                continuarRegistroViaje(desti_origen, idDestinoNuevo);
            } else {
                Swal.close();
                Swal.fire('ERROR', 'No se pudo registrar el destino', 'error');
            }
        });
    } 
    // Si ya existen tanto origen como destino
    else {
        continuarRegistroViaje(desti_origen, desti_llegada);
    }
}

function buscarDestinos(inputId, termino, limpiar) {
    if (termino.length >= 3) {
        $.ajax({
            url: baseURL + "mant_viajes/buscar_destinos", // Endpoint para buscar destinos
            method: "GET",
            data: { q: termino, page: paginaPorInput[inputId] },
            dataType: "json",
            success: function (data) {
                console.log(data);
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

// Función para seleccionar un destino y actualizar el input
function seleccionarDestino(inputId, idDestino, nombreDestino) {
    // Guardar el ID en el campo oculto correspondiente
    $("#" + inputId).val(nombreDestino);
    
    // Actualizar los campos ocultos según el input
    if (inputId === "txtorigen") {
        $("#hdnIdOrigen").val(idDestino);
    } else if (inputId === "txtdestino") {
        $("#hdnIdDestino").val(idDestino);
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

// Función para registrar un nuevo destino desde la lista de resultados
function registrarNuevoDestino(inputId, nombreDestino) {
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Registrando destino...',
        text: `Registrando "${nombreDestino}" como nuevo destino`,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    agregarDestino(nombreDestino, function(idDestinoNuevo) {
        Swal.close();
        
        if (idDestinoNuevo) {
            // Actualizar el input con el nombre y el campo oculto con el ID
            $("#" + inputId).val(nombreDestino);
            
            if (inputId === "txtorigen") {
                $("#hdnIdOrigen").val(idDestinoNuevo);
            } else if (inputId === "txtdestino") {
                $("#hdnIdDestino").val(idDestinoNuevo);
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

// Evento para el botón "Cargar más"
$(document).on("click", ".btn-cargar-mas", function (e) {
    e.preventDefault();
    e.stopPropagation();

    let inputId = $(this).data("input-id");
    let termino = $(this).data("termino");
    let tipoResultado = $(this).data("tipo-resultado");

    // Incrementar la página para este input específico
    paginaPorInput[inputId]++;

    // Llamar a la función adecuada según el tipo de resultado
    buscarDestinos(inputId, termino, false);
});

function editarViaje(btn, idviaje) {
    var row = $(btn).closest('tr');
    var estado = row.find('select[data-field="estado"]').val()

    // Si el estado es ENTREGADO, validar servicios primero
    if (estado.toUpperCase() === 'ENTREGADO') {
        $.ajax({
            type: "POST",
            url: baseURL + 'mant_viajes/validar_estado_servicios',
            data: 'idviaje=' + idviaje,
            success: function (validationResponse) {
                if (validationResponse.error) {
                    Swal.fire({
                        icon: "error",
                        title: 'VALIDACIÓN SERVICIOS',
                        text: validationResponse.error
                    });
                } else if (!validationResponse.puede_entregar) {
                    Swal.fire({
                        icon: "warning",
                        title: 'SERVICIOS PENDIENTES',
                        text: 'No se puede marcar el viaje como ENTREGADO. El viaje tiene servicios pendientes.',
                        confirmButtonText: 'Entendido'
                    }).then(function () {
                        $('#tblviajes').DataTable().ajax.reload(null, false);
                    });
                } else {
                    // Todos los servicios están entregados, proceder con el cambio
                    ejecutarCambioEstado(estado, idviaje);
                }
            }
        });
    } else {
        // Si no es ENTREGADO, proceder normalmente
        ejecutarCambioEstado(estado, idviaje);
    }
}

function ejecutarCambioEstado(estado, idviaje) {
    var parametros = 'estado=' + estado + '&cod=' + idviaje;
    $.ajax({
        type: "POST",
        url: baseURL + 'mant_viajes/editar_viaje',
        data: parametros,
        success: function (response) {
            //console.log(response);
            if (response.error) {
                Swal.fire({
                    icon: "error",
                    title: 'EDICION VIAJE',
                    text: response.error
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'EDICION VIAJE',
                    text: response.message,
                }).then(function () {
                    $('#tblviajes').DataTable().ajax.reload(null, false);
                });
            }
        }
    });
}