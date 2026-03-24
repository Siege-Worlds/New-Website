<?php
require_once('core/core.php');

// If already admin, go to dashboard
if (is_admin()) {
    header('Location: admin_dashboard.php');
    exit;
}

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
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Access Denied</title>
        <style>
            body { background:#1a1918; color:#bab1a8; font-family:"Open Sans",sans-serif; display:flex; align-items:center; justify-content:center; min-height:100vh; margin:0; }
            .box { text-align:center; }
            .box h1 { font-size:2rem; color:#fff; margin-bottom:0.5rem; }
            .box a { color:#6a24fa; }
        </style>
    </head>
    <body>
        <div class="box">
            <h1>Access Denied</h1>
            <p>Your account does not have admin privileges.</p>
            <p><a href="index.php">&larr; Back to Site</a></p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Not logged in — redirect to SSO
header('Location: login.php');
exit;
