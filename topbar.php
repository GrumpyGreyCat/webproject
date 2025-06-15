<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styleTopbar.css">
</head>


<?php
/**
 * Autoloads the required class files.
 *
 * @param string $className The name of the class to load.
 */
spl_autoload_register(function (string $className) {
    require "$className.php";
});

// Starts the session
session_start();

// Creates a new instance of the UserController class
$userController = new UserController();

/**
 * Checks if the user is connected.
 *
 * @return bool True if the user is connected, false otherwise.
 */
function isConnected(): bool
{
    return $_SESSION && $_SESSION["username"];
}
?>



<div class="topbar">
    <!-- The logo of the website -->
    <img src="./img/topbar_logo.png" id="logo" alt="logo_sonatina" width="64px" height="64px" onclick="window.location.href = 'index.php';">
    <!-- The name of the website -->
    <h1 class="sitename">Sonatina</h1>

    <!-- The button to toggle the menu -->
    <button class="burger" id="burger-btn" aria-label="Toggle menu">&#9776;</button>

    <!-- The navigation links -->
    <nav class="navigation" id="nav-links">
        <!-- Link to the homepage -->
        <a href="index.php" class="nav-link">Home</a>
        <!-- Link to the playlist page -->
        <a href="playlistPage.php" class="nav-link">Playlists</a>
        <!-- Link to the browse page -->
        <a href="browser.php" class="nav-link">Browse</a>
        <!-- If the user is connected, show the logout link -->
        <?php if (isConnected()) : ?>
            <a href="logoutPage.php" class="nav-link">Logout</a>
        <?php endif; ?>
        <!-- If the user is not connected, show the login link -->
        <?php if (!isConnected()) : ?>
            <a href="loginPage.php" class="nav-link">Login</a>
        <?php endif; ?>
    </nav>
</div>

<script>
    // Gets the burger button and the navigation links
    const burgerBtn = document.getElementById('burger-btn');
    const navLinks = document.getElementById('nav-links');

    // Adds an event listener to the burger button to toggle the menu
    burgerBtn.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });

    // Optional: Close menu when clicking outside
    document.addEventListener('click', (e) => {
        // If the click is not on the burger button or the navigation links, close the menu
        if (!burgerBtn.contains(e.target) && !navLinks.contains(e.target)) {
            navLinks.classList.remove('active');
        }
    });
</script>
