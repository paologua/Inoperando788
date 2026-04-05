<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database
define('DB_HOST', '31.11.39.150');
define('DB_NAME', 'Sql1927980_1');
define('DB_USER', 'Sql1927980');
define('DB_PASS', 'Coleottero1!');

// IMAP - CORRETTO
define('IMAP_OPERAI', '{imaps.aruba.it:993/imap/ssl}INBOX');
define('USER_OPERAI', 'operai@inoperando.it');
define('PASS_OPERAI', 'Gargamella3!');

define('IMAP_AZIENDE', '{imaps.aruba.it:993/imap/ssl}INBOX');
define('USER_AZIENDE', 'aziende@inoperando.it');
define('PASS_AZIENDE', 'Gargamella3!');

// Notifiche
define('ADMIN_EMAIL', 'info@inoperando.it');

// Connessione DB
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Errore DB: " . $e->getMessage());
}
?>