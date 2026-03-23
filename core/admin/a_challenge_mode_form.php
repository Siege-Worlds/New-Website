<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Player Name</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody id="challengeModeTable">
            <tr><td colspan="3">Loading...</td></tr>
        </tbody>
    </table>
</div>

<script>
    const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

    $(document).ready(function() {
        $.get(API_BASE + '/api/challengemodelogs', function(result) {
            var html = '';
            result.forEach(function(entry) {
                html += '<tr><td>' + entry.date + '</td><td>' + entry.player_name + '</td><td>' + entry.score + '</td></tr>';
            });
            $('#challengeModeTable').html(html || '<tr><td colspan="3">No data</td></tr>');
        });
    });
</script>
