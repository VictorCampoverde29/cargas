$(document).ready(function () {
    $('#cmbconductor').on('change', function () {
        var cod = $(this).val();
        mostrarDatosConduc(cod);
    });
    var codInicial = $('#cmbconductor').val();
    if (codInicial) {
        mostrarDatosConduc(codInicial);
    }
    $('#cmbvehiculo').on('change', function () {
        var cod = $(this).val();
        mostrarDatosVehi(cod);
    });
    var codInicial = $('#cmbvehiculo').val();
    if (codInicial) {
        mostrarDatosVehi(codInicial);
    }
    $('#cmbdeporigen').change(function (e) {
        traerProvinciaOrigen();
    });

    $('#cmbprovorigen').change(function (e) {
        traerDistritosOrigen();
    });
    traerProvinciaOrigen();
    $('#cmbdepllegada').change(function (e) {
        traerProvinciaLlegada();
    });

    $('#cmbprovllegada').change(function (e) {
        traerDistritosLlegada();
    });
    traerProvinciaLlegada();
});


function traerProvinciaOrigen() {
    var parametros = 'dep=' + $('#cmbdeporigen').val();
    const url = baseURL + 'mant_viajes/cmbprovincia';
    $.ajax({
        type: "GET",
        url: url,
        data: parametros,
        success: function (response) {
            var provincia = response[0];
            $('#cmbprovorigen').empty();
            if (Array.isArray(provincia)) {
                provincia.forEach(function (provincia) {
                    $('#cmbprovorigen').append('<option value="' + provincia.provincia + '">' + provincia.provincia + '</option>');
                });
                if (provincia.length > 0) {
                    traerDistritosOrigen();
                }
            }
        }
    });

}

function traerDistritosOrigen() {
    var parametros = 'prov=' + $('#cmbprovorigen').val();
    const url = baseURL + 'mant_viajes/cmbdistrito';
    $.ajax({
        type: "GET",
        url: url,
        data: parametros,
        success: function (response) {
            var distritos = response[0];
            $('#cmbdistorigen').empty();
            if (Array.isArray(distritos)) {
                distritos.forEach(function (distritos) {
                    $('#cmbdistorigen').append('<option value="' + distritos.iddestino + '">' + distritos.distrito + '</option>');
                });
            }

        }
    });
}

function traerProvinciaLlegada() {
    var parametros = 'dep=' + $('#cmbdepllegada').val();
    const url = baseURL + 'mant_viajes/cmbprovincia2';
    $.ajax({
        type: "GET",
        url: url,
        data: parametros,
        success: function (response) {
            var provincia = response[0];
            $('#cmbprovllegada').empty();
            if (Array.isArray(provincia)) {
                provincia.forEach(function (provincia) {
                    $('#cmbprovllegada').append('<option value="' + provincia.provincia + '">' + provincia.provincia + '</option>');
                });
                if (provincia.length > 0) {
                    traerDistritosLlegada();
                }
            }
        }
    });

}

function traerDistritosLlegada() {
    var parametros = 'prov=' + $('#cmbprovllegada').val();
    const url = baseURL + 'mant_viajes/cmbdistrito2';
    $.ajax({
        type: "GET",
        url: url,
        data: parametros,
        success: function (response) {
            var distritos = response[0];
            $('#cmbdistllegada').empty();
            if (Array.isArray(distritos)) {
                distritos.forEach(function (distritos) {
                    $('#cmbdistllegada').append('<option value="' + distritos.iddestino + '">' + distritos.distrito + '</option>');
                });
            }

        }
    });
}

function mostrarDatosConduc(cod) {
    var parametros = 'cod=' + cod;
    const url = baseURL + 'mant_viajes/datos_conductores';
    $.ajax({
        type: "GET",
        url: url,
        data: parametros,
        success: function (response) {
            $('#cmbconductor').val(cod);
            $('#txtdocuconduc').val(response.nro_doc);
            $('#txtlicenconduc').val(response.nro_licencia);
        }
    });
}

function mostrarDatosVehi(cod) {
    var parametros = 'cod=' + cod;
    const url = baseURL + 'mant_viajes/datos_vehiculos';
    $.ajax({
        type: "GET",
        url: url,
        data: parametros,
        success: function (response) {
            $('#cmbvehiculoter').val(cod);
            $('#txtnombrevehi').val(response.descripcion);
            $('#txtplaca').val(response.placa);
            $('#txtcerthabi').val(response.cert_inscrip);
        }
    });
}

function registrarViaje(){
    var idconductor = $("#cmbconductor").val();
    var idunidad = $("#cmbvehiculo").val();
    var fecha_inicio = $("#dtfinicio").val();
    var fecha_fin = $("#dtffin").val();
    var descripcion = $("#txtdescripcion").val();
    var desti_origen = $("#cmbdistorigen").val();
    var desti_llegada = $("#cmbdistllegada").val();

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
            },
        });
    }
}