<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create playlist</title>
    <link rel="stylesheet" href="./styleCreatePlaylist.css">
</head>

<body>
    <?php
    /**
     * Includes the topbar
     */
    require "./topbar.php";

    /**
     * Handles the POST request
     */
    if ($_POST) {
        /**
         * Gets the user's email from the session
         */
        $email = $_SESSION['username'];

        /**
         * Gets the user's id from the database
         */
        $user = $userController->returnIdByEmail($email);
        $user_id = $user['id'];

        /**
         * Gets the playlist name from the form
         */
        $playlistName = $_POST['playlistName'];

        /**
         * Instantiates a new PlaylistController
         */
        $playlistController = new PlaylistController();

        /**
         * Creates a new Playlist object
         */
        $playlist = new Playlist(['name' => $playlistName, 'user_id' => $user_id]);

        /**
         * Checks if the playlist name is not empty
         */
        if (isset($playlistName)) {
            /**
             * Checks if the playlist already exists
             */
            if (!$playlistController->getPlaylistByName($playlistName)) {
                /**
                 * Creates the playlist
                 */
                $playlistController->createPlaylist($playlist);

                /**
                 * Redirects to the playlist page
                 */
                header('Location: ./playlistPage.php');
            }
        }
    }

    ?>
    <div class='wrapper'>
        <div class="create-playlist-container">
            <form method="post">
                <label for="playlistName">Playlist name:</label>
                <input type="text" id="playlistName" name="playlistName" placeholder="Playlist name" required>
                <button id='create-playlist' type="submit">Create</button>
            </form>
        </div>
    </div>
</body>

</html>
