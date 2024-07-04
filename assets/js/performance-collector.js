import { onTTFB, onCLS, onINP, onLCP } from 'web-vitals';

const metricsSent = {
  ttfb: false,
  lcp: false,
  cls: false,
  inp: false,
};

function sendToAnalytics(metric) {
  const { name, value } = metric;
  const metricName = name.toLowerCase();

  const data = new FormData();
  data.append('action', 'save_performance_data');
  data.append('url', window.location.href);
  data.append('nonce', performanceData.nonce);
  data.append(metricName, value);

  if (navigator.sendBeacon) {
    navigator.sendBeacon(performanceData.ajax_url, data);
  } else {
    fetch(performanceData.ajax_url, {
      body: data,
      method: 'POST',
      keepalive: true,
    });
  }

  metricsSent[metricName] = true;
  console.log(`Sent ${metricName}: ${value}`);

  checkAllMetricsSent();
}

function checkAllMetricsSent() {
  if (Object.values(metricsSent).every(Boolean)) {
    console.log('All metrics have been sent successfully.');
  }
}

onTTFB(sendToAnalytics);
onCLS(sendToAnalytics);
onINP(sendToAnalytics);
onLCP(sendToAnalytics);

window.addEventListener('unload', () => {
  for (const [metric, sent] of Object.entries(metricsSent)) {
    if (!sent) {
      console.log(`${metric} was not captured and sent.`);
    }
  }
});
