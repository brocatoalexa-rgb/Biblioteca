<?php
// api/upload.php — upload copertina (solo admin)

require_once __DIR__ . '/../connessione.php';
require_once __DIR__ . '/../helpers.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST')    jsonError('Metodo non supportato', 405);

//richiedeAdmin();

if (empty($_FILES['copertina'])) jsonError('Nessun file caricato');

$file = $_FILES['copertina'];

$erroriUpload = [
    UPLOAD_ERR_INI_SIZE  => 'File troppo grande (limite server)',
    UPLOAD_ERR_FORM_SIZE => 'File troppo grande',
    UPLOAD_ERR_PARTIAL   => 'Upload incompleto, riprova',
    UPLOAD_ERR_NO_FILE   => 'Nessun file selezionato',
];
if ($file['error'] !== UPLOAD_ERR_OK) {
    jsonError($erroriUpload[$file['error']] ?? 'Errore upload', 500);
}

if ($file['size'] > 5 * 1024 * 1024) jsonError('File troppo grande. Massimo 5MB');

$tipiPermessi = ['image/jpeg' => 'jpg', 'image/png' => 'png',
                 'image/webp' => 'webp', 'image/gif' => 'gif'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime  = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!array_key_exists($mime, $tipiPermessi)) {
    jsonError('Formato non supportato. Usa JPG, PNG, WEBP o GIF');
}

$cartella = __DIR__ . '/../uploads/copertine/';
if (!is_dir($cartella)) mkdir($cartella, 0755, true);

$nomeFile = 'copertina_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $tipiPermessi[$mime];
$percorso = $cartella . $nomeFile;

if (!move_uploaded_file($file['tmp_name'], $percorso)) {
    jsonError('Errore nel salvataggio del file', 500);
}

$proto    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'];
$coverUrl = "$proto://$host/~inb5/Rosso-Brocato-Scoccia/biblioteca/uploads/copertine/$nomeFile";
jsonResponse(['message' => 'Copertina caricata!', 'copertina' => $coverUrl, 'fileName' => $nomeFile], 201);