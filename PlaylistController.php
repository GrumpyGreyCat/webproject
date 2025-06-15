<?php
require_once 'Playlist.php';

/**
 * Class PlaylistController
 * Handles database operations for playlists
 */
class PlaylistController
{
    /**
     * @var PDO
     */
    private PDO $db;

    /**
     * Constructs a new PlaylistController
     */
    public function __construct()
    {
        $dbHost = 'localhost';
        $dbName = 'sonatina';
        $dbPort = 3306;
        $dbUser = 'root';
        $dbPass = '';

        try {
            $dsn = "mysql:host=$dbHost;dbname=$dbName;port=$dbPort;charset=utf8mb4";
            $this->db = new PDO($dsn, $dbUser, $dbPass);
        } catch (PDOException $error) {
            error_log("Database connection error: " . $error->getMessage());
            die("Connection failed: " . $error->getMessage());
        }
    }

    /**
     * Sets the database connection
     * @param PDO $db
     */
    public function setDb(PDO $db): void
    {
        $this->db = $db;
    }

    /**
     * Creates a new playlist
     * @param Playlist $playlist
     */
    public function createPlaylist(Playlist $playlist): void
    {
        $sql = "INSERT INTO playlists (name, user_id) VALUES (:name, :user_id)";
        $req = $this->db->prepare($sql);
        $req->bindValue(':name', $playlist->getName());
        $req->bindValue(':user_id', $playlist->getUserId());
        $req->execute();
    }

    /**
     * Returns all playlists
     * @return array
     */
    public function readAllPlaylist(): array
    {
        $sql = "SELECT * FROM playlists";
        $req = $this->db->prepare($sql);
        $req->execute();
        $playlists = $req->fetchAll(PDO::FETCH_ASSOC);
        return $playlists;
    }

    /**
     * Returns a playlist by its id
     * @param int $id
     * @return array
     */
    public function readPlaylistById(int $id): array
    {
        $sql = "SELECT * FROM playlists WHERE id = :id";
        $req = $this->db->prepare($sql);
        $req->bindValue(':id', $id);
        $req->execute();
        $playlist = $req->fetch(PDO::FETCH_ASSOC);
        return $playlist;
    }

    /**
     * Updates a playlist
     * @param Playlist $playlist
     */
    public function updatePlaylist(Playlist $playlist): void
    {
        $sql = "UPDATE playlists SET name = :name, user_id = :user_id WHERE id = :id";
        $req = $this->db->prepare($sql);
        $req->bindValue(':id', $playlist->getId());
        $req->bindValue(':name', $playlist->getName());
        $req->bindValue(':user_id', $playlist->getUserId());
        $req->execute();
    }

    /**
     * Deletes a playlist
     * @param Playlist $playlist
     */
    public function deletePlaylist(Playlist $playlist): void
    {
        $sql = "DELETE FROM playlists WHERE id = :id";
        $req = $this->db->prepare($sql);
        $req->bindValue(':id', $playlist->getId());
        $req->execute();
    }

    /**
     * Returns a playlist by its user id
     * @param int $user_id
     * @return Playlist|null
     */
    public function getPlaylistByUserId(int $user_id): ?Playlist
    {
        $sql = "SELECT * FROM playlists WHERE user_id = :user_id";
        $req = $this->db->prepare($sql);
        $req->bindValue(':user_id', $user_id);
        $req->execute();
        $playlist = $req->fetch();
        if (!$playlist) {
            return null;
        }
        return new Playlist($playlist);
    }

    /**
     * Returns a playlist by its name
     * @param string $name
     * @return Playlist|null
     */
    public function getPlaylistByName(string $name): ?Playlist
    {
        $sql = "SELECT * FROM playlists WHERE name = :name";
        $req = $this->db->prepare($sql);
        $req->bindValue(':name', $name);
        $req->execute();
        $playlist = $req->fetch();
        if (!$playlist) {
            return null;
        }
        return new Playlist($playlist);
    }

