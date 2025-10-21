<html>



<head>
    <title>Admin</title>

    <?php
    ini_set('display_errors', 1);
    require_once('./core/core.php');
    head();
    ?>

</head>

<body>
    <input class="" id="user1" style="width:400px;" type="text" placeholder="Username" name="username"> <br>
    <input class="" id="oldpass" style="width:400px;" type="password" placeholder="Old password" name="password"><br>
    <input class="" id="newpass" style="width:400px;" type="password" placeholder="New Password" name="email"><br>


    <a id="signup-button" class="button is-primary is-medium">Reset Password</a>


    <script>
        document.getElementById('signup-button').addEventListener('click', function() {
            signupbutton();
        });

        async function signupbutton() {


            const result = await (await fetch(
                'https://siegeworlds-320f73534b59.herokuapp.com/api/pwreset', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        username: $('#user1').val(),
                        password1: $('#oldpass').val(),
                        password2: $('#newpass').val(),
                    })
                })).json()


            if (result == false) {
                alert("Failed. try again.")
            } else {
                alert("Password has been reset.")
            }

        }
    </script>

</body>

</html>