<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge Mode Logs</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery (for AJAX requests) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="container my-5">
        <h2 class="text-center">Challenge Mode Logs</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Player Name</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody id="challengeModeTable">
                <!-- Data will be inserted here -->
            </tbody>
        </table>
    </div>

    <script>
        const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

        // Function to load Challenge Mode Logs
        function loadChallengeModeLogs() {
            $.get(API_BASE + '/api/challengemodelogs', function(result) {
                // Ensure the table body is cleared
                $('#challengeModeTable').empty();

                // Iterate through results and append rows to the table
                result.forEach(entry => {
                    $('#challengeModeTable').append(`
                        <tr>
                            <td>${entry.date}</td>
                            <td>${entry.player_name}</td>
                            <td>${entry.score}</td>
                        </tr>
                    `);
                });
            });
        }

        // Load data when the page is fully loaded
        $(document).ready(function() {
            loadChallengeModeLogs();
        });
    </script>

</body>

</html>