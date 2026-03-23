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
        var collectionData = [];
        var currentSort = 'level';
        var currentColSort = 'collection_points';
        var activeTab = 'points';
        var flaggedUsers = new Set();
        var banActive = true;

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

        // === POINTS TAB ===

        function sortBy(field) {
            currentSort = field;
            if (field === 'username') {
                leaderboardData.sort((a, b) => a.username.localeCompare(b.username));
            } else {
                leaderboardData.sort((a, b) => (b[field] || 0) - (a[field] || 0));
            }
            renderLeaderboard();
            document.querySelectorAll('#hstable thead th.sortable').forEach(th => { th.classList.remove('sort-active'); });
            document.getElementById('sort-' + field).classList.add('sort-active');
        }

        function renderLeaderboard() {
            var html = '<tbody id="hsdata">';
            var rank = 0;
            for (var i = 0; i < leaderboardData.length; i++) {
                var row = leaderboardData[i];
                if (banActive && flaggedUsers.has(row.username)) continue;
                rank++;
                var safeUsername = row.username.replace(/['"<>&]/g, '');
                html += '<tr onclick="window.location.href=\'leaderboards.php?username=' + encodeURIComponent(safeUsername) + '\';">' +
                    '<td>' + rank + '</td>' +
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

        function buildFlaggedSet(hsResult) {
            for (var i = 0; i < hsResult.length; i++) {
                var p = hsResult[i];
                var kills = p.total_kills || 0;
                if (kills < 10000) continue;
                var hs = p.total_headshots || 0;
                var bs = p.total_bullseye || 0;
                var dmg = p.total_damage || 0;
                var divi = p.total_divi_earned || 0;
                var hsK = hs / kills;
                var bsK = bs / kills;
                var dmgK = dmg / kills;
                var diviK = divi / kills;
                if ((hsK > 1.2 && bsK > 0.07 && dmgK < 5) || diviK > 5.0) {
                    flaggedUsers.add(p.username);
                }
            }
        }

        function loadLeaderboard() {
            $.get(API_BASE + '/api/highscore', function(result) {
                // Build flagged set first
                buildFlaggedSet(result);

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
                            suspect: flaggedUsers.has(result[i].username)
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

        // === COLLECTIONS TAB ===

        function colSortBy(field) {
            currentColSort = field;
            collectionData.sort((a, b) => (b[field] || 0) - (a[field] || 0));
            renderCollection();
            document.querySelectorAll('#coltable thead th.sortable').forEach(th => { th.classList.remove('sort-active'); });
            document.getElementById('colsort-' + field).classList.add('sort-active');
        }

        function renderCollection() {
            var html = '<tbody id="coldata">';
            var rank = 0;
            for (var i = 0; i < collectionData.length; i++) {
                var row = collectionData[i];
                if (banActive && flaggedUsers.has(row.username)) continue;
                rank++;
                var safeUsername = row.username.replace(/['"<>&]/g, '');
                var suspect = row.collection_points > 500 && (row.kills || 0) < 1000;
                html += '<tr onclick="window.location.href=\'leaderboards.php?username=' + encodeURIComponent(safeUsername) + '\';">' +
                    '<td>' + rank + '</td>' +
                    '<td>' + (suspect ? '* ' : '') + capitalizeFirstLetter(safeUsername) + '</td>' +
                    '<td>' + numberWithCommas(row.collection_points) + '</td>' +
                    '<td>' + numberWithCommas(row.total_items) + '</td>' +
                    '<td>' + numberWithCommas(row.t7 || 0) + '</td>' +
                    '<td>' + numberWithCommas(row.t6 || 0) + '</td>' +
                    '<td>' + numberWithCommas(row.t5 || 0) + '</td>' +
                    '<td>' + numberWithCommas(row.t4 || 0) + '</td>' +
                    '<td>' + numberWithCommas(row.t3 || 0) + '</td>' +
                    '<td>' + numberWithCommas(row.t2 || 0) + '</td>' +
                    '<td>' + numberWithCommas(row.t1 || 0) + '</td>' +
                    '<td>' + numberWithCommas(row.t0 || 0) + '</td>' +
                    '</tr>';
            }
            html += '</tbody>';
            $('#coldata').replaceWith(html);
        }

        function loadCollection() {
            $.get(API_BASE + '/api/collectionleaderboard', function(result) {
                $.get(API_BASE + '/api/highscore', function(hsResult) {
                    var killMap = {};
                    for (var i = 0; i < hsResult.length; i++) {
                        killMap[hsResult[i].username] = hsResult[i].total_kills;
                    }
                    for (var i = 0; i < result.length; i++) {
                        result[i].kills = killMap[result[i].username] || 0;
                    }
                    collectionData = result;
                    colSortBy(currentColSort);
                });
            });
        }

        // === VARIOUS TAB ===

        var variousLoaded = false;

        function renderMiniBoard(title, data, valueField, formatFn) {
            var filtered = banActive ? data.filter(function(p) { return !flaggedUsers.has(p.username); }) : data;
            var html = '<div class="mini-board"><h3>' + title + '</h3><table class="mini-table"><thead><tr><th>#</th><th>Player</th><th>Value</th></tr></thead><tbody>';
            for (var i = 0; i < Math.min(10, filtered.length); i++) {
                var row = filtered[i];
                var val = formatFn ? formatFn(row[valueField]) : numberWithCommas(row[valueField] || 0);
                html += '<tr><td>' + (i + 1) + '</td><td>' + capitalizeFirstLetter(row.username) + '</td><td>' + val + '</td></tr>';
            }
            html += '</tbody></table></div>';
            return html;
        }

        function loadVarious() {
            $.get(API_BASE + '/api/highscore', function(hsResult) {
                var players = hsResult.map(function(p) {
                    var kills = p.total_kills || 0;
                    var divi = p.total_divi_earned || 0;
                    var hs = p.total_headshots || 0;
                    var bs = p.total_bullseye || 0;
                    var dmg = p.total_damage || 0;
                    var diviPerKill = kills > 0 ? divi / kills : 0;
                    var hsPerKill = kills > 0 ? hs / kills : 0;
                    var bsPerKill = kills > 0 ? bs / kills : 0;
                    var dmgPerKill = kills > 0 ? dmg / kills : 0;
                    // Bot farm: high divi/kill ratio (legit is under 1.0)
                    var isBotFarm = kills > 10000 && diviPerKill > 5.0;
                    // Exploding bullet cheater
                    var isExploding = kills > 10000 && hsPerKill > 1.2 && bsPerKill > 0.07 && dmgPerKill < 5;
                    return {
                        username: p.username,
                        total_kills: kills,
                        total_minutes_played: p.total_minutes_played || 0,
                        total_games: p.total_games || 0,
                        accuracy: p.accuracy || 0,
                        headshot_streak: p.headshot_streak || 0,
                        average_damage: p.average_damage || 0,
                        total_divi_earned: divi,
                        rocks_mined: p.rocks_mined || 0,
                        fountain_donations: p.fountain_donations || 0,
                        total_hits: p.total_hits || 0,
                        kills_per_min: (p.total_minutes_played || 0) > 0 ? kills / (p.total_minutes_played || 1) : 0,
                        various_suspect: isBotFarm || isExploding
                    };
                });

                var html = '';

                // 1. Most Gameplay Hours
                var byHours = players.filter(function(p) { return p.total_minutes_played > 0; }).sort(function(a, b) { return b.total_minutes_played - a.total_minutes_played; });
                html += renderMiniBoard('Most Gameplay Hours', byHours, 'total_minutes_played', function(v) { return (v / 60).toFixed(0) + ' hrs'; });

                // 3. Most Games Played
                var byGames = players.filter(function(p) { return p.total_games > 0; }).sort(function(a, b) { return b.total_games - a.total_games; });
                html += renderMiniBoard('Most Games Played', byGames, 'total_games');

                // 4. Highest Accuracy (min 10000 kills)
                var byAcc = players.filter(function(p) { return p.total_kills > 10000 && p.accuracy > 0; }).sort(function(a, b) { return b.accuracy - a.accuracy; });
                html += renderMiniBoard('Highest Accuracy', byAcc, 'accuracy', function(v) { return (v * 100).toFixed(1) + '%'; });

                // 5. Best Headshot Streak
                var byStreak = players.filter(function(p) { return p.headshot_streak > 0; }).sort(function(a, b) { return b.headshot_streak - a.headshot_streak; });
                html += renderMiniBoard('Best Headshot Streak', byStreak, 'headshot_streak');

                // 6. Highest Avg Damage Per Game
                var byAvgDmg = players.filter(function(p) { return p.average_damage > 0; }).sort(function(a, b) { return b.average_damage - a.average_damage; });
                html += renderMiniBoard('Highest Avg Damage / Game', byAvgDmg, 'average_damage');

                // 8. Most Divi Earned
                var byDivi = players.filter(function(p) { return p.total_divi_earned > 0; }).sort(function(a, b) { return b.total_divi_earned - a.total_divi_earned; });
                html += renderMiniBoard('Most Divi Earned', byDivi, 'total_divi_earned');

                // 9. Most Rocks Mined
                var byRocks = players.filter(function(p) { return p.rocks_mined > 0; }).sort(function(a, b) { return b.rocks_mined - a.rocks_mined; });
                html += renderMiniBoard('Most Rocks Mined', byRocks, 'rocks_mined');

                // 10. Most Fountain Donations
                var byDonations = players.filter(function(p) { return p.fountain_donations > 0; }).sort(function(a, b) { return b.fountain_donations - a.fountain_donations; });
                html += renderMiniBoard('Most Fountain Donations', byDonations, 'fountain_donations');

                // 18. Kill Efficiency (kills per minute, min 10000 kills)
                var byEfficiency = players.filter(function(p) { return p.total_kills > 10000 && p.total_minutes_played > 0; }).sort(function(a, b) { return b.kills_per_min - a.kills_per_min; });
                html += renderMiniBoard('Kill Efficiency (Kills/Min)', byEfficiency, 'kills_per_min', function(v) { return v.toFixed(1); });

                document.getElementById('various-content').innerHTML = html;
                variousLoaded = true;
            });
        }

        // === CHEATERS TAB ===

        var cheatersLoaded = false;

        function cheaterSortBy(field) {
            cheaterData.sort((a, b) => (b[field] || 0) - (a[field] || 0));
            renderCheaters();
            document.querySelectorAll('#cheattable thead th.sortable').forEach(th => { th.classList.remove('sort-active'); });
            var el = document.getElementById('cheatsort-' + field);
            if (el) el.classList.add('sort-active');
        }

        var cheaterData = [];

        function renderCheaters() {
            var html = '<tbody id="cheatdata">';
            for (var i = 0; i < cheaterData.length; i++) {
                var row = cheaterData[i];
                var checked = row.flagged !== false ? ' checked' : '';
                html += '<tr>' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td><input type="checkbox"' + checked + ' onchange="toggleFlag(\'' + row.username + '\', this.checked)" class="cheat-checkbox"></td>' +
                    '<td>' + capitalizeFirstLetter(row.username) + '</td>' +
                    '<td>' + row.cheat_type + '</td>' +
                    '<td>' + numberWithCommas(row.total_kills) + '</td>' +
                    '<td>' + row.dmg_per_kill.toFixed(1) + '</td>' +
                    '<td>' + row.hs_per_kill.toFixed(2) + '</td>' +
                    '<td>' + row.divi_per_kill.toFixed(1) + '</td>' +
                    '<td>' + numberWithCommas(row.total_divi_earned) + '</td>' +
                    '<td>' + numberWithCommas(row.trade_volume) + '</td>' +
                    '</tr>';
            }
            html += '</tbody>';
            $('#cheatdata').replaceWith(html);
        }

        function toggleFlag(username, checked) {
            // TODO: Save to server via API
            if (checked) {
                flaggedUsers.add(username);
            } else {
                flaggedUsers.delete(username);
            }
            for (var i = 0; i < cheaterData.length; i++) {
                if (cheaterData[i].username === username) {
                    cheaterData[i].flagged = checked;
                    break;
                }
            }
            // Re-render other tabs if ban is active
            if (banActive) {
                if (leaderboardData.length > 0) renderLeaderboard();
                if (collectionData.length > 0) renderCollection();
            }
        }

        function toggleBanAll(checked) {
            banActive = checked;
            // Re-render all tabs
            if (leaderboardData.length > 0) renderLeaderboard();
            if (collectionData.length > 0) renderCollection();
            // Various needs full re-render
            if (variousLoaded) loadVarious();
        }

        function loadCheaters() {
            $.get(API_BASE + '/api/highscore', function(hsResult) {
                $.get(API_BASE + '/api/exchangelogs', function(trades) {
                    // Build trade volume map
                    var sellVolume = {};
                    for (var i = 0; i < trades.length; i++) {
                        var seller = trades[i].seller_name;
                        if (!sellVolume[seller]) sellVolume[seller] = 0;
                        sellVolume[seller] += (trades[i].amount || 0) * (trades[i].price || 0);
                    }

                    cheaterData = [];
                    for (var i = 0; i < hsResult.length; i++) {
                        var p = hsResult[i];
                        var kills = p.total_kills || 0;
                        if (kills < 10000) continue;

                        var hs = p.total_headshots || 0;
                        var bs = p.total_bullseye || 0;
                        var dmg = p.total_damage || 0;
                        var divi = p.total_divi_earned || 0;
                        var hsK = hs / kills;
                        var bsK = bs / kills;
                        var dmgK = dmg / kills;
                        var diviK = divi / kills;

                        var cheatType = null;
                        if (hsK > 1.2 && bsK > 0.07 && dmgK < 5) {
                            cheatType = 'Exploding Bullets';
                        } else if (diviK > 5.0) {
                            cheatType = 'Bot Farm';
                        }

                        if (cheatType) {
                            cheaterData.push({
                                username: p.username,
                                cheat_type: cheatType,
                                total_kills: kills,
                                dmg_per_kill: dmgK,
                                hs_per_kill: hsK,
                                divi_per_kill: diviK,
                                total_divi_earned: divi,
                                trade_volume: sellVolume[p.username] || 0
                            });
                        }
                    }

                    // Also check collection data for item cheaters
                    $.get(API_BASE + '/api/collectionleaderboard', function(colResult) {
                        var killMap = {};
                        for (var i = 0; i < hsResult.length; i++) {
                            killMap[hsResult[i].username] = hsResult[i].total_kills;
                        }
                        for (var i = 0; i < colResult.length; i++) {
                            var cp = colResult[i];
                            var ck = killMap[cp.username] || 0;
                            if (cp.collection_points > 500 && ck < 1000) {
                                // Check if already in list
                                var already = cheaterData.some(function(c) { return c.username === cp.username; });
                                if (!already) {
                                    cheaterData.push({
                                        username: cp.username,
                                        cheat_type: 'Item Injection',
                                        total_kills: ck,
                                        dmg_per_kill: 0,
                                        hs_per_kill: 0,
                                        divi_per_kill: 0,
                                        total_divi_earned: 0,
                                        trade_volume: sellVolume[cp.username] || 0
                                    });
                                }
                            }
                        }

                        cheaterData.sort(function(a, b) { return b.trade_volume - a.trade_volume; });
                        // Populate flagged set
                        for (var i = 0; i < cheaterData.length; i++) {
                            cheaterData[i].flagged = true;
                            flaggedUsers.add(cheaterData[i].username);
                        }
                        renderCheaters();
                        cheatersLoaded = true;
                        // Re-render other tabs to remove cheaters
                        if (banActive) {
                            if (leaderboardData.length > 0) renderLeaderboard();
                            if (collectionData.length > 0) renderCollection();
                        }
                    });
                });
            });
        }

        // === TAB SWITCHING ===

        function switchTab(tab) {
            activeTab = tab;
            ['points','collections','various','cheaters'].forEach(function(t) {
                document.getElementById('tab-' + t).classList.toggle('tab-active', tab === t);
                document.getElementById('panel-' + t).style.display = tab === t ? 'block' : 'none';
            });
            if (tab === 'collections' && collectionData.length === 0) {
                loadCollection();
            }
            if (tab === 'various' && !variousLoaded) {
                loadVarious();
            }
            if (tab === 'cheaters' && !cheatersLoaded) {
                loadCheaters();
            }
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
        .leaderboard-tabs {
            display: flex;
            gap: 0;
        }
        .leaderboard-tabs .tab-btn {
            font-family: "Bebas Neue", sans-serif;
            font-size: 1.5rem;
            padding: 0.5rem 2rem;
            cursor: pointer;
            background: #2a2928;
            color: #bab1a8;
            border: none;
            transition: all 0.2s ease;
            text-transform: uppercase;
        }
        .leaderboard-tabs .tab-btn:first-child {
            border-radius: 4px 0 0 4px;
        }
        .leaderboard-tabs .tab-btn:last-child {
            border-radius: 0 4px 4px 0;
        }
        .leaderboard-tabs .tab-btn.tab-active {
            background: #6a24fa;
            color: #fff;
        }
        .leaderboard-tabs .tab-btn:hover:not(.tab-active) {
            background: #3a3938;
        }

        #coltable {
            width: 100%;
        }

        #hstable thead th.sortable,
        #coltable thead th.sortable {
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 1.2em;
        }

        #hstable thead th.sortable:hover,
        #coltable thead th.sortable:hover {
            color: #fff;
            cursor: pointer;
        }

        #hstable thead th.sortable::after,
        #coltable thead th.sortable::after {
            content: '▼';
            font-size: 0.6em;
            margin-left: 0.4em;
            opacity: 0.3;
        }

        #hstable thead th.sort-active::after,
        #coltable thead th.sort-active::after,
        #cheattable thead th.sort-active::after {
            opacity: 1;
            color: #fff;
        }

        #cheattable {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #cheattable td,
        #cheattable th {
            border-bottom: 1px solid #DADADA70;
            padding: 8px;
            color: white;
            text-align: center;
        }

        #cheattable tr {
            background-color: #212529;
        }

        #cheattable tr:hover {
            background-color: #101418;
        }

        #cheattable th {
            padding-top: 12px;
            padding-bottom: 12px;
            background-color: #aa0000;
            color: white;
        }

        #cheattable thead th.sortable {
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 1.2em;
        }

        #cheattable thead th.sortable:hover {
            color: #fff;
        }

        #cheattable thead th.sortable::after {
            content: '▼';
            font-size: 0.6em;
            margin-left: 0.4em;
            opacity: 0.3;
        }

        .cheat-checkbox {
            width: 16px;
            height: 16px;
            accent-color: #ff8800;
            cursor: pointer;
        }

        .ban-checkbox {
            width: 16px;
            height: 16px;
            accent-color: #ff0000;
            cursor: pointer;
        }

        #various-content {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.5rem;
        }

        .mini-board {
            background: #1a1a1a;
            border-radius: 4px;
            overflow: hidden;
        }

        .mini-board h3 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 1.2rem;
            color: #fff;
            background: #6a24fa;
            padding: 0.6rem 1rem;
            margin: 0;
            text-align: center;
        }

        .mini-table {
            width: 100%;
            border-collapse: collapse;
        }

        .mini-table th {
            display: none;
        }

        .mini-table td {
            padding: 0.4rem 0.8rem;
            color: #e4dad1;
            font-size: 0.85rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .mini-table tr:hover {
            background: rgba(106,36,250,0.15);
        }

        .mini-table td:first-child {
            width: 30px;
            text-align: center;
            color: #7a7572;
        }

        .mini-table td:last-child {
            text-align: right;
            color: #6a24fa;
            font-weight: bold;
        }

    </style>


</head>

<body>

    <?php header_nav(); ?>

    <div class="section highscores">
        <div class="dark-bar-pattern flip"></div>
        <div class="container">
            <h1 class="title">Leaderboards</h1>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <div class="leaderboard-tabs">
                    <button class="tab-btn tab-active" id="tab-points" onclick="switchTab('points')">Points</button>
                    <button class="tab-btn" id="tab-collections" onclick="switchTab('collections')">Collections</button>
                    <button class="tab-btn" id="tab-various" onclick="switchTab('various')">Various</button>
                    <button class="tab-btn" id="tab-cheaters" onclick="switchTab('cheaters')">Wall of Shame</button>
                </div>
                <div id="hssearch" style="margin:0;">
                    <input id="hs-input" type="text" placeholder="Username" name="username">
                    <a id="search-button" href="#">Search User</a>
                </div>
            </div>

            <div id="panel-points">

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
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="panel-collections" style="display:none;">
                <table id="coltable" class="">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Username</th>
                            <th scope="col" id="colsort-collection_points" class="sortable sort-active" onclick="colSortBy('collection_points')">Points</th>
                            <th scope="col" id="colsort-total_items" class="sortable" onclick="colSortBy('total_items')">Items</th>
                            <th scope="col" id="colsort-t7" class="sortable" onclick="colSortBy('t7')">T8</th>
                            <th scope="col" id="colsort-t6" class="sortable" onclick="colSortBy('t6')">T7</th>
                            <th scope="col" id="colsort-t5" class="sortable" onclick="colSortBy('t5')">T6</th>
                            <th scope="col" id="colsort-t4" class="sortable" onclick="colSortBy('t4')">T5</th>
                            <th scope="col" id="colsort-t3" class="sortable" onclick="colSortBy('t3')">T4</th>
                            <th scope="col" id="colsort-t2" class="sortable" onclick="colSortBy('t2')">T3</th>
                            <th scope="col" id="colsort-t1" class="sortable" onclick="colSortBy('t1')">T2</th>
                            <th scope="col" id="colsort-t0" class="sortable" onclick="colSortBy('t0')">T1</th>
                        </tr>
                    </thead>
                    <tbody id="coldata">
                        <tr>
                            <td></td>
                            <td>Loading Data...</td>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="panel-various" style="display:none;">
                <div id="various-content">
                    <p style="text-align:center;color:#bab1a8;">Loading...</p>
                </div>
            </div>

            <div id="panel-cheaters" style="display:none;">
                <p style="text-align:center;color:#bab1a8;margin-bottom:1rem;">Players flagged by automated cheat detection. Three types: <strong style="color:#ff4444;">Exploding Bullets</strong> (hacked ammo), <strong style="color:#ff8800;">Bot Farm</strong> (automated Divi farming), and <strong style="color:#ff44ff;">Item Injection</strong> (spawned items without playing).</p>
                <div style="margin-bottom:1rem;padding:0.75rem 1rem;background:#330000;border-radius:4px;display:flex;align-items:center;gap:10px;">
                    <input type="checkbox" checked id="ban-master" onchange="toggleBanAll(this.checked)" class="ban-checkbox">
                    <label for="ban-master" style="color:#ff4444;font-family:'Bebas Neue',sans-serif;font-size:1.3rem;cursor:pointer;">BAN ALL FLAGGED FROM LEADERBOARDS</label>
                </div>
                <table id="cheattable" class="">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Flagged</th>
                            <th scope="col">Username</th>
                            <th scope="col">Cheat Type</th>
                            <th scope="col" id="cheatsort-total_kills" class="sortable" onclick="cheaterSortBy('total_kills')">Kills</th>
                            <th scope="col" id="cheatsort-dmg_per_kill" class="sortable" onclick="cheaterSortBy('dmg_per_kill')">Dmg/Kill</th>
                            <th scope="col" id="cheatsort-hs_per_kill" class="sortable" onclick="cheaterSortBy('hs_per_kill')">HS/Kill</th>
                            <th scope="col" id="cheatsort-divi_per_kill" class="sortable" onclick="cheaterSortBy('divi_per_kill')">Divi/Kill</th>
                            <th scope="col" id="cheatsort-total_divi_earned" class="sortable" onclick="cheaterSortBy('total_divi_earned')">Total Divi</th>
                            <th scope="col" id="cheatsort-trade_volume" class="sortable sort-active" onclick="cheaterSortBy('trade_volume')">Trade Volume</th>
                        </tr>
                    </thead>
                    <tbody id="cheatdata">
                        <tr>
                            <td></td>
                            <td></td>
                            <td>Loading Data...</td>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
    <div class="dark-bar-pattern bottom"></div>

    <footer class="section bg-dark">

        <?php footer_branding(); ?>
        <?php footer_copyright(); ?>

    </footer>

    <script>
        document.getElementById('search-button').addEventListener('click', function() {
            var username = document.getElementById('hs-input').value;
            var url = 'leaderboards.php?username=' + encodeURIComponent(username);
            window.location.href = url;
        });
    </script>

</body>

</html>
