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
        var currentSort = 'total_kills';

        const numberWithCommas = x => {
            var parts = x.toString().split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            return parts.join('.');
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function sortBy(field) {
            currentSort = field;
            leaderboardData.sort((a, b) => (b[field] || 0) - (a[field] || 0));
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
                    '<td>' + capitalizeFirstLetter(safeUsername) + '</td>' +
                    '<td>' + numberWithCommas(row.total_kills || 0) + '</td>' +
                    '<td>' + numberWithCommas(row.total_points || 0) + '</td>' +
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
                        leaderboardData.push({
                            username: result[i].username,
                            total_kills: result[i].total_kills || 0,
                            total_points: result[i].total_points || 0
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

        header.header .header-brand img {
            width: 175px;
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
                        <th scope="col">Username</th>
                        <th scope="col" id="sort-total_kills" class="sortable sort-active" onclick="sortBy('total_kills')">Total Kills</th>
                        <th scope="col" id="sort-total_points" class="sortable" onclick="sortBy('total_points')">Total Points</th>
                    </tr>
                </thead>
                <tbody id="hsdata">
                    <tr>
                        <td></td>
                        <td>Loading Data...</td>
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