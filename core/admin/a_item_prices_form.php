<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
<script src="https://unpkg.com/lightweight-charts@4.1.3/dist/lightweight-charts.standalone.production.js"></script>

<style>
    .item-price-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
    }
    @media (max-width: 768px) {
        .item-price-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .item-price-card {
        background: #1a1918;
        border: 1px solid #3a3836;
        border-radius: 8px;
        padding: 8px;
        min-height: 90px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: background 0.15s, border-color 0.15s;
        overflow: hidden;
        cursor: pointer;
    }
    .item-price-card:hover {
        background: rgba(106, 36, 250, 0.08);
        border-color: #6a24fa;
    }
    .item-price-card.selected {
        background: rgba(106, 36, 250, 0.15);
        border-color: #6a24fa;
    }
    .item-price-card img {
        width: 74px;
        height: 74px;
        border-radius: 4px;
        flex-shrink: 0;
        object-fit: contain;
        background: #4a4a4a;
    }
    .item-price-info {
        flex: 1;
        min-width: 0;
    }
    .item-price-name {
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .item-price-id {
        color: #7a7572;
        font-size: 0.75rem;
        margin-top: 2px;
    }
    .item-price-value {
        font-family: "Bebas Neue", sans-serif;
        font-size: 1.3rem;
        color: #6a24fa;
        flex-shrink: 0;
        text-align: right;
        padding-right: 0.5rem;
    }
    .item-price-value span {
        display: block;
        font-family: "Open Sans", sans-serif;
        font-size: 0.65rem;
        color: #7a7572;
        font-weight: normal;
    }

    /* Chart section */
    .price-chart-section {
        display: none;
        margin-top: 1.5rem;
    }
    .price-chart-section.visible {
        display: block;
    }
    .price-chart-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    .price-chart-header .chart-item-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
    }
    .price-chart-header .chart-item-info img {
        width: 48px;
        height: 48px;
        border-radius: 6px;
        background: #4a4a4a;
    }
    .price-chart-header .chart-item-info .chart-item-name {
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
    }
    .price-chart-header .chart-item-info .chart-item-tier {
        color: #7a7572;
        font-size: 0.85rem;
    }

    /* Time range buttons */
    .time-range-btns {
        display: flex;
        gap: 0;
    }
    .time-range-btn {
        background: #2a2928;
        color: #bab1a8;
        border: 1px solid #3a3836;
        padding: 0.35rem 0.85rem;
        font-size: 0.8rem;
        font-family: "Bebas Neue", sans-serif;
        cursor: pointer;
        transition: all 0.15s;
        text-transform: uppercase;
    }
    .time-range-btn:first-child { border-radius: 6px 0 0 6px; }
    .time-range-btn:last-child { border-radius: 0 6px 6px 0; }
    .time-range-btn.active {
        background: #6a24fa;
        color: #fff;
        border-color: #6a24fa;
    }
    .time-range-btn:hover:not(.active) {
        background: #3a3836;
    }

    /* Chart container */
    #tvChartContainer {
        width: 100%;
        height: 420px;
        border-radius: 8px;
        overflow: hidden;
    }

    .chart-hint {
        color: #7a7572;
        font-size: 0.75rem;
        margin-top: 0.5rem;
        text-align: right;
    }
</style>

<div class="admin-card">
    <input type="text" class="admin-input" id="itemSearch" placeholder="Search items by name..." style="max-width:400px;margin-bottom:1rem;" oninput="filterItems()" />
    <div style="max-height:45vh;overflow-y:auto;">
        <div class="item-price-grid" id="itemPriceGrid">
            <div style="grid-column:1/-1;color:#7a7572;">Loading...</div>
        </div>
    </div>
</div>

<div class="price-chart-section" id="chartSection">
    <div class="admin-card">
        <div class="price-chart-header">
            <div class="chart-item-info">
                <img id="chartItemImg" src="" alt="" />
                <div>
                    <div class="chart-item-name" id="chartItemName"></div>
                    <div class="chart-item-tier" id="chartItemTier"></div>
                </div>
            </div>
            <div class="time-range-btns" id="timeRangeBtns">
                <button class="time-range-btn" data-days="7">1W</button>
                <button class="time-range-btn" data-days="30">1M</button>
                <button class="time-range-btn" data-days="90">3M</button>
                <button class="time-range-btn" data-days="365">1Y</button>
                <button class="time-range-btn active" data-days="1095">3Y</button>
            </div>
        </div>
        <div id="tvChartContainer"></div>
        <div class="chart-hint">Scroll to zoom &bull; Drag to pan &bull; Ctrl+drag to select range</div>
    </div>
</div>

