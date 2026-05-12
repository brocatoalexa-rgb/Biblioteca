<?php
// connessione.php — usa vendor installato nella cartella del progetto

require_once __DIR__ . '/vendor/autoload.php';

define('MONGO_URI', 'mongodb://10.10.13.2:27017');
define('MONGO_DB',  'biblioteca');

function getDB(): MongoDB\Database {
    static $db = null;
    if ($db === null) {
        $client = new MongoDB\Client(MONGO_URI);
        $db     = $client->selectDatabase(MONGO_DB);
    }
    return $db;
}

function getCollection(string $nome): MongoDB\Collection {
    return getDB()->selectCollection($nome);
}