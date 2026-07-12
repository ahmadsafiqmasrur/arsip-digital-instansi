document.addEventListener('DOMContentLoaded', function () {
    const chartCanvas = document.getElementById('dashboardChart');
    const timeframeSelect = document.getElementById('timeframe');
    const kelurahanSelect = document.getElementById('kelurahanSelect');
    const yearSelect = document.getElementById('yearSelect');

    let chartInstance = null;

    function fetchAndRender() {
        const timeframe = timeframeSelect ? timeframeSelect.value : '1month';
        const kelurahan = kelurahanSelect ? kelurahanSelect.value : 'all';
        const params = new URLSearchParams({ timeframe, kelurahan });

        fetch(window.location.origin + window.location.pathname + '/ajax_summary_counts?' + params.toString())
            .then(r => r.json())
            .then(json => {
                const labels = json.labels || [];
                const data = json.data || [];
                renderChart(labels, data);
            }).catch(err => console.error('Chart fetch error', err));
    }

    function renderChart(labels, data) {
        if (!chartCanvas) return;
        if (chartInstance) {
            chartInstance.data.labels = labels;
            chartInstance.data.datasets[0].data = data;
            // adjust y-axis to use integer ticks up to max data
            const maxVal = data.length ? Math.max.apply(null, data) : 1;
            chartInstance.options.scales.y.beginAtZero = true;
            chartInstance.options.scales.y.ticks = chartInstance.options.scales.y.ticks || {};
            chartInstance.options.scales.y.ticks.stepSize = 1;
            chartInstance.options.scales.y.suggestedMax = Math.max(1, maxVal);
            chartInstance.update();
            return;
        }

        const maxVal = data.length ? Math.max.apply(null, data) : 1;

        chartInstance = new Chart(chartCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Arsip',
                    data: data,
                    backgroundColor: '#0c7a5c',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, suggestedMax: Math.max(1, maxVal) },
                    x: { ticks: { color: '#333' } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    if (timeframeSelect) timeframeSelect.addEventListener('change', fetchAndRender);
    if (kelurahanSelect) kelurahanSelect.addEventListener('change', fetchAndRender);

    // initial load
    fetchAndRender();
});
