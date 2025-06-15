<?php
/**
 * Returns a JSON object containing the list of music files in the 'music_mp3' folder.
 *
 * The JSON object has the following structure:
 *
 * {
 *     "folder1": ["file1.mp3", "file2.mp3", ...],
 *     "folder2": ["file3.mp3", "file4.mp3", ...],
 *     ...
 * }
 *
 * If there is an error, the JSON object will contain an "error" key with a string value describing the error.
 */
header('Content-Type: application/json');

const MUSIC_ROOT = 'music_mp3';

$response = [];

// Check if the music root folder exists
if (!is_dir(MUSIC_ROOT)) {
    $response['error'] = "Music root folder not found: " . MUSIC_ROOT;
} else {
    // Get a list of subfolders in the music root folder
    $folders = array_filter(glob(MUSIC_ROOT . '/*'), 'is_dir');

    // Loop through each folder and get the list of music files
    foreach ($folders as $folderPath) {
        $folderName = basename($folderPath);
        $files = glob($folderPath . '/*.mp3');

        // Check if there was an error reading the files
        if ($files === false) {
            $response[$folderName] = ["error" => "Failed to read files in $folderPath"];
        } else {
            // Get the list of file names and add it to the response
            $fileNames = array_map('basename', $files);
            if (!empty($fileNames)) {
                $response[$folderName] = $fileNames;
            }
        }
    }

    // Check if there are any files at all
    if (empty($response)) {
        $response['error'] = "No mp3 files found";
    }
}

// Output the response as JSON
echo json_encode($response);

