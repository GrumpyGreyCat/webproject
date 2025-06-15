<?php
/**
 * Register page
 *
 * Handles user registration. This page expects the following POST parameters:
 *
 * - username: The username to create
 * - email: The email address to associate with the account
 * - password: The password for the account
 * - confirm-password: The password again, for confirmation
 *
 * If the registration is successful, the user is redirected to the login page.
 */

require "./topbar.php";

// Handle POST
if ($_POST) {
    // Check that the passwords match
    if ($_POST["password"] !== $_POST["confirm-password"]) {
        header("Location: registerPage.php?error=password_mismatch");
        exit;
    }

    // Check that the username is valid
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $_POST['username'])) {
        header("Location: registerPage.php?error=invalid_username");
        exit;
    }

    // Remove the confirmation password
    unset($_POST["confirm-password"]);

    // Check that the username and email are not in use
    $user  = $userController->getUserByUsername($_POST["username"]);
    $email = $userController->getUserByEmail($_POST["email"]);

    if ($user) {
        header("Location: registerPage.php?error=username_taken");
        exit;
    }
    if ($email) {
        header("Location: registerPage.php?error=email_taken");
        exit;
    }

    // All good: hash & create
    $_POST["password_hash"] = password_hash($_POST["password"], PASSWORD_DEFAULT);
    unset($_POST["password"]);
    $newUser = new User($_POST);
    $userController->createUser($newUser);
    header("Location: loginPage.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./styleLog.css">
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <form method="post">

                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Your username" required>

                <label for="email">E‑mail</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Your email" required>

                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Your password" required>

                <label for="confirm-password">Confirm your password</label>
                <input type="password" class="form-control" name="confirm-password" id="confirm-password" placeholder="Confirm your password" required>

                <input type="submit" value="Create">

            </form>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">
                    <?php
                    // Whitelist known error keys; default case is escaped
                    switch ($_GET['error']) {
                        case 'email_taken':
                            echo "Email is already in use.";
                            break;
                        case 'username_taken':
                            echo "Username is already taken.";
                            break;
                        case 'password_mismatch':
                            echo "Passwords do not match.";
                            break;
                        case 'invalid_username':
                            echo "Username must be 3–20 characters (letters, numbers, underscore).";
                            break;
                        default:
                            echo htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8');
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>

