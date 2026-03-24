<?php
require_once('core/core.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php head(); ?>

    <script type="text/javascript">
        const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

        var collectionData = [];
        var currentSort = 'collection_points';

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
            collectionData.sort((a, b) => (b[field] || 0) - (a[field] || 0));
            renderCollection();

            document.querySelectorAll('#coltable thead th.sortable').forEach(th => {
                th.classList.remove('sort-active');
            });
            document.getElementById('sort-' + field).classList.add('sort-active');
        }

        function renderCollection() {
            var html = '<tbody id="coldata">';
            for (var i = 0; i < collectionData.length; i++) {
                var row = collectionData[i];
                var safeUsername = row.username.replace(/['"<>&]/g, '');
                var suspect = row.collection_points > 500 && (row.kills || 0) < 1000;
                html += '<tr onclick="window.location.href=\'leaderboards.php?username=' + encodeURIComponent(safeUsername) + '\';">' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td>' + (suspect ? '* ' : '') + capitalizeFirstLetter(safeUsername) + '</td>' +
                    '<td>' + numberWithCommas(row.collection_points) + '</td>' +
                    '<td>' + numberWithCommas(row.total_items) + '</td>' +
                    '<td>' + numberWithCommas(row.t8 || 0) + '</td>' +
                    '<td>' + numberWithCommas(row.t7 || 0) + '</td>' +
                    '<td>' + numberWithCommas(row.t6) + '</td>' +
                    '<td>' + numberWithCommas(row.t5) + '</td>' +
                    '<td>' + numberWithCommas(row.t4) + '</td>' +
                    '<td>' + numberWithCommas(row.t3) + '</td>' +
                    '<td>' + numberWithCommas(row.t2) + '</td>' +
                    '<td>' + numberWithCommas(row.t1) + '</td>' +
                    '</tr>';
            }
            html += '</tbody>';
            $('#coldata').replaceWith(html);
        }

        function loadCollection() {
            $.get(API_BASE + '/api/collectionleaderboard', function(result) {
                // Cross-reference with highscore data for cheater detection
                $.get(API_BASE + '/api/highscore', function(hsResult) {
                    var killMap = {};
                    for (var i = 0; i < hsResult.length; i++) {
                        killMap[hsResult[i].username] = hsResult[i].total_kills;
                    }
                    for (var i = 0; i < result.length; i++) {
                        result[i].kills = killMap[result[i].username] || 0;
                    }
                    collectionData = result;
                    sortBy(currentSort);
                });
            });
        }

        window.onload = function() {
            loadCollection();
        }
    </script>
    <style>
        #coltable thead th.sortable {
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 1.2em;
        }
        #coltable thead th.sortable:hover {
            color: #fff;
            cursor: pointer;
        }
        #coltable thead th.sortable::after {
            content: '▼';
            font-size: 0.6em;
            margin-left: 0.4em;
            opacity: 0.3;
        }
        #coltable thead th.sort-active::after {
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
            <h1 class="title">Collections</h1>

            <table id="coltable" class="">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col" id="sort-collection_points" class="sortable sort-active" onclick="sortBy('collection_points')">Points</th>
                        <th scope="col" id="sort-total_items" class="sortable" onclick="sortBy('total_items')">Items</th>
                        <th scope="col" id="sort-t8" class="sortable" onclick="sortBy('t8')">T8</th>
                        <th scope="col" id="sort-t7" class="sortable" onclick="sortBy('t7')">T7</th>
                        <th scope="col" id="sort-t6" class="sortable" onclick="sortBy('t6')">T6</th>
                        <th scope="col" id="sort-t5" class="sortable" onclick="sortBy('t5')">T5</th>
                        <th scope="col" id="sort-t4" class="sortable" onclick="sortBy('t4')">T4</th>
                        <th scope="col" id="sort-t3" class="sortable" onclick="sortBy('t3')">T3</th>
                        <th scope="col" id="sort-t2" class="sortable" onclick="sortBy('t2')">T2</th>
                        <th scope="col" id="sort-t1" class="sortable" onclick="sortBy('t1')">T1</th>
                    </tr>
                </thead>
                <tbody id="coldata">
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

</body>

</html>
