<?php
/**
 * Handles deleting a playlist via a POST request.
 *
 * Expected POST data:
 * - playlist_id: The ID of the playlist to delete.
 *
 * Redirects to playlistPage.php after deletion.
 */

require_once 'PlaylistController.php';

$playlistController = new PlaylistController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['playlist_id'])) {
    // Get the playlist ID from the POST request
    $playlistId = intval($_POST['playlist_id']);

    // Get the playlist object from the database
    $playlist = $playlistController->getPlaylistById($playlistId);

    // Delete the playlist
    $playlistController->deletePlaylist($playlist);

    // Redirect to the playlist page
    header('Location: playlistPage.php');
}

