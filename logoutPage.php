<?php
/**
 * logoutPage.php
 *
 * Logs the user out of the session and redirects them to the homepage.
 */

// Include the topbar
require "./topbar.php";

// Destroy the session and clear the session variables
session_destroy();
$_SESSION = [];

// Redirect the user to the homepage
header("Location: index.php");
