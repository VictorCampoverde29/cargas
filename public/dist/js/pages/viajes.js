table = '';

$(document).ready(function () {
    cargarViajes();
});

function abrirModalViaje() {
    $('#mdlviaje').modal('show');
}

function cargarViajes() {
    const url = baseURL + "mant_viajes/datatables";
    table = $("#tblviajes").DataTable({
        destroy: true,
        language: Español,
        autoWidth: true,
        responsive: true,
        columnDefs: [{ targets: 0, visible: false }],
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
                console.log(json);
                return json;
            }
        },
        columns: [
            { data: "idviaje" },
            { data: "conductor" },
            { data: "unidad" },
            { data: "fecha_inicio" },
            { data: "fecha_fin" },
            { data: "observaciones" },
            { data: "dest_origen" },
            { data: "dest_llegada" },
            {
                data: "estado",
                width: "11%",
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
                                <option value="EN CAMINO" ${estado === 'EN CAMINO' ? 'selected' : ''}>EN CAMINO</option>
                                <option value="ENTREGADO" ${estado === 'ENTREGADO' ? 'selected' : ''}>ENTREGADO</option>
                            </select>
                        </div>
                    `;
                }
            },
            {
                data: null,
                orderable: false,
                width: "11%",
                render: function (data, type, row) {
                    return `
                        <div class="d-flex flex-row justify-content-center">
                            <button class="btn btn-2 btn-warning btn-sm btn-pill w-80" onclick="editarViaje(this, ${row.idviaje})">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-2 btn-warning btn-sm btn-pill w-80 ms-2" onclick="abrirModalServicios(${row.idviaje})">
                                <i class="fas fa-toolbox"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });
}

function registrarViaje(){
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

function editarViaje(btn, idviaje){
    var row = $(btn).closest('tr');
    var estado = row.find('select[data-field="estado"]').val()
    var parametros =
        'estado=' + estado +
        '&cod=' + idviaje;
    $.ajax({
        type: "POST",
        url: baseURL + 'mant_viajes/editar_viaje',
        data: parametros,
        success: function (response) {
            console.log(response);
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
                    var paginaActual = table.page.info().page;
                    table.ajax.reload();
                    setTimeout(function () {
                        table.page(paginaActual).draw('page');
                    }, 800);
                });
            }
        }
    });
}