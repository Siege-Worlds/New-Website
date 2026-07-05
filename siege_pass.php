<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siege Pass</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            margin: 0;
            background: #0f0e0d;
            color: #e4dad1;
            font-family: "Roboto", Arial, sans-serif;
        }

        .siege-pass-page {
            min-height: 100vh;
            padding: 3rem 1.5rem;
            background:
                linear-gradient(90deg, rgba(10, 10, 10, 0.95) 0%, rgba(10, 10, 10, 0.7) 45%, rgba(10, 10, 10, 0.95) 100%),
                url('img/filmstrip_screenshots_1.webp') center/cover no-repeat;
        }

        .siege-pass-shell {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(21, 20, 19, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
            overflow: hidden;
        }

        .siege-pass-hero {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 2rem;
            padding: 2.5rem;
            align-items: center;
        }

        .siege-pass-hero img {
            width: 100%;
            display: block;
            border: 1px solid rgba(255, 255, 255, 0.15);
            object-fit: cover;
            max-height: 320px;
        }

        .siege-pass-badge {
            display: inline-block;
            padding: .4rem .7rem;
            background: #6a24fa;
            color: #fff;
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .siege-pass-title {
            font-family: "Bebas Neue", sans-serif;
            font-size: 3rem;
            line-height: 1;
            color: #fff;
            margin-bottom: 1rem;
        }

        .siege-pass-copy {
            color: #c9bfb4;
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .siege-pass-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            margin-top: 1rem;
        }

        .button.square {
            border-radius: 0;
            padding: .8rem 1rem;
            min-width: 180px;
            justify-content: center;
        }

        .subscribe-form-card {
            margin-top: 1.25rem;
            padding: 1rem 1.1rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 420px;
        }

        .subscribe-form-card label {
            display: block;
            margin-bottom: .45rem;
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #f4ece5;
        }

        .subscribe-form-card input {
            width: 100%;
            box-sizing: border-box;
            padding: .8rem .9rem;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #fff;
            margin-bottom: .75rem;
        }

        .subscribe-form-card button {
            width: 100%;
            border: 0;
            padding: .85rem 1rem;
            background: linear-gradient(135deg, #6a24fa, #8b5cff);
            color: #fff;
            cursor: pointer;
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-size: 1rem;
        }

        .subscribe-status {
            margin-top: .7rem;
            min-height: 1.2rem;
            color: #d8c7af;
            font-size: .95rem;
        }

        .siege-pass-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            height: 100%;
        }

        .siege-pass-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            padding: 0 2.5rem 2.5rem;
        }

        .siege-pass-list {
            display: flex;
            flex-direction: column;
            gap: .75rem;
            list-style: none;
            padding: 0;
            margin: 1.25rem 0 0;
            text-align: left;
        }

        .siege-pass-list li {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .95rem 1rem;
            color: #e4dad1;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .siege-pass-list li::before {
            content: "✦";
            color: #6a24fa;
            font-size: 1rem;
        }

        .siege-pass-footer {
            padding: 0 2.5rem 2.5rem;
        }

        .siege-pass-footer .card {
            background: linear-gradient(135deg, rgba(106, 36, 250, 0.22), rgba(255, 255, 255, 0.03));
            border: 1px solid rgba(106, 36, 250, 0.35);
            padding: 1.5rem;
            text-align: center;
        }

        .home-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            color: #e4dad1;
            text-decoration: none;
            margin-bottom: 1.25rem;
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .home-link:hover {
            color: #fff;
        }

        @media (max-width: 900px) {

            .siege-pass-hero,
            .siege-pass-grid {
                grid-template-columns: 1fr;
            }

            .siege-pass-list {
                grid-template-columns: 1fr;
            }

            .siege-pass-hero {
                padding: 1.5rem;
            }

            .siege-pass-grid,
            .siege-pass-footer {
                padding: 0 1.5rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="siege-pass-page">
        <div class="siege-pass-shell">
            <div class="siege-pass-hero">
                <div>
                    <a href="index.php" class="home-link">← Back to Home</a><br>
                    <span class="siege-pass-badge">Monthly Subscription</span>
                    <h1 class="siege-pass-title">Unlock the Siege Pass</h1>
                    <p class="siege-pass-copy">
                        The Siege Pass gives dedicated players stronger rewards, better trading value, and access to premium progression perks every month.
                    </p>
                    <div class="subscribe-form-card">
                        <form id="subscribe-form" novalidate>
                            <label for="username">Enter your username</label>
                            <input id="username" name="username" type="text" placeholder="Username" autocomplete="username" required>
                            <button type="submit">Subscribe for £2.99/month</button>
                        </form>
                        <div class="subscribe-status" id="subscribe-status" aria-live="polite"></div>
                    </div>
                </div>
                <img src="img/character.webp" alt="Siege Worlds character art">
            </div>

            <script>
                const subscribeForm = document.getElementById('subscribe-form');
                const usernameInput = document.getElementById('username');
                const subscribeStatus = document.getElementById('subscribe-status');

                if (subscribeForm) {
                    subscribeForm.addEventListener('submit', async (event) => {
                        event.preventDefault();

                        const username = usernameInput.value.trim();
                        if (!username) {
                            alert('Please enter your username.');
                            return;
                        }

                        subscribeStatus.textContent = 'Checking eligibility...';

                        try {
                            const response = await fetch(`/api/can_user_subscribe/${encodeURIComponent(username)}`, {
                                method: 'GET',
                                headers: {
                                    Accept: 'application/json',
                                },
                            });

                            if (response.redirected && response.url) {
                                window.location.assign(response.url);
                                return;
                            }

                            const data = await response.json().catch(() => ({}));
                            if (data.message) {
                                alert(data.message);
                                subscribeStatus.textContent = '';
                                return;
                            }

                            alert('Unable to process subscription request.');
                            subscribeStatus.textContent = '';
                        } catch (error) {
                            console.error(error);
                            alert('Unable to process subscription request right now.');
                            subscribeStatus.textContent = '';
                        }
                    });
                }
            </script>

            <div class="siege-pass-grid" id="benefits">
                <div class="siege-pass-card">
                    <h3 class="section-title">Boosted rewards</h3>
                    <p class="siege-pass-copy">Enjoy 50X divi drops, stronger fountain rewards, and a faster path to meaningful progression.</p>
                    <ul class="siege-pass-list">
                        <li>50X divi drops</li>
                        <li>Fountain rewards</li>
                    </ul>
                </div>
                <div class="siege-pass-card">
                    <h3 class="section-title">Smarter trading</h3>
                    <p class="siege-pass-copy">Lower trading post fees so your earned resources go further and your economy stays more efficient.</p>
                    <ul class="siege-pass-list">
                        <li>Reduced trading post fees</li>
                        <li>Better value on every trade</li>
                    </ul>
                </div>
                <div class="siege-pass-card">
                    <h3 class="section-title">Premium progression</h3>
                    <p class="siege-pass-copy">Access powerful progression systems and future updates that keep the experience growing over time.</p>
                    <ul class="siege-pass-list">
                        <li>Skills</li>
                        <li>Forging</li>
                        <li>Weapons</li>
                        <li>Future content and updates</li>
                    </ul>
                </div>
            </div>


        </div>
    </div>
</body>

</html>