<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../connessione.php';
require_once __DIR__ . '/../helpers.php';

setHeaders();

$metodo = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$utente = getCollection('utente');

if ($metodo === 'POST' && $action === 'register') {
    $body     = getBody();
    $username = trim($body['username'] ?? '');
    $email    = trim($body['email']    ?? '');
    $password = trim($body['password'] ?? '');

    if (!$username || !$email || !$password)        jsonError('Tutti i campi sono obbligatori');
    if (strlen($username) < 3)                      jsonError('Username troppo corto (min 3 caratteri)');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) jsonError('Email non valida');
    if (strlen($password) < 6)                      jsonError('Password troppo corta (min 6 caratteri)');

    $esiste = $utente->findOne(['$or' => [['email' => $email], ['username' => $username]]]);
    if ($esiste) jsonError('Username o email già in uso', 409);

    $token       = bin2hex(random_bytes(32));
    $tokenExpiry = new MongoDB\BSON\UTCDateTime((time() + 7 * 24 * 3600) * 1000);

    $result = $utente->insertOne([
        'username'    => $username,
        'email'       => $email,
        'password'    => password_hash($password, PASSWORD_BCRYPT),
        'ruolo'       => 'utente',
        'token'       => $token,
        'tokenExpiry' => $tokenExpiry,
        'createdAt'   => new MongoDB\BSON\UTCDateTime()
    ]);

    jsonResponse([
        'message' => 'Registrazione avvenuta con successo!',
        'token'   => $token,
        'user'    => [
            'id'       => (string) $result->getInsertedId(),
            'username' => $username,
            'email'    => $email,
            'ruolo'    => 'utente'
        ]
    ], 201);
}

if ($metodo === 'POST' && $action === 'login') {
    $body     = getBody();
    $username = trim($body['username'] ?? '');
    $password = trim($body['password'] ?? '');

    if (!$username || !$password) jsonError('Username e password obbligatori');

    $user = $utente->findOne([
        '$or' => [['username' => $username], ['email' => $username]]
    ]);

    if (!$user || !password_verify($password, $user['password'])) {
        jsonError('Credenziali non valide', 401);
    }

    $token       = bin2hex(random_bytes(32));
    $tokenExpiry = new MongoDB\BSON\UTCDateTime((time() + 7 * 24 * 3600) * 1000);

    $utente->updateOne(
        ['_id' => $user['_id']],
        ['$set' => ['token' => $token, 'tokenExpiry' => $tokenExpiry]]
    );

    jsonResponse([
        'message' => 'Login effettuato con successo!',
        'token'   => $token,
        'user'    => [
            'id'       => (string) $user['_id'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'ruolo'    => $user['ruolo']
        ]
    ]);
}

if ($metodo === 'POST' && $action === 'logout') {
    $user = richiedeLogin();
    $utente->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($user['id'])],
        ['$set' => ['token' => null, 'tokenExpiry' => null]]
    );
    jsonResponse(['message' => 'Logout effettuato con successo!']);
}

if ($metodo === 'GET' && $action === 'me') {
    jsonResponse(richiedeLogin());
}

jsonError('Azione non valida', 404);