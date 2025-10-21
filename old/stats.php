<!DOCTYPE html>
<html lang="en">

<head>



    <?php
    require_once('core/core.php');
    head();
    ?>



</head>
</body>
<script type="text/javascript">
    function loadStats(callback) {
        $.get('https://siegeworlds-320f73534b59.herokuapp.com/api/usercount', result => {
            var nPlayers = result.user_count;
            var nKills = result.total_monster_kills;
            var nDivi = result.total_divi_earned / 100;

            //print the stats to html
            document.write("number of accounts: " + nPlayers + "<br>");
            document.write("number of kills: " + nKills + "<br>");
            document.write("number of divi earned: " + nDivi + "<br>");
        });

        $.get('https://siegeworlds-320f73534b59.herokuapp.com/api/dau', result => {


            //print the stats to html
            document.write("Users (today): " + result.length + "<br>");
        });

        $.get('https://siegeworlds-320f73534b59.herokuapp.com/api/mau', result => {


            //print the stats to html
            document.write("Monthly users: " + result.length + "<br>");
        });

    }


    window.onload = () => {
        loadStats();
    };
</script>

</body>

</html>