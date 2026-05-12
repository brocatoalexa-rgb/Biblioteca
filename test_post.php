<?php
// test_post.php — mostra esattamente cosa riceve il server
// http://10.10.13.2/biblioteca/test_post.php
// CANCELLA dopo l'uso!

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

$raw     = file_get_contents('php://input');
$decoded = json_decode($raw, true);

echo json_encode([
    'method'          => $_SERVER['REQUEST_METHOD'],
    'action_from_get' => $_GET['action'] ?? 'NON PRESENTE',
    'content_type'    => $_SERVER['CONTENT_TYPE'] ?? 'NON PRESENTE',
    'authorization'   => $_SERVER['HTTP_AUTHORIZATION'] ?? 'NON PRESENTE',
    'raw_body'        => $raw,
    'decoded_body'    => $decoded,
    'post_vars'       => $_POST,
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);