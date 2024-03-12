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
            borderWith: 1.5
        }]
    }
})