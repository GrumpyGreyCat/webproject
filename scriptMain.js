/**
 * Fetch music data from the server and display it in a carousel format.
 */
fetch('audio.php')
  .then(response => response.json())
  .then(data => {
    const container = document.getElementById('music-container');
    container.innerHTML = '';

    // Display error message if any
    if (data.error) {
      container.textContent = data.error;
      return;
    }

    // Iterate over each game and its associated audio files
    for (const [gameName, files] of Object.entries(data)) {
      const section = document.createElement('div');
      section.className = 'game-section';

      const slideshow = document.createElement('div');
      slideshow.className = 'slideshow';

      const titleCard = document.createElement('div');
      titleCard.className = 'title-card';
      const title = document.createElement('h2');
      title.textContent = gameName;
      titleCard.appendChild(title);
      section.appendChild(titleCard);

      let minIndex = 0;
      let maxIndex = 4;

      const leftArrow = document.createElement('button');
      leftArrow.className = 'carousel-arrow';
      leftArrow.textContent = '◀';

      const rightArrow = document.createElement('button');
      rightArrow.className = 'carousel-arrow';
      rightArrow.textContent = '▶';

      const audioCardContainer = document.createElement('div');
      audioCardContainer.className = 'audio-card-container';

      /**
       * Show audio cards in the specified range.
       * @param {number} min - The minimum index of the cards to show.
       * @param {number} max - The maximum index of the cards to show.
       */
      function showCard(min, max) {
        const cards = audioCardContainer.getElementsByClassName('audio-card');
        for (let i = 0; i < cards.length; i++) {
          cards[i].style.display = i >= min && i <= max ? 'block' : 'none';
        }
      }

      // Event listener for left arrow click
      leftArrow.addEventListener('click', () => {
        minIndex = Math.max(0, minIndex - 5);
        maxIndex = minIndex + 4;
        showCard(minIndex, maxIndex);
      });

      // Event listener for right arrow click
      rightArrow.addEventListener('click', () => {
        const cards = audioCardContainer.getElementsByClassName('audio-card');
        const totalCards = cards.length;

        if (maxIndex < totalCards - 1) {
          minIndex = Math.min(minIndex + 5, totalCards - 5);
          maxIndex = minIndex + 4;

          if (maxIndex >= totalCards) {
            maxIndex = totalCards - 1;
            minIndex = Math.max(0, maxIndex - 4);
          }

          showCard(minIndex, maxIndex);
        }
      });

      slideshow.appendChild(leftArrow);

      if (Array.isArray(files)) {
        files.forEach(file => {
          const audio = document.createElement('audio');
          audio.preload = 'metadata';
          audio.src = `music_mp3/${gameName}/${encodeURIComponent(file)}`;

          const audioCard = document.createElement('div');
          audioCard.className = 'audio-card';
          
          const songNameDiv = document.createElement('div');
          songNameDiv.className = 'song-name-div';
          const songName = document.createElement('h3');
          songName.textContent = file.replace(".mp3", "");
          songNameDiv.appendChild(songName);

          // Auto-scroll song name on hover
          let scrollInterval;
          songNameDiv.addEventListener('mouseenter', () => {
            const scrollSpeed = 1;
            const resetDelay = 1000;

            scrollInterval = setInterval(() => {
              if (songNameDiv.scrollLeft + songNameDiv.clientWidth >= songNameDiv.scrollWidth) {
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
            songNameDiv.scrollLeft = 0;
          });

          const controls = document.createElement('div');
          controls.className = 'controls';

          const playBtn = document.createElement('button');
          playBtn.className = 'play-btn';
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

          const progressContainer = document.createElement('div');
          progressContainer.className = 'progress';

          const progressBar = document.createElement('div');
          progressBar.className = 'progress-bar';
          progressContainer.appendChild(progressBar);

          const cursor = document.createElement('div');
          cursor.className = 'progress-cursor';
          progressContainer.appendChild(cursor);

          progressContainer.addEventListener('click', (event) => {
            const bar = progressContainer.getBoundingClientRect();
            const offsetX = event.clientX - bar.left;
            const newTime = (offsetX / bar.width) * audio.duration;
            audio.currentTime = newTime;
          });

          // Play/pause button functionality
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
            cursor.style.left = percent + '%';

            const minutes = Math.floor(audio.currentTime / 60);
            const seconds = Math.floor(audio.currentTime % 60).toString().padStart(2, '0');
            timeDisplay.textContent = `${minutes}:${seconds}/${Math.trunc(audio.duration / 60)}:${Math.trunc(audio.duration % 60)}`;
          });

          audio.addEventListener('ended', () => {
            playBtn.textContent = '▶';
          });

          controls.appendChild(playBtn);
          controls.appendChild(volumeContainer);

          audioCard.appendChild(songNameDiv);
          audioCard.appendChild(controls);
          audioCard.appendChild(progressContainer);
          audioCard.appendChild(timeDisplay);
          audioCard.appendChild(audio);
          audio.classList.add('hidden-audio');

          audioCardContainer.appendChild(audioCard);
        });

        slideshow.appendChild(audioCardContainer);
        slideshow.appendChild(rightArrow);
        section.appendChild(slideshow);
        container.appendChild(section);

        showCard(minIndex, maxIndex); // Initialize carousel
      } else if (files.error) {
        const errorMsg = document.createElement('p');
        errorMsg.textContent = files.error;
        section.appendChild(errorMsg);
        container.appendChild(section);
      }
    }
  })
  .catch(err => {
    document.getElementById('music-container').textContent = 'Error loading MP3s: ' + err;
  });

