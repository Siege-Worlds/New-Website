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
    const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

    function loadStats(callback) {
        $.get(API_BASE + '/api/usercount', result => {
            var nPlayers = result.user_count;
            var nKills = result.total_monster_kills;
            var nDivi = result.total_divi_earned / 100;

            //print the stats to html
            document.write("number of accounts: " + nPlayers + "<br>");
            document.write("number of kills: " + nKills + "<br>");
            document.write("number of divi earned: " + nDivi + "<br>");
        });

        $.get(API_BASE + '/api/dau', result => {


            //print the stats to html
            document.write("Users (today): " + result.length + "<br>");
        });

        $.get(API_BASE + '/api/mau', result => {


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