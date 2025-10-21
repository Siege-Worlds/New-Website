<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>

<script type="text/javascript">
    function loadStats() {
        $.get('https://siegeworlds-320f73534b59.herokuapp.com/api/usercount', result => {
            var nPlayers = result.user_count;
            var nKills = result.total_monster_kills;
            var nDivi = result.total_divi_earned / 100;

            // Update the specific elements in the page with the stats
            document.getElementById("playerCount").innerHTML = "Number of accounts: " + nPlayers;
            document.getElementById("killCount").innerHTML = "Number of kills: " + nKills;
            document.getElementById("diviCount").innerHTML = "Number of divi earned: " + nDivi;
        });

        $.get('https://siegeworlds-320f73534b59.herokuapp.com/api/dau', result => {
            // Update the specific element with daily active users
            document.getElementById("dau").innerHTML = "Users (today): " + result.length;
        });

        $.get('https://siegeworlds-320f73534b59.herokuapp.com/api/mau', result => {
            // Update the specific element with monthly active users
            document.getElementById("mau").innerHTML = "Monthly users: " + result.length;
        });
    }

    // Ensure the stats load once the page content is loaded
    window.onload = () => {
        loadStats();
    };
</script>

<!-- Example HTML structure where the stats will be displayed -->
<div>
    <p id="playerCount">Loading player count...</p>
    <p id="killCount">Loading kill count...</p>
    <p id="diviCount">Loading divi count...</p>
    <p id="dau">Loading daily active users...</p>
    <p id="mau">Loading monthly active users...</p>
</div>