<?php
// scripts/crea_admin.php
// Apri nel browser UNA VOLTA SOLA, poi cancella questo file!
// http://10.10.13.2/biblioteca/scripts/crea_admin.php

require_once __DIR__ . '/../connessione.php';

// ── Cambia questi dati prima di aprire ───────────────────────
$USERNAME = 'admin';
$EMAIL    = 'admin@biblioteca.it';
$PASSWORD = 'Admin123!';          // cambia con una password sicura
// ─────────────────────────────────────────────────────────────

$utenti   = getCollection('utenti');
$esistente = $utenti->findOne(['email' => $EMAIL]);

if ($esistente) {
    die("<h2>⚠️ Admin già esistente!</h2><p>Username: {$esistente['username']}</p>");
}

$utenti->insertOne([
    'username'    => $USERNAME,
    'email'       => $EMAIL,
    'password'    => password_hash($PASSWORD, PASSWORD_BCRYPT),
    'ruolo'       => 'admin',
    'token'       => null,
    'tokenExpiry' => null,
    'createdAt'   => new MongoDB\BSON\UTCDateTime()
]);

echo "<h2 style='color:green'>✅ Admin creato con successo!</h2>";
echo "<p><strong>Username:</strong> $USERNAME</p>";
echo "<p><strong>Email:</strong> $EMAIL</p>";
echo "<p><strong>Password:</strong> $PASSWORD</p>";
echo "<br><p style='color:red;font-weight:bold'>⚠️ CANCELLA QUESTO FILE DAL SERVER ADESSO!</p>";
echo "<p><a href='../index.php'>Vai al login →</a></p>";