<?php
// api/recensioni.php
// GET  ?idLibro=xxx  → recensioni di un libro  (tutti)
// POST               → aggiungi recensione      (utenti loggati)

require_once __DIR__ . '/../connessione.php';
require_once __DIR__ . '/../helpers.php';

setHeaders();

$metodo     = $_SERVER['REQUEST_METHOD'];
$recensioni = getCollection('recensioni');

// ─────────────────────────────────────────
// GET — leggi recensioni di un libro
// ─────────────────────────────────────────
if ($metodo === 'GET') {
    richiedeLogin();

    $idLibro = $_GET['idLibro'] ?? '';
    if (!$idLibro) jsonError('ID libro mancante');

    $lista = normalizzaLista(iterator_to_array(
        $recensioni->find(
            ['idLibro' => $idLibro],
            ['sort'    => ['createdAt' => -1]]
        )
    ));

    // calcola media voti
    $mediaVoto = 0;
    if (!empty($lista)) {
        $mediaVoto = round(array_sum(array_column($lista, 'voto')) / count($lista), 1);
    }

    jsonResponse([
        'recensioni' => $lista,
        'mediaVoto'  => $mediaVoto,
        'totale'     => count($lista)
    ]);
}

// ─────────────────────────────────────────
// POST — aggiungi recensione
// ─────────────────────────────────────────
if ($metodo === 'POST') {
    $user = richiedeLogin();
    $body = getBody();

    $idLibro  = trim($body['idLibro']  ?? '');
    $commento = trim($body['commento'] ?? '');
    $voto     = (int) ($body['voto']   ?? 0);

    if (!$idLibro)              jsonError('ID libro mancante');
    if (!$commento)             jsonError('Il commento è obbligatorio');
    if ($voto < 1 || $voto > 5) jsonError('Il voto deve essere tra 1 e 5');

    $result = $recensioni->insertOne([
        'idLibro'   => $idLibro,
        'utente'    => $user['username'],
        'userId'    => $user['id'],
        'commento'  => $commento,
        'voto'      => $voto,
        'data'      => date('d/m/Y'),
        'createdAt' => new MongoDB\BSON\UTCDateTime()
    ]);

    jsonResponse([
        'message'    => 'Recensione aggiunta con successo!',
        'recensione' => [
            '_id'      => (string) $result->getInsertedId(),
            'utente'   => $user['username'],
            'commento' => $commento,
            'voto'     => $voto,
            'data'     => date('d/m/Y')
        ]
    ], 201);
}

jsonError('Metodo non supportato', 405);