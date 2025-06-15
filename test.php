<?php
/**
 * Initializes a cURL session to check SSL connection to a URL.
 */

// Initialize cURL session
$ch = curl_init("https://www.google.com");

// Set option to return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the cURL session and store response
$response = curl_exec($ch);

// Check if the response is false, indicating an error
if ($response === false) {
    // Output the cURL error
    echo "Erreur cURL : " . curl_error($ch);
} else {
    // Output success message
    echo "Connexion SSL OK";
}

// Close the cURL session
curl_close($ch);

