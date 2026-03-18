<!DOCTYPE html>
<html lang="en">

<head>

    <?php



    require_once('core/core.php');
    head();
    ?>

    <script type="text/javascript">
        const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

        var leaderboardData = [];
        var currentSort = 'level';

        const numberWithCommas = x => {
            var parts = x.toString().split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            return parts.join('.');
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function calculateLevel(kills, headshots, bullseyes) {
            if (kills <= 0) return 1;
            var hsRate = kills > 0 ? headshots / kills : 0;
            var bsRate = kills > 0 ? bullseyes / kills : 0;
            var multiplier = 1.0 + (hsRate * 1.5) + (bsRate * 4.0);
            var effectiveScore = kills * multiplier;
            var level = 1;
            var threshold = 10;
            var cumulative = 0;
            while (cumulative + threshold <= effectiveScore) {
                cumulative += threshold;
                level++;
                threshold = Math.round(threshold * 1.35);
            }
            return level;
        }

        function sortBy(field) {
            currentSort = field;
            if (field === 'username') {
                leaderboardData.sort((a, b) => a.username.localeCompare(b.username));
            } else {
                leaderboardData.sort((a, b) => (b[field] || 0) - (a[field] || 0));
            }
            renderLeaderboard();

            // Update active header styling
            document.querySelectorAll('#hstable thead th.sortable').forEach(th => {
                th.classList.remove('sort-active');
            });
            document.getElementById('sort-' + field).classList.add('sort-active');
        }

        function renderLeaderboard() {
            var html = '<tbody id="hsdata">';
            for (var i = 0; i < leaderboardData.length; i++) {
                var row = leaderboardData[i];
                var safeUsername = row.username.replace(/['"<>&]/g, '');
                html += '<tr onclick="window.location.href=\'leaderboards.php?username=' + encodeURIComponent(safeUsername) + '\';">' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td>' + (row.suspect ? '* ' : '') + capitalizeFirstLetter(safeUsername) + '</td>' +
                    '<td>' + row.level + '</td>' +
                    '<td>' + numberWithCommas(row.total_kills || 0) + '</td>' +
                    '<td>' + (row.total_damage < 0 ? 'OVERKILL' : numberWithCommas(row.total_damage || 0)) + '</td>' +
                    '<td>' + numberWithCommas(row.horde_points || 0) + '</td>' +
                    '<td>' + numberWithCommas(row.headshots || 0) + '</td>' +
                    '<td>' + (row.head_pct).toFixed(1) + '%</td>' +
                    '<td>' + numberWithCommas(row.bullseyes || 0) + '</td>' +
                    '<td>' + (row.bull_pct).toFixed(1) + '%</td>' +
                    '</tr>';
            }
            html += '</tbody>';
            $('#hsdata').replaceWith(html);
        }

        function loadLeaderboard() {
            $.get(API_BASE + '/api/highscore', function(result) {
                leaderboardData = [];
                for (var i = 0; i < result.length; i++) {
                    if (result[i].total_kills != null && result[i].total_kills > 0) {
                        var kills = result[i].total_kills || 0;
                        var headshots = result[i].total_headshots || 0;
                        var bullseyes = result[i].total_bullseye || 0;
                        leaderboardData.push({
                            username: result[i].username,
                            total_kills: kills,
                            total_damage: (result[i].total_damage || 0) < 0 ? -1 : (result[i].total_damage || 0),
                            horde_points: result[i].horde_points || 0,
                            headshots: headshots,
                            bullseyes: bullseyes,
                            total_hits: result[i].total_hits || 0,
                            head_pct: (result[i].total_hits || 0) > 0 ? (headshots / result[i].total_hits) * 100 : 0,
                            bull_pct: (result[i].total_hits || 0) > 0 ? (bullseyes / result[i].total_hits) * 100 : 0,
                            level: calculateLevel(kills, headshots, bullseyes),
                            total_games: result[i].total_games || 0,
                            suspect: kills > 10000 && (headshots / kills) > 1.2 && (bullseyes / kills) > 0.07 && ((result[i].total_damage || 0) / kills) < 50
                        });
                    }
                }
                sortBy(currentSort);
            });
        }

        function searchPlayer(username) {
            $.get(API_BASE + '/api/highscoresearch/' + encodeURIComponent(username), function(result) {
                if (!result || !result.username) {
                    $('#hstable').replaceWith('<p id="hstable" style="text-align:center; padding:2rem;">Player not found.</p>');
                    return;
                }
                var name = capitalizeFirstLetter(result.username);
                var totalGames = parseInt(result.total_games_won || 0) + parseInt(result.total_games_lost || 0);
                var html = '<table id="hstable" class="">' +
                    '<thead><tr><th>Stat</th><th>' + name + '</th></tr></thead>' +
                    '<tbody id="hsdata">' +
                    '<tr><td>Gameplay Hours</td><td>' + parseFloat((result.total_gameplay_time || 0) / 60).toFixed(1) + '</td></tr>' +
                    '<tr><td>Total Kills</td><td>' + numberWithCommas(result.total_kills || 0) + '</td></tr>' +
                    '<tr><td>Headshot Streak</td><td>' + numberWithCommas(result.headshot_streak || 0) + '</td></tr>' +
                    '<tr><td>Accuracy</td><td>' + parseFloat(result.accuracy || 0).toFixed(2) + '%</td></tr>' +
                    '<tr><td>Average Game Damage</td><td>' + numberWithCommas(result.average_damage || 0) + '</td></tr>' +
                    '<tr><td>Average Game Kills</td><td>' + numberWithCommas(result.average_kills || 0) + '</td></tr>' +
                    '<tr><td>Total Games Played</td><td>' + numberWithCommas(totalGames) + '</td></tr>' +
                    '</tbody></table>';
                $('#hstable').replaceWith(html);
            });
        }

        window.onload = function() {
            var urlParams = new URLSearchParams(window.location.search);
            var username = urlParams.get('username');
            if (username && username.length > 0) {
                searchPlayer(username);
            } else {
                loadLeaderboard();
            }
        }
    </script>
    <style>
        #hstable thead th.sortable {
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 1.2em;
        }

        #hstable thead th.sortable:hover {
            color: #fff;
            cursor: pointer;
        }

        #hstable thead th.sortable::after {
            content: '▼';
            font-size: 0.6em;
            margin-left: 0.4em;
            opacity: 0.3;
        }

        #hstable thead th.sort-active::after {
            opacity: 1;
            color: #fff;
        }

    </style>


