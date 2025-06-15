/**
 * Main script for the "Browse" page.
 */

document.addEventListener('DOMContentLoaded', () => {
    /**
     * Fetches audio data from the server and populates the search results container.
     */
    fetch('audio.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('search-box');
            container.innerHTML = '';

            if (data.error) {
                container.textContent = data.error;
                return;
            }

            /**
             * Creates a search bar and a container for search results.
             */
            const searchBar = document.createElement('input');
            searchBar.type = 'search';
            searchBar.placeholder = 'Search by song or game name...';

            const searchResults = document.createElement('div');
            searchResults.className = 'search-results';

            /**
             * Flatten data into a list of { gameName, file } objects.
             */
            const allSongs = [];

            for (const gameName in data) {
                data[gameName].forEach(file => {
                    allSongs.push({ gameName, file });
                });
            }

            /**
             * Handles search input events.
             */
            searchBar.addEventListener('input', () => {
                const query = searchBar.value.toLowerCase().trim();
                searchResults.innerHTML = '';

                allSongs.forEach(({ gameName, file }) => {
                    const songName = file.replace('.mp3', '');
                    const combinedText = `${songName} ${gameName}`.toLowerCase();

                    if (combinedText.includes(query)) {
                        const audio = document.createElement('audio');
                        audio.preload = 'metadata';
                        audio.src = `music_mp3/${gameName}/${encodeURIComponent(file)}`;


                        const audioCard = document.createElement('div');
                        audioCard.className = 'audio-card';

                        const nameDiv = document.createElement('div');
                        nameDiv.className = 'name-div';

                        const songNameDiv = document.createElement('div');
                        songNameDiv.className = 'song-name-div'

                        const songName = document.createElement('h3');
                        var name = file.replace(".mp3", "");
                        songName.textContent = name;

                        const gameNameDiv = document.createElement('div');
                        gameNameDiv.className = 'game-name-div';

                        const nameOfGame = document.createElement('h3');
                        nameOfGame.textContent = gameName;

                        songNameDiv.appendChild(songName);
                        gameNameDiv.appendChild(nameOfGame);
                        nameDiv.appendChild(songNameDiv);
                        nameDiv.appendChild(gameNameDiv);


                        // Auto-scroll on hover (looping)
                        let scrollInterval;
                        songNameDiv.addEventListener('mouseenter', () => {
                            const scrollSpeed = 1;
                            const resetDelay = 1000;

                            scrollInterval = setInterval(() => {
                                if (songNameDiv.scrollLeft + songNameDiv.clientWidth >= songNameDiv.scrollWidth) {
                                    // Wait a moment and reset to the beginning
                                    clearInterval(scrollInterval);
                                    setTimeout(() => {
                                        songNameDiv.scrollLeft = 0;
                                        scrollInterval = setInterval(() => {
                                            songNameDiv.scrollLeft += scrollSpeed;
                                        }, 30);
                                    }, resetDelay);
                                } else {
                                    songNameDiv.scrollLeft += scrollSpeed;
                                }
                            }, 30);
                        });

                        songNameDiv.addEventListener('mouseleave', () => {
                            clearInterval(scrollInterval);
                            songNameDiv.scrollLeft = 0; // Reset scroll position on mouse leave
                        });


                        const controls = document.createElement('div');
                        controls.className = 'controls';

                        const addToPlaylistBtn = document.createElement('button');
                        addToPlaylistBtn.id = 'open-playlist-picker';
                        addToPlaylistBtn.className = 'add-to-playlist-btn';
                        addToPlaylistBtn.textContent = 'Add to a Playlist';

                        const playBtn = document.createElement('button');
                        playBtn.className = 'play-btn'
                        playBtn.textContent = '▶';

                        const volumeContainer = document.createElement('div');
                        volumeContainer.className = 'volume-container';

                        const volumeIcon = document.createElement('span');
                        volumeIcon.className = 'volume-icon';
                        volumeIcon.textContent = '🔊';

                        const volumeSlider = document.createElement('input');
                        volumeSlider.type = 'range';
                        volumeSlider.className = 'volume-slider';
                        volumeSlider.min = 0;
                        volumeSlider.max = 100;
                        volumeSlider.value = 100;

                        volumeSlider.addEventListener('input', () => {
                            audio.volume = volumeSlider.value / 100;
                        });


                        volumeContainer.appendChild(volumeIcon);
                        volumeContainer.appendChild(volumeSlider);


                        const timeDisplay = document.createElement('span');
                        timeDisplay.className = 'time-display';
                        timeDisplay.textContent = ``;

                        const progressContainer = document.createElement('div');
                        progressContainer.className = 'progress';

                        const progressBar = document.createElement('div');
                        progressBar.className = 'progress-bar';
                        progressContainer.appendChild(progressBar);

                        progressContainer.addEventListener('click', (event) => {
                            const bar = progressContainer.getBoundingClientRect();
                            const offsetX = event.clientX - bar.left;
                            const newTime = (offsetX / bar.width) * audio.duration;
                            audio.currentTime = newTime;
                        });

                        playBtn.addEventListener('click', () => {
                            if (audio.paused) {
                                audio.play();
                                playBtn.textContent = '⏸';
                            } else {
                                audio.pause();
                                playBtn.textContent = '▶';
                            }
                        });

                        audio.addEventListener('timeupdate', () => {
                            const percent = (audio.currentTime / audio.duration) * 100;
                            progressBar.style.width = percent + '%';

                            const minutes = Math.floor(audio.currentTime / 60);
                            const seconds = Math.floor(audio.currentTime % 60).toString().padStart(2, '0');
                            timeDisplay.textContent = `${minutes}:${seconds}/${Math.trunc(audio.duration / 60)}:${Math.trunc(audio.duration % 60)}`;
                        });

                        audio.addEventListener('ended', () => {
                            playBtn.textContent = '▶';
                        });


                        controls.appendChild(playBtn);
                        controls.appendChild(volumeContainer);


                        audioCard.appendChild(nameDiv);
                        audioCard.appendChild(controls);
                        audioCard.appendChild(progressContainer);
                        audioCard.appendChild(timeDisplay);
                        audioCard.appendChild(audio);
                        audioCard.appendChild(addToPlaylistBtn);
                        audio.classList.add('hidden-audio');
                        searchResults.appendChild(audioCard);

                        addToPlaylistBtn.addEventListener('click', () => {
                            const songData = {
                                song_name: file.replace('.mp3', ''), // Extracts song name from filename
                            };
                            openPlaylistPicker(songData); // Pass song data to the picker
                        });
                    };
                }
                );

                // Optionally show "no results" if nothing matched
                if (searchResults.children.length === 0 && query !== '') {
                    const noResults = document.createElement('p');
                    noResults.textContent = 'No matching songs or games found.';
                    searchResults.appendChild(noResults);
                }
            });

            container.appendChild(searchBar);
            container.appendChild(searchResults);
        });

    /**
     * Opens the playlist picker dialog with the given song data.
     * @param {Object} songData - Song data object with song name.
     */
    function openPlaylistPicker(songData) {
        const picker = document.getElementById('playlist-picker');
        picker.classList.remove('hidden');
        requestAnimationFrame(() => picker.classList.add('show'));

        const list = document.getElementById('playlist-list');
        list.innerHTML = 'Loading...';

        // Fetch playlists from server
        fetch('getPlaylist.php')
            .then(response => response.json())
            .then(playlists => {
                list.innerHTML = ''

                if (playlists.length === 0) {
                    list.innerHTML = '<li>No playlists found.</li>';
                    return;
                }

                playlists.forEach(p => {
                    if (p.id >= 1 && p.id <= 4) return;
                    const div = document.createElement('div');

                    const btn = document.createElement('button');
                    btn.textContent = p.name;
                    btn.addEventListener('click', () => {
                        
                        fetch('addToPlaylist.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                playlist_id: p.id,
                                song_id: songData.song_name
                            })
                        })

                        closePlaylistPicker();
                        showToast(`Added to ${p.name}!`);
                    });

                    div.appendChild(btn);
                    list.appendChild(div);
                });
            })
            .catch(() => {
                list.innerHTML = '<li>Error loading playlists.</li>';
            });
    }
});

/**
 * Closes the playlist picker dialog.
 */
function closePlaylistPicker() {
    const picker = document.getElementById('playlist-picker');
    picker.classList.remove('show');
    setTimeout(() => picker.classList.add('hidden'), 300);
}
// Close button
document.getElementById('close-picker').addEventListener('click', closePlaylistPicker);

/**
 * Shows a toast notification with the given message for a given duration.
 * @param {string} message - The text to display in the toast.
 * @param {number} duration - The duration to show the toast in milliseconds.
 */
function showToast(message, duration = 3000) {
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    document.body.appendChild(toast);

    // Trigger animation
    requestAnimationFrame(() => toast.classList.add('show'));

    // Auto-remove
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, duration);
}
