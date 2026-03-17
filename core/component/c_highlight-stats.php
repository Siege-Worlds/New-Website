<div class="section companion highlight-stats">
    <div class="container">
        <div class="companion-container">
            <div class="stats">
                <div class="stat-box">
                    <h1 id="nAccounts">0</h1>
                    <h2 style="text-align: center;">Accounts Created</h2>
                </div>
                <div class="stat-box">
                    <h1 id="nKills">0</h1>
                    <h2 style="text-align: center;">Monsters Slain</h2>
                </div>
                <div class="stat-box">
                    <h1 id="nDivi">0</h1>
                    <h2 style="text-align: center;">Divi Earned</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

    function loadStats(callback) {
        $.get(API_BASE + '/api/usercount', result => {
            var nPlayers = result.user_count;
            var nKills = result.total_monster_kills;
            var nDivi = result.total_divi_earned / 100;

            // Formatting the numbers
            //if (nKills > 1000000) {
            //    nKills = (nKills / 1000000).toFixed(1) + " Million";
            //}

            //if (nDivi > 1000) {
            //    nDivi = (nDivi / 1000).toFixed(0) + "K+";
            //}

            // Trigger callback with stats
            callback({
                nPlayers,
                nKills,
                nDivi
            });
        });
    }

    function animateValue(id, start, end, duration) {
        let range = end - start;
        let current = start;

        // Adjust increment dynamically based on target value
        let increment = Math.ceil(range / (duration / 50)); // Faster increment for larger values

        let stepTime = 50; // Step every 50ms for smoother animation
        let obj = document.getElementById(id);

        let timer = setInterval(function() {
            current += increment;
            if (current >= end) {
                current = end; // Ensure the final value matches exactly
                clearInterval(timer);
            }
            obj.innerText = current.toLocaleString(); // Format with commas
        }, stepTime);
    }

    function handleStats(stats) {
        animateValue("nAccounts", 0, stats.nPlayers, 2000); // 2-second animation
        animateValue("nKills", 0, Math.floor(parseFloat(stats.nKills)), 2500); // Faster for millions
        animateValue("nDivi", 0, parseInt(stats.nDivi), 2000);
    }

    function observeStatsSection() {
        const section = document.querySelector('.section.companion');
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loadStats(handleStats);
                    observer.disconnect(); // stop observing once stats are loaded
                }
            });
        }, {
            threshold: 0.5
        });

        observer.observe(section);
    }

    window.onload = () => {
        observeStatsSection();
    };
</script>