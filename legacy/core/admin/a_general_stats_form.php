<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>

<div class="stat-grid" id="statsGrid">
    <div class="stat-item">
        <div class="stat-value" id="playerCount">—</div>
        <div class="stat-label">Total Accounts</div>
    </div>
    <div class="stat-item">
        <div class="stat-value" id="killCount">—</div>
        <div class="stat-label">Total Kills</div>
    </div>
    <div class="stat-item">
        <div class="stat-value" id="diviCount">—</div>
        <div class="stat-label">DIVI Earned</div>
    </div>
    <div class="stat-item">
        <div class="stat-value" id="dau">—</div>
        <div class="stat-label">Users Today (DAU)</div>
    </div>
    <div class="stat-item">
        <div class="stat-value" id="mau">—</div>
        <div class="stat-label">Monthly Users (MAU)</div>
    </div>
</div>

<script type="text/javascript">
    const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function loadStats() {
        $.get(API_BASE + '/api/usercount', function(result) {
            document.getElementById('playerCount').textContent = numberWithCommas(result.user_count || 0);
            document.getElementById('killCount').textContent = numberWithCommas(result.total_monster_kills || 0);
            document.getElementById('diviCount').textContent = numberWithCommas(Math.round((result.total_divi_earned || 0) / 100));
        });
        $.get(API_BASE + '/api/dau', function(result) {
            document.getElementById('dau').textContent = numberWithCommas(result.length || 0);
        });
        $.get(API_BASE + '/api/mau', function(result) {
            document.getElementById('mau').textContent = numberWithCommas(result.length || 0);
        });
    }

    $(document).ready(function() { loadStats(); });
</script>
