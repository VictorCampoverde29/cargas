table = '';

$(document).ready(function () {
    cargarDestinos();
});

function abrirModalDestino() {
    
    $('#mdldestino').modal('show');
}

function cargarDestinos() {
    const url = baseURL + "mant_destino/datatables"; // Asegúrate que esta ruta coincide con la definida en Routes.php
    table = $("#tbldestinos").DataTable({
        "destroy": true,
        "language": Español,
        "autoWidth": true,
        "responsive": true,
        "columnDefs": [{ "targets": 0, "visible": false }],
        "createdRow": function (row, data, dataIndex) {
            setTimeout(function() {
                var estadoSelect = $(row).find('select[data-field="estado"]');
                var isInactivo = data.estado && data.estado.trim().toUpperCase() === 'INACTIVO';
                
                // Aplicar font-weight-bold al select de estado siempre
                estadoSelect.addClass('font-weight-bold');
                
                // Aplicar estilos según el estado
                if (isInactivo) {
                    estadoSelect.addClass('text-danger');
                    $(row).find('input, select').not('[data-field="estado"]').css('color', '#dc3545');
                } else {
                    estadoSelect.addClass('text-success');
                }
                
                // Listener para cambios de estado
                estadoSelect.on('change', function() {
                    var nuevoInactivo = $(this).val() === 'INACTIVO';
                    
                    if (nuevoInactivo) {
                        $(this).removeClass('text-success').addClass('text-danger');
                        $(row).find('input, select').not('[data-field="estado"]').css('color', '#dc3545');
                    } else {
                        $(this).removeClass('text-danger').addClass('text-success');
                        $(row).find('input, select').not('[data-field="estado"]').css('color', '');
                    }
                });
            }, 100);
        },
        "ajax": {
            "method": "GET",
            "url": url,
            "dataSrc": function (json) {
                console.log(json);
                return json;
            }
        },
        "columns": [
            { "data": "iddestino" },
            {
                "data": "nombre",
                "orderable": false,
                "render": function (data, type, row) {
                    if (type === 'display') {
                        return `
                            <div class="d-flex align-items-center justify-content-center">
                                <!-- Texto oculto para que DataTables lo pueda buscar -->
                                <span class="d-none">${data}</span>
                                <input type="text" class="form-control form-control-sm me-2" 
                                    style="text-transform: uppercase;" 
                                    value="${data}" 
                                    id="descripcion_${row.iddestino}" 
                                    oninput="this.value = this.value.toUpperCase();" />
                            </div>
                        `;
                    }
                    // Para otros tipos (ordenamiento, búsqueda interna), devuelve solo el dato plano
                    return data;
                }
            },
            {
                "data": "estado",
                "width": "11%",
                "render": function (data, type, row) {
                    var selected_activo = (data === 'ACTIVO') ? 'selected' : '';
                    var selected_inactivo = (data === 'INACTIVO') ? 'selected' : '';
                    return `
                        <div class="input-group input-group-sm">
                            <select class="form-control form-control-sm perfil-input" data-field="estado" data-id="${data.iddestino}">
                                <option value="ACTIVO" ${selected_activo}>ACTIVO</option>
                                <option value="INACTIVO" ${selected_inactivo}>INACTIVO</option>
                            </select>
                        </div>
                    `;
                }
            },
            {
                "data": null,
                "width": "10%",
                "orderable": false,
                "render": function (data, type, row) {
                    return `<div class="d-flex flex-row gap-1 justify-content-center">
                                <button class="btn btn-2 btn-warning btn-sm btn-pill w-80" onclick="editarDestino(this, ${data.iddestino})">
                                <i class="fas fa-check"></i>
                                </button>
                            </div>`;
                }
            }
        ],
    });
}

function agregarDestino(){
    var descripcion = $("#txtdescripciondestino").val();
    var estado = $("#cmbestadodestino").val();

    if (descripcion === "") {
        Swal.fire({
            title: "MANT. DESTINOS",
            text: "FALTA INGRESAR DESCRIPCION!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $("#txtdescripciondestino");

                // Enfocar el campo inmediatamente
                documentoField.focus();

                // Mantener el focus por más tiempo (vuelve a enfocar después de 300ms)
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    }else{
        var parametros ="descripcion=" + descripcion +
            "&estado=" + estado;
        $.ajax({
            type: "POST",
            url: baseURL + "mant_destino/agregar_destino",
            data: parametros,
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        title: "REGISTRO DESTINO",
                        text: response.error,
                        icon: "error",
                    });
                } else {
                    Swal.fire({
                        icon: "success",
                        title: "REGISTRO DESTINO",
                        text: response.message,
                    }).then(function () {
                        var paginaActual = table.page.info().page;
                        table.ajax.reload();
                        setTimeout(function () {
                            table.page(paginaActual).draw("page");
                        }, 800);
                    });
                }
                $('#mdldestino').modal('hide');
            },
        });
    }
}

function editarDestino(btn, iddestino){
    var row = $(btn).closest('tr');
    var descripcion = row.find('input[id^="descripcion_"]').val();
    var estado = row.find('select[data-field="estado"]').val();
    if (descripcion === '') {
        Swal.fire({
            title: "MANT. DESTINO",
            text: "FALTA INGRESAR DESCRIPCIÓN!",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok"
        }).then((result) => {
            if (result.isConfirmed) {
                var documentoField = $('#descripcion_' + iddestino);
                documentoField.focus();
                setTimeout(function () {
                    documentoField.focus();
                }, 300);
            }
        });
    } else {
        var parametros =
            'descripcion=' + descripcion +
            '&estado=' + estado +
            '&cod=' + iddestino;
        $.ajax({
            type: "POST",
            url: baseURL + 'mant_destino/editar_destino',
            data: parametros,
            success: function (response) {
                console.log(response);
                if (response.error) {
                    Swal.fire({
                        icon: "error",
                        title: 'EDICION DESTINO',
                        text: response.error
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'EDICION DESTINO',
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
}