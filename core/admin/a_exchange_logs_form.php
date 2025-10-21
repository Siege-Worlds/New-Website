<!-- Adding Bootstrap CSS link -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


<!--Nav bar / top menu-->
<div class="container-fluid p-0">
    <main>
        <div class="px-4 py-5 my-5 text-center">
            <div class="table-responsive w-75 mx-auto">
                <table id="hstable" class="table table-dark table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Date/Time</th>
                            <th scope="col">Item id</th>
                            <th scope="col">Item name</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Price</th>
                            <th scope="col">Buyer</th>
                            <th scope="col">Seller</th>
                        </tr>
                    </thead>
                    <tbody id="hsdata">
                        <tr>
                            <th scope="row"></th>
                            <td colspan="6">Loading Data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>

<script type="text/javascript">
    function loadExchangeLogs() {
        var totalVolume = 0;
        $.get('https://siegeworlds-320f73534b59.herokuapp.com/api/exchangelogs', result => {
            var highscoreString = '<tbody id="hsdata">';
            for (let i = result.length - 1; i >= 0; i--) {
                highscoreString = highscoreString.concat(`
                    <tr>
                        <th scope="row">` + result[i].date + `</th>
                        <td>` + result[i].item_id + `</td>
                        <td>` + result[i].item_name + `</td>
                        <td>` + result[i].amount + `</td>
                        <td>` + result[i].price + `</td>
                        <td>` + capitalizeFirstLetter(result[i].buyer_name) + `</td>
                        <td>` + capitalizeFirstLetter(result[i].seller_name) + `</td>
                    </tr>
                    `)
                totalVolume += result[i].amount;
                totalVolume += result[i].price;
            }
            console.log("total volume " + totalVolume);
            highscoreString = highscoreString.concat('</tbody>');
            $('#hsdata').replaceWith(
                highscoreString
            )
        })
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    window.onload = _ => {
        loadExchangeLogs();
    }
</script>