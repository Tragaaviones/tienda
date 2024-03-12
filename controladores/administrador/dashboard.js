document.addEventListener('DOMContentLoaded', () => {
    // Constante para obtener el número de horas.
    const HOUR = new Date().getHours();
    // Se define una variable para guardar un saludo.
    let greeting = '';
    // Dependiendo del número de horas transcurridas en el día, se asigna un saludo para el usuario.
    if (HOUR < 12) {
        greeting = 'Buenos días';
    } else if (HOUR < 19) {
        greeting = 'Buenas tardes';
    } else if (HOUR <= 23) {
        greeting = 'Buenas noches';
    }
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = `${greeting}, bienvenido administrador`;

});


const ctx = document.getElementById('barchart').getContext('2d');
const names = ['Camisas', 'Zapatos', 'Tenis', 'Camisas coleccionables'];
const stock = [23, 34, 12, 4];

const barchart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: names,
        datasets: [{
            label: 'stock',
            data: stock,
            backgroundColor: [
                'rgba(120, 247, 79, 0.46)',
                'rgba(239, 0, 0, 0.46)',
                'rgba(221, 111, 38, 0.46)',
                'rgba(32, 43, 75, 0.46)',
                'rgba(146, 92, 141, 0.46)',
                'rgba(225, 34, 206, 0.3)'
            ],
            borderColor: [
                'rgba(120, 247, 79, 0.46)',
                'rgba(239, 0, 0, 0.46)',
                'rgba(221, 111, 38, 0.46)',
                'rgba(32, 43, 75, 0.46)',
                'rgba(146, 92, 141, 0.46)',
                'rgba(225, 34, 206, 0.3)'
            ],
            borderWidth: 1.5
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Cantidad de productos',
                color: 'black' // Change the title color
            },
            legend: {
                labels: {
                    color: 'black' // Change the legend labels color
                }
            }
        }
    }
});

const ctx2 = document.getElementById('doughnut')
const names2 = ['Camisas', 'Zapatos', 'Tenis', 'Camisas coleccionables']
const stock2 = [23, 34, 12, 4]
const doughnut = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: names2,
        datasets: [{
            label: 'stock',
            data: stock2,
            backgroundColor: [
                'rgba(120, 247, 79, 0.46)',
                'rgba(239, 0, 0, 0.46)',
                'rgba(221, 111, 38, 0.46)',
                'rgba(32, 43, 75, 0.46)',
                'rgba(146, 92, 141, 0.46)',
                'rgba(225, 34, 206, 0.46)'
            ],
            borderColor: [
                'rgba(120, 247, 79, 0.46)',
                'rgba(239, 0, 0, 0.46)',
                'rgba(221, 111, 38, 0.46)',
                'rgba(32, 43, 75, 0.46)',
                'rgba(146, 92, 141, 0.46)',
                'rgba(225, 34, 206, 0.46)'
            ],
            borderWith: 1.5
        }]
    }
})