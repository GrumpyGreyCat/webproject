<?php

/**
 * Handles adding a song to a playlist via a POST request.
 *
 * Expected POST data:
 * - playlist_id: The ID of the playlist to add the song to.
 * - song_id: The name of the song to add (without extension).
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
$songName = $data['song_id'] ?? null; // assuming song_name is passed in 'song_id' key

if (!$songName) {
    echo json_encode(['success' => false, 'message' => 'No song name provided.']);
    exit;
}

// Append ".mp3" only if needed
if (!str_ends_with($songName, '.mp3')) {
    $songName .= '.mp3';
}

// Get song ID from DB
$songIdResult = $playlistController->getSongIdBySongName($songName);

if ($songIdResult === null) {
    echo json_encode(['success' => false, 'message' => 'Song not found in database.']);
    exit;
}

// Add to playlist
if ($playlistId && $songIdResult) {
    $result = $playlistController->addSongToPlaylist($playlistId, $songIdResult);

    if (!$result) {
        echo json_encode(['success' => true, 'message' => 'Song added to playlist successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add song to playlist.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing playlist ID or song ID.']);
}

