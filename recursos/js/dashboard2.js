const ctx2 = document.getElementById('doughnut')
const names2 = ['carlos', 'pedro', 'maria', 'rosa', 'juan']
const ages2 = [23,34,12,4,18]
const doughnut = new Chart(ctx2,{
    type: 'doughnut',
    data:{
        labels: names2,
        datasets: [{
            label: 'Edad',
            data: ages2,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor:[
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWith: 1.5
        }]
    }
})