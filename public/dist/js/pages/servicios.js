table = '';

$(document).ready(function () {
    cargarViajes();
});

function abrirModalServicios() {
    $('#mdlservicios').modal('show');
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
                                <option value="PENDIENTE" ${estado === 'PENDIENTE' ? 'selected' : ''}>PENDIENTE</option>
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
                render: function (data, type, row) {
                    return `
                        <div class="d-flex flex-row gap-1 justify-content-center">
                            <button class="btn btn-2 btn-warning btn-sm btn-pill w-80" onclick="editarViaje(this, ${row.idviaje})">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-2 btn-warning btn-sm btn-pill w-80" onclick="abrirModalServicios()">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                        `;
                }
            }
        ]
    });
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
