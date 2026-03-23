<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>

<div class="chart-container">
    <canvas id="signupChart"></canvas>
</div>

<div class="admin-card" style="margin-top:1.5rem;max-height:50vh;overflow-y:auto;">
    <h3>New Users</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>IP Address</th>
                <th>Signup Date</th>
                <th>Minutes Played</th>
                <th>Last Login</th>
            </tr>
        </thead>
        <tbody id="newUsersBody">
            <tr><td colspan="5">Loading...</td></tr>
        </tbody>
    </table>
</div>

<script>
    const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

    Chart.defaults.color = '#bab1a8';
    Chart.defaults.borderColor = '#3a3836';

    $(document).ready(function() {
        $.get(API_BASE + '/api/dailysignups', function(result) {
            new Chart(document.getElementById('signupChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: result.map(function(e){return e.date;}),
                    datasets: [{
                        label: 'Signups per Day',
                        data: result.map(function(e){return e.signups;}),
                        backgroundColor: 'rgba(106,36,250,0.3)',
                        borderColor: '#6a24fa',
                        borderWidth: 1
                    }]
                },
                options: { scales: { y: { beginAtZero: true } }, responsive: true, maintainAspectRatio: false }
            });
        });

        $.get(API_BASE + '/api/newusers', function(result) {
            var html = '';
            result.reverse().forEach(function(u) {
                html += '<tr><td>' + u.username + '</td><td>' + u.ip.split(':')[0].replace('/','') + '</td><td>' + u.date + '</td><td>' + u.total_minutes_played + '</td><td>' + u.last_login_date + '</td></tr>';
            });
            $('#newUsersBody').html(html || '<tr><td colspan="5">No data</td></tr>');
        });
    });
</script>
