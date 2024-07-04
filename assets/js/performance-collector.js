(function () {
  // Load web-vitals library
  var script = document.createElement('script');
  script.src = 'https://unpkg.com/web-vitals@3/dist/web-vitals.iife.js';

  script.onload = function () {
    // Initialize performance measurement once the library is loaded.
    webVitals.onTTFB(sendToAnalytics);
    webVitals.onLCP(sendToAnalytics);
    webVitals.onCLS(sendToAnalytics);
    webVitals.onINP(sendToAnalytics);
  };

  document.head.appendChild(script);

  function sendToAnalytics(metric) {
    var data = new FormData();
    data.append('action', 'save_performance_data');
    data.append('url', window.location.href);
    data.append('nonce', performanceData.nonce);
    data.append(metric.name.toLowerCase(), metric.value);

    console.log(Object.fromEntries(data));

    if (navigator.sendBeacon) {
      navigator.sendBeacon(performanceData.ajax_url, data);
    } else {
      fetch(performanceData.ajax_url, {
        body: data,
        method: 'POST',
        keepalive: true,
      });
    }
  }
})();
