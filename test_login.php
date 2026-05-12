<?php
// test_login.php — testa il login direttamente
// http://10.10.13.2/biblioteca/test_login.php
// CANCELLA dopo l'uso!

require_once 'connessione.php';
require_once 'helpers.php';

header('Content-Type: application/json; charset=utf-8');

$utente   = getCollection('utente');
$username = 'admin';
$password = 'Admin123!';

// cerca utente
$user = $utente->findOne([
    '$or' => [['username' => $username], ['email' => $username]]
]);

if (!$user) {
    echo json_encode(['step' => 'findOne', 'error' => 'Utente non trovato']);
    exit;
}

echo json_encode(['step' => 'findOne', 'ok' => true, 'username' => $user['username']]);
echo "\n";

// verifica password
if (!password_verify($password, $user['password'])) {
    echo json_encode(['step' => 'password', 'error' => 'Password errata']);
    exit;
}

echo json_encode(['step' => 'password', 'ok' => true]);
echo "\n";

// genera token
$token       = bin2hex(random_bytes(32));
$tokenExpiry = new MongoDB\BSON\UTCDateTime((time() + 7 * 24 * 3600) * 1000);

echo json_encode(['step' => 'token_generato', 'token' => substr($token, 0, 10) . '...']);
echo "\n";

// salva token
try {
    $utente->updateOne(
        ['_id' => $user['_id']],
        ['$set' => ['token' => $token, 'tokenExpiry' => $tokenExpiry]]
    );
    echo json_encode(['step' => 'updateOne', 'ok' => true]);
    echo "\n";
} catch (Exception $e) {
    echo json_encode(['step' => 'updateOne', 'error' => $e->getMessage()]);
    exit;
}

// verifica che il token sia stato salvato
$userAggiornato = $utente->findOne(['token' => $token]);
if ($userAggiornato) {
    echo json_encode(['step' => 'verifica_token', 'ok' => true, 'messaggio' => 'Token salvato correttamente!']);
} else {
    echo json_encode(['step' => 'verifica_token', 'error' => 'Token NON trovato dopo il salvataggio']);
}