<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siege Worlds Referral Program</title>

    <!-- Custom CSS -->
    <style>
        /* General Styling */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
        }

        /* Container Styling */
        .container {
            width: 90%;
            max-width: 600px;
            padding: 20px;
            margin-top: 40px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Heading */
        h2 {
            font-size: 1.8em;
            color: #4a90e2;
            margin-bottom: 15px;
        }

        p {
            font-size: 1em;
            color: #555;
            margin-bottom: 25px;
        }

        /* Input and Button Styles */
        .form-group {
            margin-bottom: 15px;
            text-align: center;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .btn-primary,
        .btn-secondary {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            font-size: 1em;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .btn-primary {
            background-color: #4a90e2;
        }

        .btn-primary:hover {
            background-color: #3a7bc1;
        }

        .btn-secondary {
            background-color: #6c757d;
            width: auto;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        /* Referral Link Styling */
        #referralLinkContainer {
            margin-top: 20px;
            display: none;
        }

        #referralLink {
            font-size: 0.9em;
            padding: 8px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Refer a Friend and Earn Rewards</h2>
        <p>Enter your username below to generate your referral link. Share it with friends to earn an airdrop of our future <strong>$HORDE</strong> token for each friend who connects their Telegram account and kills 200+ monsters!</p>

        <div class="form-group">
            <input type="text" id="username" placeholder="Enter your username">
        </div>

        <button class="btn-primary" onclick="generateReferralLink()">Generate Referral Link</button>

        <div id="referralLinkContainer">
            <p>Your referral link:</p>
            <input type="text" id="referralLink" readonly>
            <button class="btn-secondary" onclick="copyToClipboard()">Copy to Clipboard</button>
        </div>
    </div>

    <script>
        function generateReferralLink() {
            const username = document.getElementById("username").value.trim();

            if (username === "") {
                alert("Please enter a valid username.");
                return;
            }

            const referralLink = `https://www.siegeworlds.com/signup.php?ref=${encodeURIComponent(username)}`;
            document.getElementById("referralLink").value = referralLink;
            document.getElementById("referralLinkContainer").style.display = "block";
        }

        function copyToClipboard() {
            const referralLinkField = document.getElementById("referralLink");
            referralLinkField.select();
            referralLinkField.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand("copy");
            alert("Referral link copied to clipboard!");
        }
    </script>
</body>

</html>