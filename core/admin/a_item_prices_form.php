<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>

<div class="admin-card">
    <label class="admin-label">Select an item:</label>
    <select class="admin-select" id="itemDropdown" style="max-width:400px;margin-bottom:1.5rem;">
        <option selected disabled>Choose an item</option>
    </select>

    <div id="itemInfo" style="display:none;margin-bottom:1rem;">
        <div style="display:flex;align-items:center;gap:1rem;">
            <img id="itemImage" style="width:64px;height:64px;border-radius:6px;background:#1a1918;" />
            <div id="itemDetails" style="color:#fff;font-size:0.95rem;"></div>
        </div>
    </div>
</div>

<div class="chart-container">
    <canvas id="priceChart"></canvas>
</div>

<script>
    const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

    Chart.defaults.color = '#bab1a8';
    Chart.defaults.borderColor = '#3a3836';

    var priceChart = null;
    var items = [];

    $(document).ready(async function() {
        var response = await fetch(API_BASE + '/api/itemdata');
        items = await response.json();

        items.forEach(function(item) {
            var opt = document.createElement('option');
            opt.value = item.id_id;
            opt.textContent = 'ID: ' + item.id_id + ' — Tier ' + item.item_tier + ' — ' + item.item_name;
            document.getElementById('itemDropdown').appendChild(opt);
        });
    });

    document.getElementById('itemDropdown').addEventListener('change', async function() {
        var id = this.value;
        var item = items.find(function(i){return i.id_id == id;});
        var prices = await (await fetch(API_BASE + '/api/exchangeitem/' + id)).json();

        document.getElementById('itemInfo').style.display = 'block';
        document.getElementById('itemDetails').innerHTML = '<strong>' + item.item_name + '</strong> — Tier ' + item.item_tier + ' — ID: ' + item.id_id;
        document.getElementById('itemImage').src = 'img/game/sprites/' + item.id_id + '.png';

        if (priceChart) priceChart.destroy();
        priceChart = new Chart(document.getElementById('priceChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: prices.map(function(p){return p.date;}),
                datasets: [{
                    label: 'Price (DIVI)',
                    data: prices.map(function(p){return p.price;}),
                    borderColor: '#6a24fa',
                    backgroundColor: 'rgba(106,36,250,0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.2
                }]
            },
            options: { scales: { y: { beginAtZero: true } }, responsive: true, maintainAspectRatio: false }
        });
    });
</script>
