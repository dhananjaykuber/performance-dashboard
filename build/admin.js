/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************!*\
  !*** ./assets/js/admin.js ***!
  \****************************/
// import '../css/admin.css';

document.addEventListener('DOMContentLoaded', function () {
  console.log('Performance Data:', performanceData);
  const charts = {};
  const metrics = ['ttfb', 'lcp', 'cls', 'inp'];
  const colors = {
    ttfb: 'rgb(54, 162, 235)',
    lcp: 'rgb(255, 99, 132)',
    cls: 'rgb(255, 206, 86)',
    inp: 'rgb(75, 192, 192)'
  };
  function createChart(metric) {
    const ctx = document.getElementById(`${metric}Chart`).getContext('2d');
    return new Chart(ctx, {
      type: 'line',
      data: {
        labels: performanceData.labels,
        datasets: [{
          label: metric.toUpperCase(),
          data: performanceData[metric],
          borderColor: colors[metric],
          backgroundColor: colors[metric] + '40',
          // 40 is for 25% opacity
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            type: 'time',
            time: {
              unit: 'hour',
              displayFormats: {
                hour: 'MMM d, HH:mm'
              }
            },
            title: {
              display: true,
              text: 'Date'
            }
          },
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: metric === 'cls' ? 'Score' : 'Time (ms)'
            }
          }
        },
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  }
  metrics.forEach(metric => {
    charts[metric] = createChart(metric);
  });

  // URL selector functionality
  const urlSelect = document.getElementById('url-select');
  urlSelect.addEventListener('change', function () {
    const selectedUrl = this.value;
    // Here you would fetch new data for the selected URL
    // and then update the charts
    // For example:
    // fetchDataForUrl(selectedUrl).then(newData => {
    //     updateCharts(newData);
    // });
  });
  function updateCharts(newData) {
    metrics.forEach(metric => {
      charts[metric].data.labels = newData.labels;
      charts[metric].data.datasets[0].data = newData[metric];
      charts[metric].update();
    });
  }
});
/******/ })()
;
//# sourceMappingURL=admin.js.map