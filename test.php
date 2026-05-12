<?php
// test.php — metti questo file nella cartella biblioteca e aprilo nel browser
// http://10.10.13.2/biblioteca/test.php
// CANCELLA dopo aver risolto il problema!

require_once 'connessione.php';

echo "<h2>Test connessione MongoDB</h2>";

// ── Test 1: connessione DB ────────────────────────────────────────────────
try {
    $utente = getCollection('utente');
    echo "<p style='color:green'>✅ Connessione al DB riuscita</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Errore connessione: " . $e->getMessage() . "</p>";
    die();
}

// ── Test 2: quanti utenti ci sono? ────────────────────────────────────────
$totale = $utente->countDocuments([]);
echo "<p>👥 Utenti nella collection 'utente': <strong>$totale</strong></p>";

// ── Test 3: mostra tutti gli utenti (senza password) ─────────────────────
$lista = $utente->find([], ['limit' => 10]);
echo "<h3>Utenti trovati:</h3>";
if (empty($lista)) {
    echo "<p style='color:orange'>⚠️ Nessun utente trovato — devi prima creare l'admin!</p>";
    echo "<p>Vai su: <a href='scripts/crea_admin.php'>scripts/crea_admin.php</a></p>";
} else {
    echo "<ul>";
    foreach ($lista as $u) {
        $id       = isset($u['_id']) ? (string)$u['_id'] : 'N/A';
        $username = $u['username'] ?? 'N/A';
        $email    = $u['email']    ?? 'N/A';
        $ruolo    = $u['ruolo']    ?? 'N/A';
        $token    = $u['token']    ?? 'null';
        echo "<li>ID: $id | Username: <strong>$username</strong> | Email: $email | Ruolo: $ruolo | Token: " . ($token !== 'null' ? '✅ presente' : '❌ null') . "</li>";
    }
    echo "</ul>";
}

// ── Test 4: prova login manuale ───────────────────────────────────────────
echo "<h3>Test login manuale:</h3>";
$username  = 'admin';   // cambia con il tuo username
$password  = 'Admin123!'; // cambia con la tua password

$trovato = $utente->findOne([
    '$or' => [['username' => $username], ['email' => $username]]
]);

if (!$trovato) {
    echo "<p style='color:red'>❌ Utente '$username' non trovato nel DB</p>";
} else {
    echo "<p style='color:green'>✅ Utente trovato: {$trovato['username']}</p>";
    echo "<p>Hash password nel DB: <code>" . substr($trovato['password'] ?? '', 0, 30) . "...</code></p>";

    // verifica password
    $ok = password_verify($password, $trovato['password'] ?? '');
    if ($ok) {
        echo "<p style='color:green'>✅ Password corretta!</p>";
    } else {
        echo "<p style='color:red'>❌ Password NON corretta — l'hash non corrisponde</p>";
        echo "<p>Prova a ricreare l'admin: <a href='scripts/crea_admin.php'>scripts/crea_admin.php</a></p>";
    }
}

// ── Test 5: verifica struttura helpers.php ────────────────────────────────
echo "<h3>Test helpers.php:</h3>";
if (file_exists('helpers.php')) {
    require_once 'helpers.php';
    echo "<p style='color:green'>✅ helpers.php caricato</p>";
} else {
    echo "<p style='color:red'>❌ helpers.php non trovato</p>";
}

// ── Test 6: mostra nome collection usata ─────────────────────────────────
echo "<h3>Riepilogo:</h3>";
echo "<ul>";
echo "<li>URI MongoDB: <code>" . MONGO_URI . "</code></li>";
echo "<li>Database: <code>" . MONGO_DB . "</code></li>";
echo "<li>Collection utenti: <code>utente</code></li>";
echo "</ul>";

echo "<br><p style='color:red'><strong>⚠️ CANCELLA QUESTO FILE DOPO L'USO!</strong></p>";
?>