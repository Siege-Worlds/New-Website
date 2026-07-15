<div class="chart-container">
    <canvas id="usersChart"></canvas>
</div>
<div style="text-align:center;margin-bottom:1rem;">
    <button class="admin-btn admin-btn-primary" id="toggleUsersView">Switch to Monthly View</button>
</div>

<script>
    const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;
    var usersView = 'daily';

    Chart.defaults.color = '#bab1a8';
    Chart.defaults.borderColor = '#3a3836';

    function renderUsersChart(labels, data, label) {
        var ctx = document.getElementById('usersChart').getContext('2d');
        if (window.usersChartObj) window.usersChartObj.destroy();
        window.usersChartObj = new Chart(ctx, {
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

    async function fetchDailyUsers() {
        var data = await (await fetch(API_BASE + '/api/userdata')).json();
        renderUsersChart(data.map(function(e){return e.date}), data.map(function(e){return e.activeUsers}), 'Active Users per Day');
    }

    async function fetchMonthlyUsers() {
        var data = await (await fetch(API_BASE + '/api/userdata')).json();
        var monthly = {};
        data.forEach(function(e) {
            var m = e.date.slice(0,7);
            monthly[m] = (monthly[m]||0) + e.activeUsers;
        });
        renderUsersChart(Object.keys(monthly), Object.values(monthly), 'Active Users per Month');
    }

    document.getElementById('toggleUsersView').addEventListener('click', function() {
        if (usersView === 'daily') {
            fetchMonthlyUsers();
            usersView = 'monthly';
            this.textContent = 'Switch to Daily View';
        } else {
            fetchDailyUsers();
            usersView = 'daily';
            this.textContent = 'Switch to Monthly View';
        }
    });

    fetchDailyUsers();
</script>
