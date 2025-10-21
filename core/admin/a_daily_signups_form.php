<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Signups Chart</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="container my-5">
        <h2 class="text-center">Daily Signups</h2>
        <canvas id="signupChart" width="100vw" height="50vh"></canvas>
    </div>

    <div class="container my-5">
        <h2 class="text-center">New Users</h2>
        <table class="table table-striped" id="newUsersTable">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>IP Address</th>
                    <th>Signup Date</th>
                    <th>Total Minutes Played</th>
                    <th>Last Login</th>
                </tr>
            </thead>
            <tbody>
                <!-- New users data will be appended here -->
            </tbody>
        </table>
    </div>

    <!-- jQuery (required for AJAX calls) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Function to fetch daily signups from the Node.js API
        function loadDailySignups() {
            $.get('https://siegeworlds-320f73534b59.herokuapp.com/api/dailysignups', function(result) {
                // Assuming result is an array of objects with "date" and "signups" fields
                const labels = result.map(item => item.date);
                const data = result.map(item => item.signups);

                // Initialize the bar chart using Chart.js
                const ctx = document.getElementById('signupChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels, // Dates as X-axis labels
                        datasets: [{
                            label: 'Signups per Day',
                            data: data, // Number of signups
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true // Y-axis starts at 0
                            }
                        }
                    }
                });
            });
        }

        // Function to load new users and display them in the table
        function loadNewUsers() {
            $.get('https://siegeworlds-320f73534b59.herokuapp.com/api/newusers', function(result) {
                // Reverse the result array to display newest users first
                const reversedResult = result.reverse();
                const tableBody = $('#newUsersTable tbody');
                tableBody.empty(); // Clear any existing data

                reversedResult.forEach(user => {
                    const row = `
                        <tr>
                            <td>${user.username}</td>
                            <td>${user.ip.split(':')[0].replace('/', '')}</td> <!-- Remove / and port -->
                            <td>${user.date}</td>
                            <td>${user.total_minutes_played}</td>
                            <td>${user.last_login_date}</td>
                        </tr>
                    `;
                    tableBody.append(row);
                });
            });
        }

        // Load data when the page is fully loaded
        $(document).ready(function() {
            loadDailySignups();
            loadNewUsers();
        });
    </script>

</body>

</html>