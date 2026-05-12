<?php
// helpers.php

function jsonResponse(mixed $data, int $status = 200): void {
    if (ob_get_level()) ob_clean();
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function jsonError(string $msg, int $status = 400): void {
    jsonResponse(['error' => $msg], $status);
}

function getBody(): array {
    // prova prima il body JSON raw
    $raw = file_get_contents('php://input');
    if (!empty($raw)) {
        $data = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            return $data;
        }
    }
    // fallback su $_POST (form tradizionale)
    if (!empty($_POST)) {
        return $_POST;
    }
    return [];
}

function setHeaders(): void {
    if (!ob_get_level()) ob_start();
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

function getToken(): string {
    // prova HTTP_AUTHORIZATION
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    // alcuni server usano REDIRECT_HTTP_AUTHORIZATION
    if (empty($header)) {
        $header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    }
    // alcuni server passano il token via Apache header
    if (empty($header) && function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        $header  = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    }
    return str_replace('Bearer ', '', $header);
}

function getUtenteLoggato(): ?array {
    $token = getToken();
    if (empty($token)) return null;

    $user = getCollection('utente')->findOne(['token' => $token]);
    if (!$user) return null;

    $scadenza = $user['tokenExpiry']->toDateTime();
    if ($scadenza < new DateTime()) {
        getCollection('utente')->updateOne(
            ['token' => $token],
            ['$set'  => ['token' => null, 'tokenExpiry' => null]]
        );
        return null;
    }

    return [
        'id'       => (string) $user['_id'],
        'username' => $user['username'],
        'email'    => $user['email'],
        'ruolo'    => $user['ruolo']
    ];
}

function richiedeLogin(): array {
    $user = getUtenteLoggato();
    if (!$user) jsonError('Devi fare il login per continuare', 401);
    return $user;
}

function richiedeAdmin(): array {
    $user = richiedeLogin();
    if ($user['ruolo'] !== 'admin') {
        jsonError('Accesso negato. Solo gli amministratori possono eseguire questa operazione.', 403);
    }
    return $user;
}

function normalizza($doc): array {
    $doc = (array) $doc;
    if (isset($doc['_id'])) $doc['_id'] = (string) $doc['_id'];
    return $doc;
}

function normalizzaLista(array $lista): array {
    return array_map('normalizza', $lista);
}