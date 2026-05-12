<?php
// fix_permessi.php — apri nel browser una volta sola poi cancella!

$cartella = __DIR__ . '/uploads/copertine/';

// prova a cambiare i permessi
if (chmod($cartella, 0777)) {
    echo "<p style='color:green'>✅ Permessi cambiati a 777!</p>";
} else {
    echo "<p style='color:red'>❌ Impossibile cambiare permessi via PHP — usa FTP</p>";
}

// testa scrittura
$testFile = $cartella . 'test.txt';
if (file_put_contents($testFile, 'test')) {
    unlink($testFile);
    echo "<p style='color:green'>✅ Cartella ora scrivibile — upload funzionerà!</p>";
} else {
    echo "<p style='color:red'>❌ Ancora non scrivibile — devi usare FTP</p>";
    echo "<p>Percorso cartella: <code>$cartella</code></p>";
    echo "<p>In FileZilla: tasto destro sulla cartella → File permissions → 777</p>";
}

echo "<br><p style='color:red'><strong>⚠️ Cancella questo file!</strong></p>";
?>