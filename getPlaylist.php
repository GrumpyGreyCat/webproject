<?php

/**
 * This script returns a JSON response containing all playlists.
 * It uses the PDO extension to connect to the database and the
 * PlaylistController class to fetch the playlists.
 */

// Autoload the required class files
spl_autoload_register(function (string $className) {
    require "$className.php";
});

// Create a PDO instance to connect to the database
$db = new PDO('mysql:host=localhost;dbname=sonatina;charset=utf8', 'root', '');

// Set the response content type to JSON
header('Content-Type: application/json');

// Initialize an empty array to store the playlists
$playlists = [];

// Create an instance of the PlaylistController class
$playlistController = new PlaylistController($db);

// Fetch all playlists using the PlaylistController
$playlists = $playlistController->readAllPlaylist();

// Output the playlists as JSON
echo json_encode($playlists);

// Exit the script to prevent any further output
exit;