</head>

<body>

    <?php header_nav(); ?>

    <div class="section highscores">
        <div class="dark-bar-pattern flip"></div>
        <div class="container">
            <h1 class="title">Leaderboards</h1>

            <div id="hssearch">
                <input id="hs-input" type="text" placeholder="Username" name="username">
                <a id="search-button" href="#">Search User</a>
            </div>

            <table id="hstable" class="">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col" id="sort-username" class="sortable" onclick="sortBy('username')">Username</th>
                        <th scope="col" id="sort-level" class="sortable sort-active" onclick="sortBy('level')">Level</th>
                        <th scope="col" id="sort-total_kills" class="sortable" onclick="sortBy('total_kills')">Total Kills</th>
                        <th scope="col" id="sort-total_damage" class="sortable" onclick="sortBy('total_damage')">Total Damage</th>
                        <th scope="col" id="sort-horde_points" class="sortable" onclick="sortBy('horde_points')">Horde Points</th>
                        <th scope="col" id="sort-headshots" class="sortable" onclick="sortBy('headshots')">Headshots</th>
                        <th scope="col" id="sort-head_pct" class="sortable" onclick="sortBy('head_pct')">Head%</th>
                        <th scope="col" id="sort-bullseyes" class="sortable" onclick="sortBy('bullseyes')">Bullseyes</th>
                        <th scope="col" id="sort-bull_pct" class="sortable" onclick="sortBy('bull_pct')">Bull%</th>
                    </tr>
                </thead>
                <tbody id="hsdata">
                    <tr>
                        <td></td>
                        <td>Loading Data...</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
    <div class="dark-bar-pattern bottom"></div>

    <footer class="section bg-dark">

        <?php footer_branding(); ?>
        <?php footer_copyright(); ?>

    </footer>

    <script>
        // JavaScript to handle the button click event
        document.getElementById('search-button').addEventListener('click', function() {
            var username = document.getElementById('hs-input').value;
            var url = 'leaderboards.php?username=' + encodeURIComponent(username);
            window.location.href = url;
        });
    </script>

</body>

</html>