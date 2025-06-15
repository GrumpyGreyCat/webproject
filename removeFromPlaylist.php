<?php

/**
 * Handles removing a song from a playlist via a POST request.
 *
 * Expected POST data:
 * - playlist_id: The ID of the playlist to remove the song from.
 * - song_id: The ID of the song to remove.
 *
 * Returns a JSON response indicating success or failure.
 */

header('Content-Type: application/json');
require_once 'PlaylistController.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$playlistController = new PlaylistController();

// Collect inputs
$playlistId = $data['playlist_id'] ?? null;
$songId = $data['song_id'] ?? null;

if (!$songId) {
    echo json_encode(['success' => false, 'message' => 'No song ID provided.']);
    exit;
}

// Remove from playlist
if ($playlistId && $songId) {
    $result = $playlistController->removeSongFromPlaylist($playlistId, $songId);

    if (!$result) {
        echo json_encode(['success' => true, 'message' => 'Song removed from playlist successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove song from playlist.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing playlist ID or song ID.']);
}

