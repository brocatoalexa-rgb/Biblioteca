<?php
// crea_cartelle.php — apri nel browser una volta sola poi cancella!

$cartella = __DIR__ . '/uploads/copertine/';

if (is_dir($cartella)) {
    echo "<p style='color:green'>✅ Cartella già esistente: $cartella</p>";
} else {
    if (mkdir($cartella, 0755, true)) {
        echo "<p style='color:green'>✅ Cartella creata con successo: $cartella</p>";
    } else {
        echo "<p style='color:red'>❌ Impossibile creare la cartella — contatta il tecnico</p>";
    }
}

// crea .htaccess di sicurezza
$htaccess = $cartella . '.htaccess';
if (!file_exists($htaccess)) {
    $contenuto = "Options -ExecCGI\nAddHandler cgi-script .php .php3 .php4 .php5\n<FilesMatch \"(?i)\.(jpg|jpeg|png|gif|webp)$\">\nAllow from all\n</FilesMatch>\n<FilesMatch \"(?i)^(?!.*\\.(jpg|jpeg|png|gif|webp)$)\">\nDeny from all\n</FilesMatch>";
    file_put_contents($htaccess, $contenuto);
    echo "<p style='color:green'>✅ .htaccess di sicurezza creato</p>";
}

// test scrittura
$testFile = $cartella . 'test.txt';
if (file_put_contents($testFile, 'test')) {
    unlink($testFile);
    echo "<p style='color:green'>✅ Cartella scrivibile — upload funzionerà!</p>";
} else {
    echo "<p style='color:red'>❌ Cartella non scrivibile — problema di permessi</p>";
}

echo "<br><p style='color:red'><strong>⚠️ Cancella questo file!</strong></p>";
?>