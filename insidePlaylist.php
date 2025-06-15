<?php
require "./topbar.php";

// Validate and retrieve the playlist ID from the URL
if (!isset($_GET['playlist_id']) || !is_numeric($_GET['playlist_id'])) {
    die("Invalid playlist ID.");
}

$playlistId = intval($_GET['playlist_id']);
$playlistController = new PlaylistController();
$playlist = $playlistController->getPlaylistById($playlistId);

// Check if the playlist exists
if (!$playlist) {
    die("Playlist not found.");
}

// Fetch songs associated with the playlist
$songs = $playlistController->getSongs($playlist);
foreach ($songs as $index => $song) {
    $songs[$index]['src'] = $playlistController->getSongSrcBySongId($song['song_id']);
    $songs[$index]['name'] = substr($playlistController->getSongNameBySongId($song['song_id']), 0, -4);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlist - <?= htmlspecialchars($playlist->getName()) ?></title>
    <link rel="stylesheet" href="./styleInside.css">
</head>

<body>
    <div class="wrapper">
        <div class="data">
            <h1><?= htmlspecialchars($playlist->getName()) ?></h1>
            <p>Created At : <?= htmlspecialchars($playlist->getCreated_at() ?? '') ?></p>
            <?php if (!in_array($playlist->getName(), [
                "Sonatina Playlist 1",
                "Sonatina Playlist 2",
                "Sonatina Playlist 3",
                "Sonatina Playlist 4"
            ])) : ?>
                <!-- Delete playlist form -->
                <form method="post" action="deletePlaylist.php" onsubmit="return confirm('Are you sure you want to delete this playlist?');">
                    <input type="hidden" name="playlist_id" value="<?= $playlistId ?>">
                    <button type="submit" class="delete-playlist-btn">Delete Playlist</button>
                </form>
            <?php endif; ?>
        </div>

        <div class="list">
            <button id="play-playlist-btn">Play Playlist</button>
            <h2>Tracks</h2>
            <div class="track-list">
                <?php foreach ($songs as $index => $song) : ?>
                    <div class="track">
                        <p><?= htmlspecialchars($song['name']) ?></p>
                        <audio class="playlist-audio"
                            data-index="<?= $index ?>"
                            src="<?= htmlspecialchars($song['src']) ?>"></audio>
                        <?php if (!in_array($playlist->getName(), [
                            "Sonatina Playlist 1",
                            "Sonatina Playlist 2",
                            "Sonatina Playlist 3",
                            "Sonatina Playlist 4"
                        ])) : ?>
                            <!-- Remove song button -->
                            <button class="remove-song-btn"
                                data-song-id="<?= htmlspecialchars($song['song_id']) ?>"
                                data-playlist-id="<?= htmlspecialchars($playlistId) ?>">
                                Remove the song
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div id="music-controls">
        <div id="current-song-title">No song playing</div>
        <div class="controls">
            <button id="prev-btn">⏮</button>
            <button id="play-pause-btn">▶</button>
            <button id="next-btn">⏭</button>
            <input type="range" id="volume-control" min="0" max="1" step="0.01" value="1">
        </div>
        <audio id="audio-player"></audio>
    </div>

    <script>
        // Playlist data and audio controls
        const playlist = <?= json_encode($songs) ?>;
        const audioPlayer = document.getElementById('audio-player');
        const playButton = document.getElementById('play-playlist-btn');
        const playPauseBtn = document.getElementById('play-pause-btn');
        const nextBtn = document.getElementById('next-btn');
        const prevBtn = document.getElementById('prev-btn');
        const volumeControl = document.getElementById('volume-control');
        const songTitle = document.getElementById('current-song-title');

        let currentIndex = 0;
        let isPlaying = false;

        /**
         * Plays a song from the playlist at the given index.
         * @param {number} index - The index of the song to play.
         */
        function playSong(index) {
            if (index < 0 || index >= playlist.length) return;

            const song = playlist[index];
            audioPlayer.src = song.src;
            audioPlayer.play()
                .then(() => {
                    songTitle.textContent = song.name || `Track ${index + 1}`;
                    currentIndex = index;
                    isPlaying = true;
                    playPauseBtn.textContent = "⏸";
                })
                .catch(error => {
                    console.error("Playback failed:", error);
                });
        }

        /**
         * Toggles play/pause state of the audio player.
         */
        function togglePlayPause() {
            if (!playlist.length) return;

            if (isPlaying) {
                audioPlayer.pause();
                isPlaying = false;
                playPauseBtn.textContent = "▶";
            } else {
                if (!audioPlayer.src) {
                    playSong(currentIndex);
                } else {
                    audioPlayer.play()
                        .then(() => {
                            isPlaying = true;
                            playPauseBtn.textContent = "⏸";
                        });
                }
            }
        }

        // Event listeners for audio controls
        playPauseBtn.addEventListener('click', togglePlayPause);
        nextBtn.addEventListener('click', () => playSong((currentIndex + 1) % playlist.length));
        prevBtn.addEventListener('click', () => playSong((currentIndex - 1 + playlist.length) % playlist.length));
        volumeControl.addEventListener('input', () => audioPlayer.volume = volumeControl.value);
        audioPlayer.addEventListener('ended', () => playSong(currentIndex + 1));
        playButton.addEventListener('click', () => playSong(0));

        // Add remove song functionality
        document.querySelectorAll('.remove-song-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const songId = this.getAttribute('data-song-id');
                const playlistId = this.getAttribute('data-playlist-id');

                // Send AJAX request to remove song using POST with proper body
                fetch('removeFromPlaylist.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        playlist_id: playlistId,
                        song_id: songId
                    })
                })
                location.href = location.href;
            });
        });
    </script>
</body>

</html>
