(function () {
  // Object to track which metrics have been sent
  var metricsSent = {
    ttfb: false,
    lcp: false,
    cls: false,
    inp: false,
  };

  // Load web-vitals library
  var script = document.createElement('script');
  script.src = 'https://unpkg.com/web-vitals@3/dist/web-vitals.iife.js';

  script.onload = function () {
    // Initialize performance measurement once the library is loaded.
    webVitals.onTTFB(function (metric) {
      sendToAnalytics(metric, 'ttfb');
    });
    webVitals.onLCP(function (metric) {
      sendToAnalytics(metric, 'lcp');
    });
    webVitals.onCLS(function (metric) {
      sendToAnalytics(metric, 'cls');
    });
    webVitals.onINP(function (metric) {
      sendToAnalytics(metric, 'inp');
    });
  };

  document.head.appendChild(script);

  function sendToAnalytics(metric, metricName) {
    var data = new FormData();
    data.append('action', 'save_performance_data');
    data.append('url', window.location.href);
    data.append('nonce', performanceData.nonce);
    data.append(metricName, metric.value);

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

    // Mark this metric as sent
    metricsSent[metricName] = true;

    // Check if all metrics have been sent
    checkAllMetricsSent();
  }

  function checkAllMetricsSent() {
    if (Object.values(metricsSent).every(Boolean)) {
      console.log('All metrics have been sent successfully.');
    }
  }

  // Ensure all metrics are sent, even if some don't fire
  window.addEventListener('unload', function () {
    for (var metric in metricsSent) {
      if (!metricsSent[metric]) {
        console.log(metric + ' was not captured and sent.');
        // You could send a final beacon here for any missing metrics
      }
    }
  });
})();
