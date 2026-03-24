<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>

<style>
    .item-counts-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
    }
    @media (max-width: 768px) {
        .item-counts-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .item-count-card {
        background: #1a1918;
        border: 1px solid #3a3836;
        border-radius: 8px;
        padding: 8px;
        min-height: 90px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: background 0.15s;
        overflow: hidden;
    }
    .item-count-card:hover {
        background: rgba(106, 36, 250, 0.08);
    }
    .item-count-card img {
        width: 74px;
        height: 74px;
        border-radius: 4px;
        flex-shrink: 0;
        object-fit: contain;
        background: #4a4a4a;
    }
    .item-count-info {
        flex: 1;
        min-width: 0;
    }
    .item-count-name {
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .item-count-id {
        color: #7a7572;
        font-size: 0.75rem;
        margin-top: 2px;
    }
    .item-count-value {
        font-family: "Bebas Neue", sans-serif;
        font-size: 1.3rem;
        color: #fff;
        flex-shrink: 0;
        text-align: right;
        padding-right: 0.5rem;
    }
</style>

<div class="admin-card" style="max-height:70vh;overflow-y:auto;">
    <div class="item-counts-grid" id="itemCountsGrid">
        <div style="grid-column:1/-1;color:#7a7572;">Loading...</div>
    </div>
</div>

<script>
    const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

    $(document).ready(function() {
        $.when(
            $.get(API_BASE + '/api/itemcounts'),
            $.get(API_BASE + '/api/itemdata')
        ).done(function(countsRes, itemsRes) {
            var counts = countsRes[0];
            var items = itemsRes[0];

            var infoMap = {};
            items.forEach(function(item) {
                infoMap[item.id_id] = item;
            });

            var MAX_SPRITE = 228;
            var maxId = Math.max(counts.length - 1, MAX_SPRITE);
            var html = '';
            for (var i = 0; i <= maxId; i++) {
                var info = infoMap[i];
                var name = info ? info.item_name : 'Item #' + i;
                var tier = info && info.item_tier > 0 ? ' T' + info.item_tier : '';
                var count = i < counts.length ? counts[i] : 0;
                html += '<div class="item-count-card">'
                    + '<img src="img/sprites/' + i + '.webp" alt="" onerror="this.style.display=\'none\'" />'
                    + '<div class="item-count-info">'
                    + '<div class="item-count-name">' + name + tier + '</div>'
                    + '<div class="item-count-id">#' + i + '</div>'
                    + '</div>'
                    + '<div class="item-count-value">' + count.toLocaleString() + '</div>'
                    + '</div>';
            }
            $('#itemCountsGrid').html(html || '<div style="grid-column:1/-1;color:#7a7572;">No data</div>');
        });
    });
</script>
