<?php
require_once 'core/core.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Siege Worlds</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            margin: 0;
            background: #0f0e0d;
            color: #e4dad1;
            font-family: "Roboto", Arial, sans-serif;
        }

        .login-page {
            min-height: 100vh;
            padding: 3rem 1.5rem;
            background:
                linear-gradient(90deg, rgba(10, 10, 10, 0.95) 0%, rgba(10, 10, 10, 0.72) 45%, rgba(10, 10, 10, 0.95) 100%),
                url('img/filmstrip_screenshots_1.webp') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-shell {
            width: min(1120px, 100%);
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            background: rgba(21, 20, 19, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
            overflow: hidden;
        }

        .login-visual {
            position: relative;
            min-height: 520px;
            background: linear-gradient(135deg, rgba(106, 36, 250, 0.2), rgba(255,255,255,0.03));
        }

        .login-visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: saturate(1.1) contrast(1.02);
        }

        .login-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.45) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1.5rem;
        }

        .login-badge {
            display: inline-block;
            width: fit-content;
            padding: .4rem .7rem;
            background: #6a24fa;
            color: #fff;
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: .75rem;
        }

        .login-visual h2 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 2rem;
            margin: 0 0 .35rem;
            color: #fff;
        }

        .login-visual p {
            margin: 0;
            color: #e9dfd6;
            max-width: 300px;
            line-height: 1.6;
        }

        .login-form-panel {
            padding: 2.25rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .home-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            color: #e4dad1;
            text-decoration: none;
            margin-bottom: 1.15rem;
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .home-link:hover {
            color: #fff;
        }

        .login-title {
            font-family: "Bebas Neue", sans-serif;
            font-size: 2.4rem;
            color: #fff;
            margin: 0 0 .6rem;
        }

        .login-copy {
            color: #c9bfb4;
            line-height: 1.7;
            margin-bottom: 1.4rem;
        }

        .login-form {
            display: grid;
            gap: .9rem;
        }

        .login-form label {
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #f4ece5;
        }

        .login-form input {
            width: 100%;
            box-sizing: border-box;
            padding: .85rem .95rem;
            background: rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .login-form button {
            border: 0;
            padding: .9rem 1rem;
            background: linear-gradient(135deg, #6a24fa, #8b5cff);
            color: #fff;
            cursor: pointer;
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-size: 1rem;
        }

        .login-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: .55rem;
            font-size: .95rem;
        }

        .login-actions a {
            color: #d4c2b4;
            text-decoration: none;
        }

        .login-actions a:hover {
            color: #fff;
        }

        @media (max-width: 900px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .login-visual {
                min-height: 280px;
            }

            .login-form-panel {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-shell">
            <div class="login-visual">
                <img src="img/character.webp" alt="Siege Worlds character art">
                <div class="login-overlay">
                    <span class="login-badge">Secure access</span>
                    <h2>Enter the battlefield</h2>
                    <p>Log in to manage your account, view your collections, and continue your journey.</p>
                </div>
            </div>

            <div class="login-form-panel">
                <a href="index.php" class="home-link">← Back to Home</a>
                <h1 class="login-title">Welcome back</h1>
                <p class="login-copy">Sign in to your Siege Worlds account and continue your adventure.</p>

                <form class="login-form" action="login.php" method="post">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" placeholder="Enter your username" required>

                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Enter your password" required>

                    <button type="submit">Login</button>
                </form>

                <div class="login-actions">
                    <a href="signup.php">Create account</a>
                    <a href="pwreset.php">Forgot password?</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
