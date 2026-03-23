<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Signing in...</title>
    <style>
        body {
            background: #1a1918;
            color: #bab1a8;
            font-family: "Open Sans", sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .status {
            text-align: center;
            font-size: 1.2rem;
        }
        .status a {
            color: #6a24fa;
        }
    </style>
</head>
<body>
<div class="status" id="status">Signing in...</div>
<script>
    // Extract tokens from hash fragment (SSO puts them there, not in query params)
    var hash = window.location.hash.substring(1);
    var params = new URLSearchParams(hash);
    var accessToken = params.get('access_token');
    var refreshToken = params.get('refresh_token');

    if (accessToken) {
        fetch('/auth/verify.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                access_token: accessToken,
                refresh_token: refreshToken
            })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                window.location.href = '/';
            } else {
                document.getElementById('status').innerHTML =
                    'Login failed: ' + (data.error || 'Unknown error') +
                    '<br><a href="/">Go home</a>';
            }
        })
        .catch(function() {
            document.getElementById('status').innerHTML =
                'Connection error. <a href="/">Go home</a>';
        });
    } else {
        document.getElementById('status').innerHTML =
            'No token received. <a href="/">Go home</a>';
    }
</script>
</body>
</html>
