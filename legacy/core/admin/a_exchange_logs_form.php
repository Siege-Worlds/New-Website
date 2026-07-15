<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>

<div class="admin-card" style="max-height:70vh;overflow-y:auto;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Date/Time</th>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Buyer</th>
                <th>Seller</th>
            </tr>
        </thead>
        <tbody id="hsdata">
            <tr><td colspan="7">Loading...</td></tr>
        </tbody>
    </table>
</div>

<script>
    const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

    function capitalizeFirstLetter(s) {
        return s.charAt(0).toUpperCase() + s.slice(1);
    }

    $(document).ready(function() {
        $.get(API_BASE + '/api/exchangelogs', function(result) {
            var html = '';
            for (var i = result.length - 1; i >= 0; i--) {
                var r = result[i];
                html += '<tr><td>' + r.date + '</td><td>' + r.item_id + '</td><td>' + r.item_name + '</td><td>' + r.amount + '</td><td>' + r.price + '</td><td>' + capitalizeFirstLetter(r.buyer_name) + '</td><td>' + capitalizeFirstLetter(r.seller_name) + '</td></tr>';
            }
            $('#hsdata').html(html || '<tr><td colspan="7">No data</td></tr>');
        });
    });
</script>
