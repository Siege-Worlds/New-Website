<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exchange Volume Chart</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .container {
            margin-top: 50px;
        }

        #volumeChart {
            width: 100vw;
            height: 50vh;
            margin: auto;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="text-center">Active users</h2>
        <canvas id="volumeChart"></canvas>
        <div class="text-center mt-3">
            <button class="btn btn-primary" id="toggleView">Switch to Monthly View</button>
        </div>
    </div>

    <script>
        let currentView = 'daily'; // Initialize with daily view

        // Function to fetch data from the API
        async function fetchVolumeData() {
            try {
                const response = await fetch('https://siegeworlds-320f73534b59.herokuapp.com/api/userdata');
                let data = await response.json();

                // Fill in missing dates
                //data = fillMissingDates(data, '2024-10-01', '2024-10-14'); // Adjust date range as necessary

                // Separate the data into dates and volumes
                const dates = data.map(entry => entry.date);
                const volumes = data.map(entry => entry.activeUsers);

                // Render the initial chart
                renderChart(dates, volumes, 'Total Volume per Day');
            } catch (error) {
                console.error('Error fetching volume data:', error);
            }
        }

        // Function to fill missing dates
        function fillMissingDates(data, startDate, endDate) {
            const filledData = [];
            let currentDate = new Date(startDate);
            const lastDate = new Date(endDate);
            const dataMap = {};

            // Map existing data by date for quick lookup
            data.forEach(entry => {
                dataMap[entry.date] = entry.totalVolume;
            });

            while (currentDate <= lastDate) {
                const formattedDate = currentDate.toISOString().split('T')[0];
                filledData.push({
                    date: formattedDate,
                    totalVolume: dataMap[formattedDate] || 0 // Default to 0 if date not found
                });
                currentDate.setDate(currentDate.getDate() + 1);
            }

            return filledData;
        }

        // Function to render the chart
        function renderChart(dates, volumes, label) {
            const ctx = document.getElementById('volumeChart').getContext('2d');
            if (window.myChart) window.myChart.destroy(); // Destroy previous chart instance if exists
            window.myChart = new Chart(ctx, {
                type: 'bar', // Bar chart
                data: {
                    labels: dates,
                    datasets: [{
                        label: label,
                        data: volumes,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Function to fetch and calculate monthly volume data
        async function fetchMonthlyVolume() {
            const response = await fetch('https://siegeworlds-320f73534b59.herokuapp.com/api/userdata');
            let data = await response.json();

            const monthlyVolume = {};
            data.forEach(entry => {
                const month = entry.date.slice(0, 7); // Get "YYYY-MM" part of the date
                if (!monthlyVolume[month]) {
                    monthlyVolume[month] = 0;
                }
                monthlyVolume[month] += entry.activeUsers;
            });

            // Prepare data for the chart
            const months = Object.keys(monthlyVolume);
            const volumes = Object.values(monthlyVolume);
            renderChart(months, volumes, 'Total Volume per Month');
        }

        // Event listener to toggle between daily and monthly view
        document.getElementById('toggleView').addEventListener('click', function() {
            if (currentView === 'daily') {
                fetchMonthlyVolume(); // Switch to monthly view
                currentView = 'monthly';
                this.innerText = 'Switch to Daily View';
            } else {
                fetchVolumeData(); // Switch to daily view
                currentView = 'daily';
                this.innerText = 'Switch to Monthly View';
            }
        });

        // Fetch the initial daily volume data when the page loads
        window.onload = fetchVolumeData;
    </script>

</body>

</html>