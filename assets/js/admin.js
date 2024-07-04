import '../css/admin.css';

jQuery(document).ready(function ($) {
  if (typeof performanceData !== 'undefined') {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: performanceData.labels,
        datasets: [
          {
            label: 'TTFB',
            data: performanceData.ttfb,
            borderColor: 'rgb(255, 99, 132)',
            fill: false,
          },
          {
            label: 'LCP',
            data: performanceData.lcp,
            borderColor: 'rgb(54, 162, 235)',
            fill: false,
          },
          {
            label: 'CLS',
            data: performanceData.cls,
            borderColor: 'rgb(255, 206, 86)',
            fill: false,
          },
          {
            label: 'INP',
            data: performanceData.inp,
            borderColor: 'rgb(75, 192, 192)',
            fill: false,
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          x: {
            display: true,
            title: {
              display: true,
              text: 'Date',
            },
          },
          y: {
            display: true,
            title: {
              display: true,
              text: 'Value',
            },
          },
        },
      },
    });
  }
});
