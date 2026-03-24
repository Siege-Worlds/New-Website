<?php
require_once('core/core.php');

if (!is_admin()) {
    header('Location: login.php');
    exit;
}

require_once('core/admin_tools.php');

$currentForm = $_GET['form'] ?? 'generalstats';
$displayName = $_SESSION['display_name'] ?? $_SESSION['username'] ?? 'Admin';

$navItems = [
    'generalstats' => 'General Stats',
    'challengemode' => 'Challenge Mode Logs',
    'exchangelogs' => 'Exchange Logs',
    'exchangevolume' => 'Exchange Volume',
    'itemcounts' => 'Item Counts',
    'itemprices' => 'Item Prices',
    'dailyusers' => 'Daily Users',
    'dailysignups' => 'Daily Signups',
    'characterapis' => 'Character Management',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — <?php echo htmlspecialchars($navItems[$currentForm] ?? 'Dashboard'); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:wght@400;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: "Open Sans", sans-serif;
            background: #1a1918;
            color: #bab1a8;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 240px;
            background: #111;
            border-right: 1px solid #3a3836;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .admin-sidebar-header {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #3a3836;
        }
        .admin-sidebar-header h2 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 1.6rem;
            color: #fff;
            margin: 0;
        }
        .admin-sidebar-header .admin-user {
            font-size: 0.8rem;
            color: #7a7572;
            margin-top: 4px;
        }
        .admin-sidebar nav {
            flex: 1;
            padding: 0.5rem 0;
        }
        .admin-sidebar nav a {
            display: block;
            padding: 0.65rem 1.25rem;
            color: #bab1a8;
            text-decoration: none;
            font-size: 0.9rem;
            border-left: 3px solid transparent;
            transition: all 0.15s;
        }
        .admin-sidebar nav a:hover {
            background: #2a2928;
            color: #fff;
        }
        .admin-sidebar nav a.active {
            background: rgba(106, 36, 250, 0.15);
            color: #fff;
            border-left-color: #6a24fa;
        }
        .admin-sidebar-footer {
            padding: 0.75rem 1.25rem;
            border-top: 1px solid #3a3836;
        }
        .admin-sidebar-footer a {
            color: #7a7572;
            text-decoration: none;
            font-size: 0.85rem;
        }
        .admin-sidebar-footer a:hover { color: #CD412B; }

        /* Main content */
        .admin-main {
            margin-left: 240px;
            flex: 1;
            padding: 2rem;
            min-height: 100vh;
        }
        .admin-main h2 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 2rem;
            color: #fff;
            margin-bottom: 0.25rem;
        }
        .admin-main > .page-desc {
            color: #7a7572;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        /* Shared admin component styles */
        .admin-card {
            background: #2a2928;
            border: 1px solid #3a3836;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .admin-card h3 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 1.4rem;
            color: #fff;
            margin: 0 0 1rem 0;
        }

        /* Stat items */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .stat-item {
            background: #1a1918;
            border: 1px solid #3a3836;
            border-radius: 8px;
            padding: 1.25rem;
            text-align: center;
        }
        .stat-item .stat-value {
            font-family: "Bebas Neue", sans-serif;
            font-size: 2rem;
            color: #fff;
        }
        .stat-item .stat-label {
            font-size: 0.8rem;
            color: #7a7572;
            margin-top: 4px;
        }

        /* Tables */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        .admin-table thead th {
            background: #111;
            color: #fff;
            font-family: "Bebas Neue", sans-serif;
            font-size: 1rem;
            font-weight: normal;
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 2px solid #6a24fa;
            position: sticky;
            top: 0;
        }
        .admin-table tbody td {
            padding: 0.6rem 1rem;
            border-bottom: 1px solid #3a3836;
            color: #bab1a8;
        }
        .admin-table tbody tr:hover {
            background: rgba(106, 36, 250, 0.08);
        }
        .admin-table img { border-radius: 4px; }

        /* Charts */
        .chart-container {
            background: #2a2928;
            border: 1px solid #3a3836;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .chart-container canvas {
            width: 100% !important;
            max-height: 400px;
        }

        /* Buttons */
        .admin-btn {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
        }
        .admin-btn-primary {
            background: #6a24fa;
            color: #fff;
        }
        .admin-btn-primary:hover { background: #7b3aff; }

        /* Inputs / selects */
        .admin-input, .admin-select {
            background: #1a1918;
            color: #bab1a8;
            border: 1px solid #3a3836;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            font-family: "Open Sans", sans-serif;
            width: 100%;
        }
        .admin-select { cursor: pointer; }
        .admin-input:focus, .admin-select:focus {
            outline: none;
            border-color: #6a24fa;
        }
        .admin-label {
            display: block;
            color: #bab1a8;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }

        /* Back to site link */
        .back-to-site {
            display: inline-block;
            margin-bottom: 1rem;
            color: #6a24fa;
            text-decoration: none;
            font-size: 0.85rem;
        }
        .back-to-site:hover { color: #7b3aff; }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar { width: 200px; }
            .admin-main { margin-left: 200px; padding: 1rem; }
        }
    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <div class="admin-sidebar-header">
            <h2>Siege Worlds</h2>
            <div class="admin-user"><?php echo htmlspecialchars($displayName); ?> — <?php echo htmlspecialchars($_SESSION['sso_role'] ?? 'admin'); ?></div>
        </div>
        <nav>
            <?php foreach ($navItems as $key => $label): ?>
            <a href="admin_dashboard.php?form=<?php echo $key; ?>" class="<?php echo $currentForm === $key ? 'active' : ''; ?>">
                <?php echo $label; ?>
            </a>
            <?php endforeach; ?>
        </nav>
        <div class="admin-sidebar-footer">
            <a href="index.php">&larr; Back to Site</a>
        </div>
    </aside>

    <main class="admin-main">
        <a href="index.php" class="back-to-site">&larr; Back to Site</a>
        <h2><?php echo htmlspecialchars($navItems[$currentForm] ?? 'Dashboard'); ?></h2>
        <p class="page-desc">Admin Dashboard</p>

        <?php
        switch ($currentForm) {
            case 'generalstats': general_stats_form(); break;
            case 'challengemode': challenge_mode_form(); break;
            case 'exchangelogs': exchange_logs_form(); break;
            case 'exchangevolume': exchange_volume_form(); break;
            case 'itemcounts': item_counts_form(); break;
            case 'itemprices': a_item_prices_form(); break;
            case 'dailyusers': daily_users_form(); break;
            case 'dailysignups': daily_signups_form(); break;
            case 'characterapis': character_apis_form(); break;
            default: general_stats_form(); break;
        }
        ?>
    </main>

</body>
</html>
