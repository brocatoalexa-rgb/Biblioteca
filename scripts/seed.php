<?php
// scripts/seed.php
require_once __DIR__ . '/../connessione.php';

$libri = getCollection('libri');
$libri->deleteMany([]);

$ora = new MongoDB\BSON\UTCDateTime();
$campione = [
    [
        'titolo'=>'Il Nome della Rosa',
        'autore'=>'Umberto Eco',
        'categoria'=>'Romanzo',
        'prezzo'=>12.90,
        'descrizione'=>'Un capolavoro del giallo medievale ambientato in un monastero benedettino.',
        'copertina'=>'https://covers.openlibrary.org/b/isbn/9788845292613-L.jpg'
    ],
    [
        'titolo'=>'1984',
        'autore'=>'George Orwell',
        'categoria'=>'Fantascienza',
        'prezzo'=>9.90,
        'descrizione'=>'Un romanzo distopico profetico sulla sorveglianza totale dello stato.',
        'copertina'=>'https://covers.openlibrary.org/b/isbn/9780451524935-L.jpg'
    ],
    [
        'titolo'=>'Il Signore degli Anelli',
        'autore'=>'J.R.R. Tolkien',
        'categoria'=>'Fantasy',
        'prezzo'=>19.90,
        'descrizione'=>'La trilogia che ha definito il fantasy moderno.',
        'copertina'=>'https://covers.openlibrary.org/b/isbn/9780261102385-L.jpg'
    ],
    [
        'titolo'=>'Dune',
        'autore'=>'Frank Herbert',
        'categoria'=>'Fantascienza',
        'prezzo'=>14.90,
        'descrizione'=>'Un epico romanzo di fantascienza ambientato su un pianeta desertico.',
        'copertina'=>'https://covers.openlibrary.org/b/isbn/9780441013593-L.jpg'
    ],
    [
        'titolo'=>'Sapiens',
        'autore'=>'Yuval Noah Harari',
        'categoria'=>'Saggistica',
        'prezzo'=>16.50,
        'descrizione'=>'Una breve storia dell\'umanità dalla preistoria ai giorni nostri.',
        'copertina'=>'https://covers.openlibrary.org/b/isbn/9780062316097-L.jpg'
    ],
    [
        'titolo'=>'Delitto e Castigo',
        'autore'=>'Fëdor Dostoevskij',
        'categoria'=>'Narrativa',
        'prezzo'=>11.90,
        'descrizione'=>'Un capolavoro della letteratura russa sulla colpa e la redenzione.',
        'copertina'=>'https://covers.openlibrary.org/b/isbn/9780140449136-L.jpg'
    ],
    [
        'titolo'=>'Harry Potter e la Pietra Filosofale',
        'autore'=>'J.K. Rowling',
        'categoria'=>'Fantasy',
        'prezzo'=>13.90,
        'descrizione'=>'Il primo capitolo delle avventure del giovane mago Harry Potter.',
        'copertina'=>'https://covers.openlibrary.org/b/isbn/9780439708180-L.jpg'
    ],
    [
        'titolo'=>'Cosmos',
        'autore'=>'Carl Sagan',
        'categoria'=>'Saggistica',
        'prezzo'=>15.00,
        'descrizione'=>'Un viaggio meraviglioso attraverso l\'universo spiegato con poesia.',
        'copertina'=>'https://covers.openlibrary.org/b/isbn/9780345539434-L.jpg'
    ],
    [
        'titolo'=>'La Divina Commedia',
        'autore'=>'Dante Alighieri',
        'categoria'=>'Poesia',
        'prezzo'=>8.90,
        'descrizione'=>'Il viaggio di Dante attraverso Inferno, Purgatorio e Paradiso.',
        'copertina'=>'https://covers.openlibrary.org/b/isbn/9788817078238-L.jpg'
    ],
    [
        'titolo'=>'Steve Jobs',
        'autore'=>'Walter Isaacson',
        'categoria'=>'Biografia',
        'prezzo'=>18.90,
        'descrizione'=>'La biografia ufficiale del co-fondatore di Apple.',
        'copertina'=>'https://covers.openlibrary.org/b/isbn/9781451648539-L.jpg'
    ],
    [
        'titolo'=>'La Ragazza con il Tatuaggio del Drago',
        'autore'=>'Stieg Larsson',
        'categoria'=>'Giallo',
        'prezzo'=>12.50,
        'descrizione'=>'Un thriller nordico avvincente con la memorabile Lisbeth Salander.',
        'copertina'=>'https://covers.openlibrary.org/b/isbn/9780307454546-L.jpg'
    ],
    [
        'titolo'=>'Il Grande Gatsby',
        'autore'=>'F. Scott Fitzgerald',
        'categoria'=>'Narrativa',
        'prezzo'=>9.50,
        'descrizione'=>'La critica al sogno americano ambientata negli anni Venti.',
        'copertina'=>'https://covers.openlibrary.org/b/isbn/9780743273565-L.jpg'
    ],
];

foreach ($campione as &$libro) {
    $libro['createdAt'] = $ora;
    $libro['updatedAt'] = $ora;
}

$libri->insertMany($campione);

echo "<h2 style='color:green'>✅ Inseriti " . count($campione) . " libri!</h2>";
echo "<p><a href='../libri.php'>Vai al catalogo →</a></p>";
echo "<br><p style='color:red;font-weight:bold'>⚠️ Cancella questo file dal server!</p>";