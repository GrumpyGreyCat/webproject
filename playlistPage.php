<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./stylePlaylist.css">
    <title>Playlists</title>
</head>

<body>
    <?php
    // Include the top navigation bar
    require "./topbar.php";

    /**
     * If the user is not connected, display a message to connect
     */
    if (!isConnected()) {
    ?>
        <h2>Connect to create your playlist</h2>
    <?php } else { ?>
        <div class='my-playlists-container'>
            <h2>My playlists</h2>
            <div id='my-playlists'></div>
            <button onclick="window.location.href = 'createPlaylistPage.php';">Create a playlist</button>
        </div>
        <div class='popular-playlists-container'>
            <h2>Popular playlists</h2>
            <div id='popular-playlists'></div>
        <?php } ?>
        <!-- Include the JavaScript file that handles the playlists -->
        <script src='./scriptPlaylist.js'></script>
</body>

</html>
