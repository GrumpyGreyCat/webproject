/**
 * Initializes the playlist page once the DOM content is fully loaded.
 * Fetches playlists from the server and populates the "My Playlists" 
 * and "Popular Playlists" sections with clickable playlist cards.
 */
document.addEventListener('DOMContentLoaded', () => {
    fetch('getPlaylist.php')
        .then(response => response.json())
        .then(data => {
            console.log(data);

            const myPlaylist = document.getElementById('my-playlists');
            const popularPlaylist = document.getElementById('popular-playlists');

            // Iterate over each playlist returned from the server
            data.forEach(playlist => {
                // Determine whether the playlist is user-created or popular based on user_id
                const playlistCard = document.createElement('div');
                playlistCard.className = 'playlist-card';
                playlistCard.textContent = playlist['name'];

                // Add click event to redirect to the playlist details page
                playlistCard.addEventListener('click', () => {
                    window.location.href = 'insidePlaylist.php?playlist_id=' + playlist['id'];
                });

                // Append the playlist card to the appropriate container
                if (playlist['user_id'] == 0) {
                    popularPlaylist.appendChild(playlistCard);
                } else {
                    myPlaylist.appendChild(playlistCard);
                }
            });
        });
});
