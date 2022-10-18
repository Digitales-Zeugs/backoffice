require('chart.js');

var ctxLines = document.getElementById('canvasLines').getContext('2d');
new Chart(ctxLines, {
    type: 'line',
    data: {
        labels: worksDays.map(e => e.created),
        datasets: [
            {
                label: 'Nuevas Solicitudes',
                data: worksDays.map(e => e.count),
                fill: false
            }
        ]
    },
    options: {
        scales: {
            yAxes: [ { ticks: {
                min: 0,
                precision: 0
            } } ]
        }
    }
});

var ctxSemiCircle = document.getElementById('canvasSemiCircle').getContext('2d');
new Chart(ctxSemiCircle, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: worksStatus.slice(0, -1).map(e => e.count),
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            label: 'Dataset 1'
        }],
        labels: worksStatus.slice(0, -1).map(e => e.name),
    },
    options: {
        responsive: true,
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: 'Estado de los Trámites Activos'
        },
        animation: {
            animateScale: true,
            animateRotate: true
        },
        circumference: Math.PI,
        rotation: -Math.PI
    }
});
