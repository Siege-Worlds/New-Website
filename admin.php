<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Admin Login</title>

    <?php
    session_start();

    // Dummy credentials for example (use a database in production)
    $admin_username = 'jake';
    $admin_password = 'rabiddog';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check if the credentials are correct
        if ($username === $admin_username && $password === $admin_password) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            header('Location: admin_dashboard.php'); // Redirect to the admin dashboard
            exit;
        } else {
            echo "<script>alert('Invalid username or password');</script>";
        }
    }
    ?>


</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h3 class="text-center">Admin Login</h3>
                <form action="admin.php" method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>