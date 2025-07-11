let pagina = 1;
var table = "";

$(document).ready(function () {
    getUltimosViajes();
    getEstadisticasViajes();
});

function getUltimosViajes() {
    var url = baseURL + 'mant_viajes/ultimos_viajes_dash';
    table = $('#tblultimosviajes').DataTable({
        "destroy": true,
        "language": Español,
        "lengthChange": false,
        "searching": false,
        "info": false,
        "paging": false,
        "autoWidth": false,
        "responsive": true,
        "ajax": {
            'url': url,
            'method': 'GET',
            'dataSrc': function (json) {
                return json.data || [];
            }
        },
        "createdRow": function (row, data, dataIndex) {
            if (data.estado && data.estado.trim().toUpperCase() === 'EN CAMIN') {
                $(row).addClass('text-warning');
            }
        },
        "columns": [
            { 
                "data": "fecha_viaje",
                "width": "15%",
                "className": "text-center"
            },
            { 
                "data": "origen",
                "width": "25%"
            },
            { 
                "data": "destino",
                "width": "25%"
            },
            { 
                "data": "conductor",
                "width": "20%"
            },
            {
                "data": "estado",
                "width": "15%",
                "className": "text-center",
                "render": function (data) {
                    if (data === 'EN CAMINO') {
                        return '<span class="text-danger font-weight-bold">' + data + '</span>';
                    } else if (data === 'ENTREGADO') {
                        return '<span class="text-primary font-weight-bold">' + data + '</span>';
                    }
                    return data;
                }
            }
        ],
    });
}

function getEstadisticasViajes() {
    var url = baseURL + 'mant_viajes/estadisticas_viajes_dash';
    
    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            crearGraficoPie(response.data);
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar estadísticas:', error);
        }
    });
}

function crearGraficoPie(data) {
    var ctx = document.getElementById('pieChartViajes').getContext('2d');
    
    // Preparar los datos para el gráfico
    var labels = [];
    var valores = [];
    var colores = [];
    
    data.forEach(function(item) {
        labels.push(item.estado);
        valores.push(parseInt(item.total));
        
        // Asignar colores según el estado
        if (item.estado === 'EN CAMINO') {
            colores.push('#dc3545'); // Rojo
        } else if (item.estado === 'ENTREGADO') {
            colores.push('#007bff'); // Azul
        }
    });
    
    var pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: valores,
                backgroundColor: colores,
                borderColor: '#ffffff',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1,
            legend: {
                display: false
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var label = data.labels[tooltipItem.index];
                        var value = data.datasets[0].data[tooltipItem.index];
                        var total = data.datasets[0].data.reduce(function(a, b) {
                            return a + b;
                        }, 0);
                        var percentage = Math.round((value / total) * 100);
                        return label + ': ' + value + ' (' + percentage + '%)';
                    }
                }
            }
        }
    });
    
    // Crear leyenda personalizada con totales
    crearLeyendaPersonalizada(data);
}

function crearLeyendaPersonalizada(data) {
    // Limpiar cualquier leyenda existente
    $('.leyenda-personalizada').remove();
    
    var leyendaHTML = '<div class="leyenda-personalizada"><div class="row mt-3">';
    
    data.forEach(function(item) {
        var color = '';
        var iconClass = '';
        
        if (item.estado === 'EN CAMINO') {
            color = '#dc3545'; // Rojo
            iconClass = 'fas fa-truck';
        } else if (item.estado === 'ENTREGADO') {
            color = '#007bff'; // Azul
            iconClass = 'fas fa-check-circle';
        }
        
        leyendaHTML += '<div class="col-6 text-center mb-2">';
        leyendaHTML += '<div class="d-flex align-items-center justify-content-center">';
        leyendaHTML += '<div class="mr-2" style="width: 15px; height: 15px; background-color: ' + color + '; border-radius: 3px;"></div>';
        leyendaHTML += '<i class="' + iconClass + ' mr-1" style="color: ' + color + ';"></i>';
        leyendaHTML += '<span class="font-weight-bold" style="color: #333;">' + item.estado + '</span>';
        leyendaHTML += '</div>';
        leyendaHTML += '<div class="h4 font-weight-bold mt-1" style="color: ' + color + ';">' + item.total + '</div>';
        leyendaHTML += '</div>';
    });
    
    leyendaHTML += '</div></div>';
    
    // Insertar la leyenda después del contenedor del canvas
    $('.chart-responsive').after(leyendaHTML);
}
