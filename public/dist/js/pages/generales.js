var Español={
  "sProcessing":     "⏳ Procesando...",
  "sLengthMenu":     "Ver  _MENU_ registros",
  "sZeroRecords":    "😕 No se encontraron resultados",
  "sEmptyTable":     "📭 Ningún dato disponible en esta tabla",
  "sInfo":           "📄 Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
  "sInfoEmpty":      "📄 Mostrando registros del 0 al 0 de un total de 0 registros",
  "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
  "sInfoPostFix":    "",
  "sSearch":         "🔍 Buscar:",
  "sUrl":            "",
  "sInfoThousands":  ",",
  "sLoadingRecords": "⏳ Cargando...",
  "oPaginate": {
      "sNext":     "➡️",
      "sPrevious": "⬅️"
  },
  "oAria": {
      "sSortAscending":  "⬆️: Activar para ordenar la columna de manera ascendente",
      "sSortDescending": "⬇️: Activar para ordenar la columna de manera descendente"
  },
  "buttons": {
      "copy": "📋 Copiar",
      "colvis": "👁️ Visibilidad"
  }
}

document.addEventListener('DOMContentLoaded', function () {
  // Agregar un evento de escucha a todo el documento
  document.addEventListener('input', function (event) {
      // Verificar si el elemento que disparó el evento es un input o textarea
      if ((event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') &&
          event.target.id !== 'txtpassword') { // Ignorar los campos de contraseña
          // Convertir el valor del input/textarea a mayúsculas
          event.target.value = event.target.value.toUpperCase();
      }
  });
});