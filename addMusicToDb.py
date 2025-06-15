import requests
import mysql.connector

def insert_videogames_and_songs():
    """Fetches game data from a remote source and inserts games and songs into the database."""
    
    # Connect to the database
    conn = mysql.connector.connect(
        host='localhost',
        user='root',
        password='',
        database='sonatina'
    )
    cursor = conn.cursor()

    # Fetch data from the remote server
    response = requests.get('http://localhost/webProject/audio.php')
    data = response.json()

    # Insert games into the database
    for game_name in data.keys():
        game_name = game_name.strip()
        
        # Check if the game already exists in the database
        cursor.execute("SELECT id FROM videogames WHERE name = %s", (game_name,))
        existing = cursor.fetchone()

        if existing:
            print(f"Game already exists: {game_name}")
            continue

        # Insert the game into the database
        cursor.execute("INSERT INTO videogames (name) VALUES (%s)", (game_name,))
        print(f"Inserted game: {game_name}")

    conn.commit()

    # Insert songs into the database
    for game_name, songs in data.items():
        game_name = game_name.strip()

        # Get the game ID from the database
        cursor.execute("SELECT id FROM videogames WHERE name = %s", (game_name,))
        game = cursor.fetchone()

        if not game:
            print(f"Game not found: {game_name}")
            continue

        game_id = game[0]

        for song_title in songs:
            song_title = song_title.strip()
            song_src = f"music_mp3/{game_name}/{song_title}"

            # Check for existing song with the same title and game_id
            cursor.execute(
                "SELECT id FROM songs WHERE title = %s AND game_id = %s",
                (song_title, game_id)
            )
            existing_song = cursor.fetchone()

            if existing_song:
                print(f"Song already exists: {song_title} for game: {game_name}")
                continue

            # Insert the song into the database
            cursor.execute(
                "INSERT INTO songs (title, game_id, src) VALUES (%s, %s, %s)",
                (song_title, game_id, song_src)
            )
            print(f"Inserted song: {song_title} for game: {game_name}")

    conn.commit()
    cursor.close()
    conn.close()
    print("Songs inserted.")

# Run the function
insert_videogames_and_songs()

