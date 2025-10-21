<!DOCTYPE html>
<html lang="en">

<head>

    <?php



    require_once('core/core.php');
    head();
    ?>

    <script type="text/javascript">
        const orderByKills = (a, b) => {
            return a.kills > b.kills ? -1 : a.kills < b.kills ? 1 : 0
        }
        const numberWithCommas = x => {
            var parts = x.toString().split('.')
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',')
            return parts.join('.')
        }

        function loadHighscores() {
            $.get('https://siegeworlds-320f73534b59.herokuapp.com/api/highscore', result => {
                var highscoreString = '<tbody id="hsdata">';
                //result = result.sort(orderByKills)
                for (let i = 0; i < result.length; i++) {
                    highscoreString = highscoreString.concat(`
                    <tr onclick="window.location.href='highscores.php?username=` + result[i].username + `';">
                        <td scope="row">` + (i + 1) + `</td>
                        <td>` + capitalizeFirstLetter(result[i].username) + `</td>
                        <td>` + (result[i].total_kills == null ? 0 : result[i].total_kills) + `</td>
                    </tr>
                    `)

                }
                highscoreString = highscoreString.concat('</tbody>');
                $('#hsdata').replaceWith(
                    highscoreString
                )
            })
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function searchHighscores(username) {
            $.get('https://siegeworlds-320f73534b59.herokuapp.com/api/highscoresearch/' + username, result => {
                var highscoreString = '<table id="hstable" class="table table-dark w-50">';
                var totalGames = parseInt(result.total_games_won) + parseInt(result.total_games_lost);
                var username = capitalizeFirstLetter(result.username);
                highscoreString = highscoreString.concat(`
                    <thead>
                <tr>
                    <th scope="col">Username</th>
                    <th scope="col"> ` + username + `</th>
                </tr>
            </thead>
            <tbody id="hsdata">
            <tr>
            <td>Gameplay hours</td>
                    <td> ` + parseFloat(result.total_gameplay_time / 60).toFixed(1) + `</td>
                </tr>

                    <td>Total Kills</td>
                    <td> ` + result.total_kills + `</td>
                </tr>

                <tr>
                    <td>Headshot Streak</td>
                    <td> ` + result.headshot_streak + `</td>
                </tr>

                <tr>
                    <td>Accuracy</td>
                    <td> ` + parseFloat(result.accuracy).toFixed(2) + `%</td>
                </tr>

                <tr>
                    <td>Average Game Damage</td>
                    <td> ` + result.average_damage + `</td>
                </tr>

                <tr>
                    <td>Average Game Kills</td>
                    <td> ` + result.average_kills + `</td>
                </tr>

                <tr>
                    <td>Total Games Played</td>

                    <td> ` + totalGames + `</td>
                </tr>

            </tbody>
        </table>
                    `)
                $('#hstable').replaceWith(
                    highscoreString
                )
            })
        }

        window.onload = _ => {
            const queryString = window.location.search;
            console.log(queryString);
            const urlParams = new URLSearchParams(queryString);
            const username = urlParams.get('username')
            console.log(username);
            if (username == null || username.length < 1) {
                loadHighscores();
            } else {
                searchHighscores(username);
            }
        }
    </script>


</head>

<body>

    <?php header_nav(); ?>

    <div class="section highscores">
        <div class="dark-bar-pattern flip"></div>
        <div class="hs-body">
            <h1 class="title">High Scores</h1>


            <div id="hssearch">

                <input id="hs-input" type="text" placeholder="Username" name="username">
                <a id="search-button" href="#">Search User</a>

            </div>

            <table id="hstable" class="">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col">Total Kills</th>
                    </tr>
                </thead>
                <tbody id="hsdata">
                    <tr>
                        <td>
                        </td>
                        <td>Loading Data...</td>
                        <td></td>
                    </tr>

                </tbody>
            </table>




        </div>

    </div>
    <div class="dark-bar-pattern bottom"></div>
    </div>


</body>

<footer class="section bg-dark">

    <script>
        // JavaScript to handle the button click event
        document.getElementById('search-button').addEventListener('click', function() {
            var username = document.getElementById('hs-input').value;
            var url = 'highscores.php?username=' + encodeURIComponent(username);
            window.location.href = url;
        });
    </script>

    <?php footer_branding(); ?>
    <?php footer_copyright(); ?>

</footer>

</html>
