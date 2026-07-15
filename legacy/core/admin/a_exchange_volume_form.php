<div class="chart-container">
    <canvas id="volumeChart"></canvas>
</div>
<div style="text-align:center;margin-bottom:1rem;">
    <button class="admin-btn admin-btn-primary" id="toggleView">Switch to Monthly View</button>
</div>

<script>
    const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;
    var currentView = 'daily';

    Chart.defaults.color = '#bab1a8';
    Chart.defaults.borderColor = '#3a3836';

    async function fetchVolumeData() {
        var response = await fetch(API_BASE + '/api/exchangevolume');
        var data = await response.json();
        renderChart(data.map(function(e){return e.date}), data.map(function(e){return e.totalVolume}), 'Volume per Day');
    }

    function renderChart(labels, data, label) {
        var ctx = document.getElementById('volumeChart').getContext('2d');
        if (window.myChart) window.myChart.destroy();
        window.myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: 'rgba(106,36,250,0.3)',
                    borderColor: '#6a24fa',
                    borderWidth: 1
                }]
            },
            options: { scales: { y: { beginAtZero: true } }, responsive: true, maintainAspectRatio: false }
        });
    }

    async function fetchMonthlyVolume() {
        var response = await fetch(API_BASE + '/api/exchangevolume');
        var data = await response.json();
        var monthly = {};
        data.forEach(function(e) {
            var m = e.date.slice(0,7);
            monthly[m] = (monthly[m]||0) + e.totalVolume;
        });
        renderChart(Object.keys(monthly), Object.values(monthly), 'Volume per Month');
    }

    document.getElementById('toggleView').addEventListener('click', function() {
        if (currentView === 'daily') {
            fetchMonthlyVolume();
            currentView = 'monthly';
            this.textContent = 'Switch to Daily View';
        } else {
            fetchVolumeData();
            currentView = 'daily';
            this.textContent = 'Switch to Monthly View';
        }
    });

    fetchVolumeData();
</script>
