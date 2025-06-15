<?php
session_start();

require_once 'vendor/autoload.php'; // Nécessite d'avoir installé google/apiclient avec Composer

header('Content-Type: application/json');

// Récupère les données JSON envoyées par fetch()
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id_token'])) {
    echo json_encode(['success' => false, 'error' => 'ID token manquant']);
    exit;
}

$id_token = $input['id_token'];

// Initialise le client Google
$client = new Google_Client([
    'client_id' => '981333410353-8kcort6vu35goatsm071erq9ipv83hja.apps.googleusercontent.com'
]);

// Vérifie le token
$payload = $client->verifyIdToken($id_token);

if ($payload) {
    // L'utilisateur est authentifié par Google
    $email = $payload['email'];
    $name = $payload['name'];

    // Exemple : enregistre les infos dans la session
    $_SESSION['username'] = $email;

    echo json_encode(['success' => true]);
} else {
    // Token invalide
    echo json_encode(['success' => false, 'error' => 'Jeton invalide']);
}
