<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Counts</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
</head>

<body>
    <div id="itemCountsContainer">
        <h2>Item Counts</h2>
        <table id="itemCountsTable">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <!-- Item counts will be appended here -->
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        function loadStats(callback) {
            $.get('https://siegeworlds-320f73534b59.herokuapp.com/api/itemcounts', result => {
                // Select the table body where the rows will be added
                const tableBody = document.querySelector('#itemCountsTable tbody');

                // Clear existing rows (if any)
                tableBody.innerHTML = '';

                // Append rows to the table
                for (let i = 0; i < result.length; i++) {
                    const row = document.createElement('tr');

                    const imgCell = document.createElement('td');
                    const img = document.createElement('img');
                    img.style.width = '64px';
                    img.style.height = '64px';
                    img.src = `img/game/sprites/${i}.png`;
                    imgCell.appendChild(img);
                    row.appendChild(imgCell);

                    const countCell = document.createElement('td');
                    countCell.textContent = result[i];
                    row.appendChild(countCell);

                    tableBody.appendChild(row);
                }
            });
        }

        window.onload = () => {
            loadStats();
        };
    </script>
</body>

</html>