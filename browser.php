<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styleBrowser.css">
    <title>Browser</title>
</head>

<body>
    <?php
    // Includes the topbar
    require "./topbar.php"
    ?>
    <!-- The search box container -->
    <div id='search-box'>
    </div>
    <!-- The playlist picker container -->
    <div id="playlist-picker" class="playlist-picker hidden">
        <!-- The title of the playlist picker -->
        <h3>Select Playlist</h3>
        <!-- The list of playlists -->
        <div id="playlist-list"></div>
        <!-- The close button -->
        <button id="close-picker">Close</button>
    </div>
    <!-- The script that handles the search and playlist picker -->
    <script src="scriptBrowse.js">
    </script>
</body>

</html>
