<?php
require_once('core/core.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php head(); ?>

    <script type="text/javascript">
        const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

        const numberWithCommas = x => {
            var parts = x.toString().split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            return parts.join('.');
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        var contest = null;
        var countdownInterval = null;

        function loadContest() {
            $.get(API_BASE + '/api/contest/active', function(result) {
                if (!result || !result.active) {
                    document.getElementById('contest-active').style.display = 'none';
                    document.getElementById('contest-none').style.display = 'block';
                    return;
                }
                contest = result;
                document.getElementById('contest-active').style.display = 'block';
                document.getElementById('contest-none').style.display = 'none';
                document.getElementById('contest-name').textContent = contest.name || 'Contest';
                document.getElementById('contest-metric').textContent = 'Competing by: ' + (contest.metric || 'kills').toUpperCase();
                document.getElementById('contest-prizes').innerHTML =
                    '<span class="prize gold">1st: ' + numberWithCommas(contest.prize_1 || 0) + ' DIVI</span>' +
                    '<span class="prize silver">2nd: ' + numberWithCommas(contest.prize_2 || 0) + ' DIVI</span>' +
                    '<span class="prize bronze">3rd: ' + numberWithCommas(contest.prize_3 || 0) + ' DIVI</span>';

                startCountdown(new Date(contest.end_time));
                loadContestLeaderboard();
            }).fail(function() {
                document.getElementById('contest-active').style.display = 'none';
                document.getElementById('contest-none').style.display = 'block';
            });
        }

        function startCountdown(endTime) {
            if (countdownInterval) clearInterval(countdownInterval);
            function update() {
                var now = new Date().getTime();
                var diff = endTime.getTime() - now;
                if (diff <= 0) {
                    document.getElementById('countdown').innerHTML = '<span class="countdown-ended">CONTEST ENDED</span>';
                    clearInterval(countdownInterval);
                    return;
                }
                var days = Math.floor(diff / (1000 * 60 * 60 * 24));
                var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((diff % (1000 * 60)) / 1000);
                document.getElementById('countdown').innerHTML =
                    '<div class="countdown-unit"><span class="countdown-num">' + days + '</span><span class="countdown-label">Days</span></div>' +
                    '<div class="countdown-unit"><span class="countdown-num">' + String(hours).padStart(2,'0') + '</span><span class="countdown-label">Hours</span></div>' +
                    '<div class="countdown-unit"><span class="countdown-num">' + String(minutes).padStart(2,'0') + '</span><span class="countdown-label">Minutes</span></div>' +
                    '<div class="countdown-unit"><span class="countdown-num">' + String(seconds).padStart(2,'0') + '</span><span class="countdown-label">Seconds</span></div>';
            }
            update();
            countdownInterval = setInterval(update, 1000);
        }

        function loadContestLeaderboard() {
            $.get(API_BASE + '/api/contest/leaderboard', function(result) {
                if (!result || result.length === 0) {
                    document.getElementById('contest-lb').innerHTML = '<p style="text-align:center;color:#bab1a8;">No data yet.</p>';
                    return;
                }
                var html = '<table id="contest-table"><thead><tr><th>#</th><th>Player</th><th>Score</th></tr></thead><tbody>';
                for (var i = 0; i < result.length; i++) {
                    var medal = '';
                    if (i === 0) medal = '<span class="medal gold-medal">&#9679;</span> ';
                    if (i === 1) medal = '<span class="medal silver-medal">&#9679;</span> ';
                    if (i === 2) medal = '<span class="medal bronze-medal">&#9679;</span> ';
                    html += '<tr><td>' + (i + 1) + '</td><td>' + medal + capitalizeFirstLetter(result[i].username) + '</td><td>' + numberWithCommas(result[i].score) + '</td></tr>';
                }
                html += '</tbody></table>';
                document.getElementById('contest-lb').innerHTML = html;
            });
        }

        // === ADMIN FUNCTIONS ===

        function showAdminPanel() {
            document.getElementById('admin-panel').style.display = 'block';
        }

        function createContest() {
            var data = {
                name: document.getElementById('adm-name').value,
                metric: document.getElementById('adm-metric').value,
                start_time: document.getElementById('adm-start').value,
                end_time: document.getElementById('adm-end').value,
                prize_1: parseInt(document.getElementById('adm-prize1').value) || 0,
                prize_2: parseInt(document.getElementById('adm-prize2').value) || 0,
                prize_3: parseInt(document.getElementById('adm-prize3').value) || 0,
            };
            if (!data.name || !data.start_time || !data.end_time) {
                alert('Please fill in all required fields.');
                return;
            }
            // TODO: POST to /api/contest/create
            console.log('Create contest:', data);
            alert('Contest creation will work once the API endpoint is connected. Data: ' + JSON.stringify(data));
        }

        function showCancelModal() {
            document.getElementById('cancel-modal').style.display = 'flex';
        }

        function closeCancelModal() {
            document.getElementById('cancel-modal').style.display = 'none';
            document.getElementById('cancel-input').value = '';
        }

        function confirmCancel() {
            if (document.getElementById('cancel-input').value.toLowerCase() !== 'cancel') {
                alert('Type "cancel" to confirm.');
                return;
            }
            // TODO: POST to /api/contest/cancel
            console.log('Contest cancelled');
            alert('Contest cancellation will work once the API endpoint is connected.');
            closeCancelModal();
        }

        window.onload = function() {
            loadContest();
        }
    </script>

    <style>
        .contest-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .contest-header h1 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 5rem;
            color: #fff;
            margin: 0;
        }
        .contest-header h2 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 1.8rem;
            color: #6a24fa;
            margin: 0.5rem 0;
        }

        .prizes {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 1.5rem 0;
        }
        .prize {
            font-family: "Bebas Neue", sans-serif;
            font-size: 1.4rem;
            padding: 0.5rem 1.5rem;
            border-radius: 4px;
        }
        .prize.gold { background: rgba(255,215,0,0.2); color: #ffd700; border: 1px solid #ffd700; }
        .prize.silver { background: rgba(192,192,192,0.2); color: #c0c0c0; border: 1px solid #c0c0c0; }
        .prize.bronze { background: rgba(205,127,50,0.2); color: #cd7f32; border: 1px solid #cd7f32; }

        #countdown {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .countdown-unit {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .countdown-num {
            font-family: "Bebas Neue", sans-serif;
            font-size: 4rem;
            color: #fff;
            background: rgba(106,36,250,0.3);
            border: 1px solid #6a24fa;
            padding: 0.5rem 1rem;
            min-width: 80px;
            text-align: center;
            border-radius: 4px;
        }
        .countdown-label {
            font-family: "Bebas Neue", sans-serif;
            font-size: 1rem;
            color: #bab1a8;
            margin-top: 0.3rem;
        }
        .countdown-ended {
            font-family: "Bebas Neue", sans-serif;
            font-size: 3rem;
            color: #ff4444;
        }

        #contest-table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        #contest-table th {
            background-color: #6a24fa;
            color: white;
            padding: 10px;
            text-align: center;
        }
        #contest-table td {
            padding: 10px;
            color: white;
            text-align: center;
            border-bottom: 1px solid #DADADA30;
        }
        #contest-table tr { background-color: #212529; }
        #contest-table tr:hover { background-color: #101418; }

        .medal { font-size: 1.2rem; }
        .gold-medal { color: #ffd700; }
        .silver-medal { color: #c0c0c0; }
        .bronze-medal { color: #cd7f32; }

        #contest-none {
            text-align: center;
            padding: 3rem;
        }
        #contest-none h2 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 3rem;
            color: #bab1a8;
        }
        #contest-none p { color: #7a7572; }

        /* Admin Panel */
        #admin-panel {
            display: none;
            background: #1a1a1a;
            border: 1px solid #4c4946;
            border-radius: 4px;
            padding: 2rem;
            margin-top: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        #admin-panel h3 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 1.8rem;
            color: #fff;
            margin: 0 0 1rem 0;
            text-align: center;
        }
        #admin-panel label {
            color: #bab1a8;
            font-size: 0.9rem;
            display: block;
            margin-top: 0.75rem;
        }
        #admin-panel input, #admin-panel select {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            background: #2a2928;
            color: #e4dad1;
            border: 1px solid #4c4946;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .admin-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        /* Cancel Modal */
        #cancel-modal {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .cancel-modal-content {
            background: #1a1a1a;
            border: 2px solid #ff4444;
            border-radius: 8px;
            padding: 2rem;
            max-width: 400px;
            text-align: center;
        }
        .cancel-modal-content h3 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 2rem;
            color: #ff4444;
            margin: 0 0 1rem 0;
        }
        .cancel-modal-content p { color: #bab1a8; margin-bottom: 1rem; }
        .cancel-modal-content input {
            width: 200px;
            padding: 8px;
            background: #2a2928;
            color: #e4dad1;
            border: 1px solid #4c4946;
            border-radius: 4px;
            font-size: 1rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        .cancel-modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
    </style>
</head>

<body>

    <?php header_nav(); ?>

    <div class="section highscores">
        <div class="dark-bar-pattern flip"></div>
        <div class="container">

            <!-- No active contest -->
            <div id="contest-none" style="display:none;">
                <h2>No Active Contest</h2>
                <p>Check back soon for the next competition!</p>
                <a class="button is-primary is-medium" onclick="showAdminPanel()" style="margin-top:1rem;cursor:pointer;">Admin: Create Contest</a>
            </div>

            <!-- Active contest -->
            <div id="contest-active" style="display:none;">
                <div class="contest-header">
                    <h1 id="contest-name">Contest</h1>
                    <h2 id="contest-metric">Competing by: KILLS</h2>
                    <div class="prizes" id="contest-prizes"></div>
                </div>

                <div id="countdown"></div>

                <div id="contest-lb">
                    <p style="text-align:center;color:#bab1a8;">Loading...</p>
                </div>

                <div style="text-align:center;margin-top:2rem;">
                    <a class="button is-primary is-medium" onclick="showAdminPanel()" style="cursor:pointer;">Admin: Manage Contest</a>
                </div>
            </div>

            <!-- Admin Panel -->
            <div id="admin-panel">
                <h3>Create New Contest</h3>
                <label>Contest Name
                    <input type="text" id="adm-name" placeholder="e.g. Weekend Kill Frenzy">
                </label>
                <label>Metric
                    <select id="adm-metric">
                        <option value="total_kills">Kills</option>
                        <option value="total_damage">Damage</option>
                        <option value="horde_points">Horde Points</option>
                        <option value="total_headshots">Headshots</option>
                        <option value="total_bullseye">Bullseyes</option>
                    </select>
                </label>
                <label>Start Date/Time
                    <input type="datetime-local" id="adm-start">
                </label>
                <label>End Date/Time
                    <input type="datetime-local" id="adm-end">
                </label>
                <label>1st Place Prize (DIVI)
                    <input type="number" id="adm-prize1" value="1000">
                </label>
                <label>2nd Place Prize (DIVI)
                    <input type="number" id="adm-prize2" value="500">
                </label>
                <label>3rd Place Prize (DIVI)
                    <input type="number" id="adm-prize3" value="250">
                </label>
                <div class="admin-buttons">
                    <a class="button is-primary is-medium" onclick="createContest()" style="cursor:pointer;flex:1;text-align:center;">Create Contest</a>
                    <a class="button is-outline is-medium" onclick="showCancelModal()" style="cursor:pointer;flex:1;text-align:center;color:#ff4444;">Cancel Active Contest</a>
                </div>
            </div>

        </div>
    </div>
    <div class="dark-bar-pattern bottom"></div>

    <footer class="section bg-dark">
        <?php footer_branding(); ?>
        <?php footer_copyright(); ?>
    </footer>

    <!-- Cancel Modal -->
    <div id="cancel-modal">
        <div class="cancel-modal-content">
            <h3>Cancel Contest?</h3>
            <p>This will end the current contest immediately. Type "cancel" to confirm.</p>
            <input type="text" id="cancel-input" placeholder='Type "cancel"'>
            <div class="cancel-modal-buttons">
                <a class="button is-primary is-medium" onclick="confirmCancel()" style="cursor:pointer;background:#ff4444;">Confirm Cancel</a>
                <a class="button is-outline is-medium" onclick="closeCancelModal()" style="cursor:pointer;">Go Back</a>
            </div>
        </div>
    </div>

</body>
</html>
