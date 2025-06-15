<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <title>Login Page</title>
    <link rel="stylesheet" href="./styleLog.css" />
</head>

<body>
    <?php
    /**
     * Includes the topbar
     */
    require "./topbar.php";

    /**
     * Handles the POST request
     */
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        /**
         * Gets the username and password from the form
         */
        $username = $_POST["username"] ?? '';
        $password = $_POST["password"] ?? '';

        /**
         * Checks if the fields are not empty
         */
        if (!empty($username) && !empty($password)) {
            /**
             * Gets the user from the database
             */
            $user = $userController->getUserByUsername($username);

            /**
             * Checks if the user exists and if the password is correct
             */
            if ($user && password_verify($password, $user->getPassword_hash())) {
                /**
                 * Starts the session
                 */
                $_SESSION["username"] = $user->getUsername();
                header("Location: index.php");
                exit;
            } else {
                /**
                 * Sets the error message
                 */
                $errorMessage = "Invalid username or password.";
            }
        } else {
            /**
             * Sets the error message
             */
            $errorMessage = "Please fill in all fields.";
        }
    }
    ?>

    <div class="wrapper">
        <div class="container">
            <form method="post" action="loginPage.php">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Your username" />

                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Your password" required />

                <input type="submit" value="Sign In" />
            </form>

            <div id="g_id_onload"
                data-client_id="981333410353-8kcort6vu35goatsm071erq9ipv83hja.apps.googleusercontent.com"
                data-callback="handleCredentialResponse"
                data-auto_prompt="false"
                data-ux_mode="popup">
            </div>

            <div class="g_id_signin" data-shape="circle"></div>
            <style type="text/css">
                .g_id_signin {
                    margin-left: 25px;
                    margin-top: 10px;
                }
            </style>

            <button onclick="window.location.href = 'registerPage.php';">Create an account</button>
        </div>
    </div>

    <script>
        /**
         * Handles the response from Google
         * @param {Object} response
         */
        function handleCredentialResponse(response) {
            console.log("Encoded JWT ID token: " + response.credential);

            fetch('handle-google-signin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id_token: response.credential
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'index.php';
                    } else {
                        alert('Google login failed: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(() => alert('Network error'));
        }
    </script>
</body>

</html>
