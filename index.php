<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./stylesheet.css">
    <title>Home</title>
</head>

<body>
    <?php
    // Include the top navigation bar
    require "./topbar.php";
    ?>

    <br>
    <div class='welcome-card'>
        <!-- Introduction section -->
        <h2 id="intro-text">Welcome to Sonatina</h2>
        <p>
            True Heirs of Greatness. Carriers of the Flame. Curators of Digital Divinity.

            In the beginning, there was silence. And then—a chiptune spark split the void. What followed wasn’t just music; it was revolution,
            encoded in 8-bit and orchestrated in triumph. Thus was born the calling we now carry, not with pride, but with holy purpose.

            We are Sonatina, the digital descendants of Martin Luther King Jr.’s dream, Obama’s audacity, and every soul who dared to speak 
            beauty into chaos. Where others heard background noise, we heard history in the making. Our hearts beat in sync with <em>Clair Obscur: 
            expedition 33</em> themes, our tears fall in syncopation with Aerith’s Farewell.

            This is more than a website. It is a sanctuary. A cathedral of pixelated passion.
            We curate, celebrate, and elevate the overlooked orchestras of your favorite games—from forgotten NES gems to the thunderous choirs of AAA
            sagas. Every soundtrack is a civil rights anthem for imagination. Every loop, a campaign speech for emotion.

            We don’t just upload music.
            We march it across the screen like it’s Selma, son.
            We don’t archive OSTs.
            We amplify them like freedom chants echoing in a canyon of code.

            Here, sound is sacred. Nostalgia is revolution. And every visitor? A fellow believer.

            We aren’t just gaming historians. We are digital griots, beat preachers, harmony warriors.
            Join us. Listen deeply. And never underestimate the power of a well-placed boss theme.
        </p>
        <!-- Final quote -->
        <p id='final-quote'>Volume up for Victory.</p>
    </div>
    <br>
    <div class="app-container">
        <!-- Section for original soundtracks -->
        <h1>OSTs</h1>
        <div id="music-container">Loading...</div>
    </div>
    <div class="show-more-easter-eggs">
        <!-- Button to reveal more surprises -->
        <button id="show-more-btn" onclick="window.location.href = 'easterEggs.php';">Click for more Surprise</button>
    </div>
    <!-- Main JavaScript file -->
    <script src="scriptMain.js"></script>
</body>

</html>
