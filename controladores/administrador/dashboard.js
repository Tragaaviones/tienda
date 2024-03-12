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
                'rgba(191, 64, 130, 0.2)',
                'rgba(0, 245, 217, 0.2)',
                'rgba(204, 218, 71, 0.2)',
                'rgba(245, 0, 0, 0.2)',
                'rgba(146, 92, 141, 0.2)',
                'rgba(225, 34, 206, 0.3)'
            ],
            borderColor: [
                'rgba(191, 64, 130, 0.2)',
                'rgba(0, 245, 217, 0.2)',
                'rgba(204, 218, 71, 0.2)',
                'rgba(245, 0, 0, 0.2)',
                'rgba(146, 92, 141, 0.2)',
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