<script>
    const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

    var chart = null;
    var areaSeries = null;
    var items = [];
    var allPriceData = [];
    var currentItemId = null;
    var currentDays = 1095;

    // Create the Lightweight Chart once
    function initChart() {
        var container = document.getElementById('tvChartContainer');
        chart = LightweightCharts.createChart(container, {
            layout: {
                background: { color: '#1a1918' },
                textColor: '#bab1a8',
                fontFamily: "'Open Sans', sans-serif",
            },
            grid: {
                vertLines: { color: '#2a2928' },
                horzLines: { color: '#2a2928' },
            },
            crosshair: {
                mode: LightweightCharts.CrosshairMode.Normal,
                vertLine: { color: '#6a24fa', width: 1, style: 2, labelBackgroundColor: '#6a24fa' },
                horzLine: { color: '#6a24fa', width: 1, style: 2, labelBackgroundColor: '#6a24fa' },
            },
            rightPriceScale: {
                borderColor: '#3a3836',
                scaleMargins: { top: 0.1, bottom: 0.05 },
            },
            timeScale: {
                borderColor: '#3a3836',
                timeVisible: false,
                rightOffset: 5,
                barSpacing: 8,
            },
            handleScroll: true,
            handleScale: true,
        });

        areaSeries = chart.addAreaSeries({
            topColor: 'rgba(106, 36, 250, 0.4)',
            bottomColor: 'rgba(106, 36, 250, 0.02)',
            lineColor: '#6a24fa',
            lineWidth: 2,
            crosshairMarkerBackgroundColor: '#6a24fa',
            crosshairMarkerBorderColor: '#fff',
            crosshairMarkerRadius: 4,
        });

        // Resize on window resize
        var ro = new ResizeObserver(function() {
            chart.applyOptions({ width: container.clientWidth });
        });
        ro.observe(container);
    }

    // Parse "YYYY-MM-DD" or other date strings into YYYY-MM-DD for Lightweight Charts
    function parseDate(dateStr) {
        // Handle various date formats
        var d = new Date(dateStr);
        if (isNaN(d.getTime())) return null;
        var y = d.getFullYear();
        var m = ('0' + (d.getMonth() + 1)).slice(-2);
        var day = ('0' + d.getDate()).slice(-2);
        return y + '-' + m + '-' + day;
    }

    $(document).ready(function() {
        $.when(
            $.get(API_BASE + '/api/itemdata'),
            $.get(API_BASE + '/api/exchangelogs')
        ).done(function(itemsRes, logsRes) {
            items = itemsRes[0];
            var logs = logsRes[0];

            var lastPriceMap = {};
            logs.forEach(function(log) {
                lastPriceMap[log.item_id] = log.price;
            });

            // Build lookup from API items
            var infoMap = {};
            items.forEach(function(item) {
                infoMap[item.id_id] = item;
            });

            // Show all sprites 0-228, filling in API data where available
            var MAX_SPRITE = 228;
            var html = '';
            for (var i = 0; i <= MAX_SPRITE; i++) {
                var info = infoMap[i];
                var name = info ? info.item_name : 'Item #' + i;
                var tier = info && info.item_tier > 0 ? ' T' + info.item_tier : '';
                var lastPrice = lastPriceMap[i];
                var priceText = lastPrice != null ? lastPrice.toLocaleString() : '—';
                html += '<div class="item-price-card" data-id="' + i + '" onclick="selectItem(' + i + ')">'
                    + '<img src="img/sprites/' + i + '.webp" alt="" onerror="this.style.display=\'none\'" />'
                    + '<div class="item-price-info">'
                    + '<div class="item-price-name">' + name + tier + '</div>'
                    + '<div class="item-price-id">#' + i + '</div>'
                    + '</div>'
                    + '<div class="item-price-value">' + priceText + (lastPrice != null ? '<span>DIVI</span>' : '') + '</div>'
                    + '</div>';
            }
            $('#itemPriceGrid').html(html || '<div style="grid-column:1/-1;color:#7a7572;">No data</div>');
        });
    });

    function filterItems() {
        var query = document.getElementById('itemSearch').value.toLowerCase();
        document.querySelectorAll('.item-price-card').forEach(function(card) {
            var name = card.querySelector('.item-price-name').textContent.toLowerCase();
            card.style.display = name.indexOf(query) !== -1 ? '' : 'none';
        });
    }

    // Time range button clicks
    document.getElementById('timeRangeBtns').addEventListener('click', function(e) {
        var btn = e.target.closest('.time-range-btn');
        if (!btn) return;
        currentDays = parseInt(btn.dataset.days);
        document.querySelectorAll('.time-range-btn').forEach(function(b) { b.classList.remove('active'); });
        btn.classList.add('active');
        if (currentItemId !== null) renderFilteredChart();
    });

    async function selectItem(id) {
        currentItemId = id;
        var item = items.find(function(i) { return i.id_id == id; });
        var tierText = item.item_tier > 0 ? ' T' + item.item_tier : '';

        // Highlight selected card
        document.querySelectorAll('.item-price-card').forEach(function(c) { c.classList.remove('selected'); });
        document.querySelector('.item-price-card[data-id="' + id + '"]').classList.add('selected');

        // Update header
        document.getElementById('chartItemImg').src = 'img/game/sprites/' + id + '.png';
        document.getElementById('chartItemName').textContent = item.item_name + tierText;
        document.getElementById('chartItemTier').textContent = 'Tier ' + item.item_tier + ' — ID: ' + item.id_id;

        // Fetch price history
        allPriceData = await (await fetch(API_BASE + '/api/exchangeitem/' + id)).json();

        // Show chart section
        document.getElementById('chartSection').classList.add('visible');

        // Initialize chart if first time
        if (!chart) initChart();

        renderFilteredChart();

        document.getElementById('chartSection').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function renderFilteredChart() {
        var cutoff = new Date();
        cutoff.setDate(cutoff.getDate() - currentDays);
        var cutoffStr = cutoff.toISOString().slice(0, 10);

        var filtered = allPriceData.filter(function(p) {
            var d = parseDate(p.date);
            return d && d >= cutoffStr;
        });

        if (filtered.length === 0) filtered = allPriceData;

        // Convert to Lightweight Charts format { time: 'YYYY-MM-DD', value: number }
        // Aggregate by date (use last price per day if multiple trades)
        var dayMap = {};
        filtered.forEach(function(p) {
            var d = parseDate(p.date);
            if (d) dayMap[d] = p.price;
        });

        var chartData = Object.keys(dayMap).sort().map(function(d) {
            return { time: d, value: dayMap[d] };
        });

        areaSeries.setData(chartData);

        // Fit visible range to show all data, with some padding
        if (chartData.length > 0) {
            chart.timeScale().fitContent();
        }
    }
</script>