    /**
     * Returns a playlist by its id
     * @param int $playlistId
     * @return Playlist|null
     */
    public function getPlaylistById(int $playlistId): ?Playlist
    {
        $stmt = $this->db->prepare('SELECT * FROM playlists WHERE id = :playlistId');
        $stmt->execute(['playlistId' => $playlistId]);
        $playlistData = $stmt->fetch();

        if (!$playlistData) {
            return null;
        }

        return new Playlist($playlistData);
    }

    /**
     * Adds a song to a playlist
     * @param Playlist $playlist
     * @param int $song_id
     */
    public function addSong(Playlist $playlist, int $song_id): void
    {
        $sql = "INSERT INTO playlist_songs (playlist_id, song_id) VALUES (:playlist_id, :song_id)";
        $req = $this->db->prepare($sql);
        $req->bindValue(':playlist_id', $playlist->getId());
        $req->bindValue(':song_id', $song_id);
        $req->execute();
    }

    /**
     * Removes a song from a playlist
     * @param Playlist $playlist
     * @param int $song_id
     */
    public function removeSong(Playlist $playlist, int $song_id): void
    {
        $sql = "DELETE FROM playlist_songs WHERE playlist_id = :playlist_id AND song_id = :song_id";
        $req = $this->db->prepare($sql);
        $req->bindValue(':playlist_id', $playlist->getId());
        $req->bindValue(':song_id', $song_id);
        $req->execute();
    }

    /**
     * Returns the songs of a playlist
     * @param Playlist $playlist
     * @return array
     */
    public function getSongs(Playlist $playlist): array
    {
        $sql = "SELECT * FROM playlist_songs WHERE playlist_id = :playlist_id";
        $req = $this->db->prepare($sql);
        $req->bindValue(':playlist_id', $playlist->getId());
        $req->execute();
        $songs = $req->fetchAll(PDO::FETCH_ASSOC);
        return $songs;
    }

    /**
     * Returns the name of a song by its id
     * @param int $song_id
     * @return string
     */
    public function getSongNameBySongId(int $song_id): string
    {
        $sql = "SELECT title FROM songs JOIN playlist_songs ON songs.id = playlist_songs.song_id WHERE playlist_songs.song_id = :song_id";
        $req = $this->db->prepare($sql);
        $req->bindValue(':song_id', $song_id);
        $req->execute();
        $song = $req->fetch();
        return $song['title'];
    }

    /**
     * Returns the src of a song by its id
     * @param int $song_id
     * @return string
     */
    public function getSongSrcBySongId(int $song_id): string
    {
        $sql = "SELECT src FROM songs JOIN playlist_songs ON songs.id = playlist_songs.song_id WHERE playlist_songs.song_id = :song_id";
        $req = $this->db->prepare($sql);
        $req->bindValue(':song_id', $song_id);
        $req->execute();
        $song = $req->fetch();
        return $song['src'];
    }

    /**
     * Returns the id of a song by its name
     * @param string $songName
     * @return int|null
     */
    public function getSongIdBySongName(string $songName): ?int
    {
        $sql = 'SELECT id FROM songs WHERE title = :songName';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['songName' => $songName]);
        $song = $stmt->fetch();

        if ($song && isset($song['id'])) {
            return (int)$song['id'];
        }

        // Return null if not found
        return null;
    }

    /**
     * Adds a song to a playlist
     * @param int $playlist_id
     * @param int $song_id
     */
    public function addSongToPlaylist(int $playlist_id, int $song_id): void
    {
        $sql = "INSERT INTO playlist_songs (playlist_id, song_id) VALUES (:playlist_id, :song_id)";
        $req = $this->db->prepare($sql);
        $req->bindValue(':playlist_id', $playlist_id);
        $req->bindValue(':song_id', $song_id);
        $req->execute();
    }

    /**
     * Removes a song from a playlist
     * @param int $playlist_id
     * @param int $song_id
     */
    public function removeSongFromPlaylist(int $playlist_id, int $song_id): void
    {
        $sql = "DELETE FROM playlist_songs WHERE playlist_id = :playlist_id AND song_id = :song_id";
        $req = $this->db->prepare($sql);
        $req->bindValue(':playlist_id', $playlist_id);
        $req->bindValue(':song_id', $song_id);
        $req->execute();
    }
}