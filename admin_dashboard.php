<?php
require_once('core/core.php');

// Check if admin is logged in (SSO role or legacy hardcoded login)
if (!is_admin()) {
    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
            background-color: #343a40;
            color: #fff;
        }

        .sidebar a {
            color: #ffffff;
            padding: 15px;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>

    <?php
    require_once('core/admin_tools.php');
    ?>



</head>

<body>

    <div class="sidebar">
        <h4 class="text-center py-3">Admin Panel</h4>
        <a href="admin_dashboard.php?form=generalstats">General Stats</a>
        <a href="admin_dashboard.php?form=challengemode">Challenge Mode Logs</a>
        <a href="admin_dashboard.php?form=exchangelogs">Exchange Logs</a>
        <a href="admin_dashboard.php?form=exchangevolume">Exchange Volume</a>
        <a href="admin_dashboard.php?form=itemcounts">Item Counts</a>
        <a href="admin_dashboard.php?form=itemprices">Item Prices</a>
        <a href="admin_dashboard.php?form=dailyusers">Daily Users</a>
        <a href="admin_dashboard.php?form=dailysignups">Daily Signups</a>
        <a href="admin_dashboard.php?form=characterapis">Character APIs</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <div class="content">
        <h2>Welcome to the Admin Dashboard</h2>
        <p>Select options from the sidebar to manage various sections of the website.</p>

        <?php

        //if form is not set
        if (!isset($_GET['form'])) {
            // Include the add game form
            general_stats_form();
        }

        if (isset($_GET['form']) && $_GET['form'] === 'generalstats') {
            // Include the add game form
            general_stats_form();
        }
        if (isset($_GET['form']) && $_GET['form'] === 'challengemode') {
            // Include the add game form
            challenge_mode_form();
        }
        if (isset($_GET['form']) && $_GET['form'] === 'exchangelogs') {
            // Include the add game form
            exchange_logs_form();
        }

        if (isset($_GET['form']) && $_GET['form'] === 'exchangevolume') {
            // Include the add game form
            exchange_volume_form();
        }

        if (isset($_GET['form']) && $_GET['form'] === 'itemcounts') {
            // Include the add game form
            item_counts_form();
        }

        if (isset($_GET['form']) && $_GET['form'] === 'itemprices') {
            // Include the add game form
            a_item_prices_form();
        }

        if (isset($_GET['form']) && $_GET['form'] === 'dailyusers') {
            // Include the add game form
            daily_users_form();
        }

        if (isset($_GET['form']) && $_GET['form'] === 'dailysignups') {
            // Include the add game form
            daily_signups_form();
        }

        if (isset($_GET['form']) && $_GET['form'] === 'characterapis') {
            character_apis_form();
        }

        ?>
    </div>

</body>

</html>