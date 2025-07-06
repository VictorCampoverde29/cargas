$(document).ready(function () {
    // Recuperar usuario y contraseña de cookies solo si el usuario existe
    var usuarioGuardado = obtenerCookie('usuarioRecordado');
    var passGuardado = obtenerCookie('passRecordado');
    var recordarMarcado = obtenerCookie('recordarMarcado') === 'true';

    if (recordarMarcado && usuarioGuardado) {
        // Verifica si el usuario existe en el input
        $('#cmbusuario').val(usuarioGuardado);
        $('#recordarPass').prop('checked', true);
        if (passGuardado) $('#txtpassword').val(passGuardado);
    } else {
        borrarCookie('usuarioRecordado');
        borrarCookie('passRecordado');
        borrarCookie('recordarMarcado');
        $('#recordarPass').prop('checked', false);
    }
    // Limpia la contraseña si el usuario cambia
    $('#cmbusuario').on('input', function () {
        $('#txtpassword').val('');
    });
});

$('#txtpassword').keyup(function(e){
  if(e.keyCode == 13)
  {
    loguear();
  }
});

function loguear() {
    var clave = $('#txtpassword').val().trim();
    var usuario = $('#txtusuario').val();
    var recordar = $('#recordarPass').is(':checked');

    // Validaciones antes de enviar la petición
    if (usuario === '' || usuario === null) {
        Swal.fire({
            icon: "error",
            title: "INICIO DE SESIÓN",
            text: "SELECCIONE UN USUARIO"
        });
        return;
    }

    if (clave === '') {
        Swal.fire({
            icon: "error",
            title: "INICIO DE SESIÓN",
            text: "INGRESE SU CONTRASEÑA"
        });
        return;
    }

    // Guardar o borrar cookies según el checkbox
    if (recordar) {
        setCookie('usuarioRecordado', usuario, 30);
        setCookie('passRecordado', clave, 30);
        setCookie('recordarMarcado', 'true', 30);
    } else {
        borrarCookie('usuarioRecordado');
        borrarCookie('passRecordado');
        borrarCookie('recordarMarcado');
    }

    var parametros = $.param({ password: clave, usuario: usuario });
    const url = URLPY + 'login/login';

    $.ajax({
        type: "POST",
        url: url,
        data: parametros,
        dataType: "json",
        success: function(response) {
           
            if (response.mensaje) {
                Swal.fire({
                    icon: "error",
                    title: "INICIO DE SESIÓN",
                    text: response.mensaje
                });
            } else {
                window.location.href = URLPY + 'dashboard';  // Redirige correctamente
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                icon: "error",
                title: "ERROR EN LA PETICIÓN",
                text: "Ocurrió un problema al intentar iniciar sesión. Inténtelo de nuevo.",
                footer: "Detalles: " + textStatus + " - " + errorThrown
            });
        }
    });
}

// Funciones de cookies en español
function setCookie(nombre, valor, dias) {
    var expires = "";
    if (dias) {
        var date = new Date();
        date.setTime(date.getTime() + (dias*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = nombre + "=" + encodeURIComponent(valor) + expires + "; path=/";
}

function obtenerCookie(nombre) {
    var nameEQ = nombre + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return decodeURIComponent(c.substring(nameEQ.length,c.length));
    }
    return null;
}

function borrarCookie(nombre) {
    document.cookie = nombre+'=; Max-Age=-99999999; path=/';
}