<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
// api/libri.php
// GET                    → lista libri con ricerca   (tutti)
// GET    ?id=xxx         → dettaglio libro            (tutti)
// POST                   → aggiungi libro             (solo admin)
// PUT    ?id=xxx         → modifica libro             (solo admin)
// DELETE ?id=xxx         → elimina libro              (solo admin)

require_once __DIR__ . '/../connessione.php';
require_once __DIR__ . '/../helpers.php';

setHeaders();

$metodo = $_SERVER['REQUEST_METHOD'];
$id     = $_GET['id'] ?? null;
$libri  = getCollection('libri');   // collection libri nel DB biblioteca

// ─────────────────────────────────────────
// GET — lettura (tutti)
// ─────────────────────────────────────────
if ($metodo === 'GET') {
    richiedeLogin();

    // dettaglio singolo libro
    if ($id) {
        try {
            $libro = $libri->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            if (!$libro) jsonError('Libro non trovato', 404);
            jsonResponse(normalizza($libro));
        } catch (Exception $e) {
            jsonError('ID non valido', 400);
        }
    }

    // lista con ricerca e filtri
    $filtro = [];

    // ricerca testuale su titolo, autore, categoria
    if (!empty($_GET['ricerca'])) {
        $regex = new MongoDB\BSON\Regex($_GET['ricerca'], 'i');
        $filtro['$or'] = [
            ['titolo'    => $regex],
            ['autore'    => $regex],
            ['categoria' => $regex]
        ];
    }

    if (!empty($_GET['categoria'])) {
        $filtro['categoria'] = $_GET['categoria'];
    }

    $page  = max(1, (int) ($_GET['page']  ?? 1));
    $limit = max(1, (int) ($_GET['limit'] ?? 12));
    $skip  = ($page - 1) * $limit;

    $lista = normalizzaLista(iterator_to_array(
        $libri->find($filtro, ['sort' => ['titolo' => 1], 'skip' => $skip, 'limit' => $limit])
    ));
    $total = $libri->countDocuments($filtro);

    jsonResponse([
        'libri'      => $lista,
        'pagination' => [
            'total'      => $total,
            'page'       => $page,
            'limit'      => $limit,
            'totalPages' => (int) ceil($total / $limit)
        ]
    ]);
}

// ─────────────────────────────────────────
// POST — aggiungi libro (solo admin)
// ─────────────────────────────────────────
if ($metodo === 'POST') {
    richiedeAdmin();
    $body = getBody();

    if (empty($body['titolo']))  jsonError('Il titolo è obbligatorio');
    if (empty($body['autore']))  jsonError("L'autore è obbligatorio");

    $documento = [
        'titolo'      => trim($body['titolo']),
        'autore'      => trim($body['autore']),
        'categoria'   => trim($body['categoria']   ?? 'Varie'),
        'prezzo'      => (float) ($body['prezzo']  ?? 0),
        'descrizione' => trim($body['descrizione'] ?? ''),
        'copertina'   => trim($body['copertina']   ?? ''),
        'createdAt'   => new MongoDB\BSON\UTCDateTime(),
        'updatedAt'   => new MongoDB\BSON\UTCDateTime()
    ];

    $result              = $libri->insertOne($documento);
    $documento['_id']    = (string) $result->getInsertedId();

    jsonResponse(['message' => 'Libro aggiunto con successo!', 'libro' => $documento], 201);
}

// ─────────────────────────────────────────
// PUT — modifica libro (solo admin)
// ─────────────────────────────────────────
if ($metodo === 'PUT') {
    richiedeAdmin();
    if (!$id) jsonError('ID libro mancante');

    $body              = getBody();
    $body['updatedAt'] = new MongoDB\BSON\UTCDateTime();

    try {
        $aggiornato = $libri->findOneAndUpdate(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => $body],
            ['returnDocument' => MongoDB\Operation\FindOneAndUpdate::RETURN_DOCUMENT_AFTER]
        );
        if (!$aggiornato) jsonError('Libro non trovato', 404);
        jsonResponse(['message' => 'Libro aggiornato con successo!', 'libro' => normalizza($aggiornato)]);
    } catch (Exception $e) {
        jsonError('ID non valido', 400);
    }
}

// ─────────────────────────────────────────
// DELETE — elimina libro (solo admin)
// ─────────────────────────────────────────
if ($metodo === 'DELETE') {
    richiedeAdmin();
    if (!$id) jsonError('ID libro mancante');

    try {
        $eliminato = $libri->findOneAndDelete(['_id' => new MongoDB\BSON\ObjectId($id)]);
        if (!$eliminato) jsonError('Libro non trovato', 404);
        jsonResponse(['message' => 'Libro eliminato con successo!']);
    } catch (Exception $e) {
        jsonError('ID non valido', 400);
    }
}

jsonError('Metodo non supportato', 405);