import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

renderChart()

function renderChart(){
    let element = document.getElementById('log-chart');

    if(element == null) return;

    if(element.hasAttribute('x-service')){
        let service = element.getAttribute('x-service');

        let httpRequest = new XMLHttpRequest();
        httpRequest.open("GET", "/api/logs/" + service);
        httpRequest.send();

        httpRequest.onreadystatechange = (e) => {
            if(httpRequest.readyState === 4 && httpRequest.status === 200){
                let response = JSON.parse(httpRequest.responseText);
                const chart = new Chart(element, {
                    type: 'line',
                    data: {
                        datasets: [
                            {
                                label: response.name,
                                data: response.times,
                                fill: false,
                                borderColor: 'rgb(105,100,255)',
                                cubicInterpolationMode: 'monotone',
                                pointRadius: 0,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            intersect: false
                        },
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Time'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Response Time (s)'
                                }
                            }
                        }
                    }
                });
            }
        }
    }
}