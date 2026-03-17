<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Price Chart</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js for graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Item Price History</h1>

        <!-- Dropdown for item selection -->
        <div class="mb-4">
            <label for="itemDropdown" class="form-label">Select an item:</label>
            <select class="form-select" id="itemDropdown" aria-label="Select Item">
                <option selected disabled>Choose an item</option>
            </select>
        </div>

        <!-- Display selected item info -->
        <div id="itemInfo" class="mb-3">
            <h3>Item Information</h3>
            <p id="itemDetails"></p>
            <img id="itemImage" alt="Item Image" style="max-width: 150px;" />
        </div>

        <!-- Chart container -->
        <div class="card">
            <div class="card-body">
                <canvas id="priceChart" width="100vw" height="500"></canvas>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

        document.addEventListener('DOMContentLoaded', async function() {
            const itemDropdown = document.getElementById('itemDropdown');
            const itemDetails = document.getElementById('itemDetails');
            const itemImage = document.getElementById('itemImage');
            const priceChartCtx = document.getElementById('priceChart').getContext('2d');
            let priceChart;

            // Fetch item data from the API
            async function fetchItems() {
                const response = await fetch(API_BASE + '/api/itemdata');
                return await response.json();
            }

            // Fetch price data for a specific item from the API
            async function fetchItemPrices(itemId) {
                const response = await fetch(`${API_BASE}/api/exchangeitem/${itemId}`);
                return await response.json();
            }

            // Populate the dropdown with items
            const items = await fetchItems();
            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id_id;
                option.text = `ID: ${item.id_id}, Tier: ${item.item_tier}, Name: ${item.item_name}`;
                itemDropdown.appendChild(option);
            });

            // Handle item selection from the dropdown
            itemDropdown.addEventListener('change', async function() {
                const selectedItemId = this.value;
                const selectedItem = items.find(item => item.id_id == selectedItemId);
                const itemPrices = await fetchItemPrices(selectedItemId);

                // Display selected item info
                itemDetails.innerHTML = `
                    <strong>ID:</strong> ${selectedItem.id_id}<br>
                    <strong>Tier:</strong> ${selectedItem.item_tier}<br>
                    <strong>Name:</strong> ${selectedItem.item_name}
                `;
                itemImage.src = `https://www.siegeworlds.com/img/game/sprites/${selectedItem.id_id}.png`;

                // Prepare data for chart
                const labels = itemPrices.map(price => price.date);
                const prices = itemPrices.map(price => price.price);

                // If the chart exists, destroy it before creating a new one
                if (priceChart) {
                    priceChart.destroy();
                }

                // Create bar chart
                priceChart = new Chart(priceChartCtx, {
                    type: 'line', // Change 'bar' to 'line'
                    data: {
                        labels: labels, // These are the dates
                        datasets: [{
                            label: 'Price (Divi)',
                            data: prices, // These are the prices
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)', // Line color
                            borderWidth: 2, // Thickness of the line
                            fill: false, // Disable the fill below the line
                            tension: 0.1 // Add some tension to smooth the line curve (optional)
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true // Ensures the y-axis starts at 0
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

            });
        });
    </script>
</body>

</html>