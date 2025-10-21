<!doctype html>
<html lang="en">

<head>

    <?php
    require_once('./core/core.php');
    head();
    ?>
    <script type="text/javascript">
        function loadExchangeLogs() {
            var totalVolume = 0;
            $.get('https://siegeworlds-320f73534b59.herokuapp.com/api/exchangelogs', result => {
                var highscoreString = '<tbody id="hsdata">';
                //result = result.sort(orderByKills)
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

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            /* Background color for the header */
            z-index: 100;
            /* Ensure the header stays above the content */
        }

        tbody {
            display: block;
            max-height: 1000px;
            /* Adjust the height to allow scrolling */
            overflow-y: auto;
        }

        thead,
        tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
    </style>

</head>

<body style="background-color: rgb(37, 32, 37);">


    <!--Nav bar / top menu-->
    <div class="container-fluid p-0">


        <main>





            <div class="px-4 py-5 my-5 text-center">
                <div class="d-flex justify-content-center p-2 w-75 mx-auto">
                    <table id="hstable" class="table table-dark">
                        <thead style="position:sticky">
                            <tr>
                                <th scope="col">Date/Time</th>
                                <th scope="col">Item id:</th>
                                <th scope="col">Item name:</th>
                                <th scope="col">quantity:</th>
                                <th scope="col">price:</th>
                                <th scope="col">Buyer:</th>
                                <th scope="col">Seller:</th>
                            </tr>
                        </thead>
                        <tbody id="hsdata">
                            <tr>
                                <th scope="row"></th>
                                <td>Loading Data...</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>




        </main>

    </div>




</body>

</html>