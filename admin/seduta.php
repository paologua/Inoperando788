<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Percorso assoluto per funzionare anche in CRON Aruba
require_once __DIR__ . '/../includes/config.php';

echo "PARSER AVVIATO<br>";

// Sintassi corretta per Aruba
$mailbox_candidati = '{imaps.aruba.it:993/imap/ssl/novalidate-cert}INBOX';
$mailbox_aziende = '{imaps.aruba.it:993/imap/ssl/novalidate-cert}INBOX';

function leggiMail($mailbox, $username, $password, $tipo) {
    global $pdo;
    echo "Connessione a $username...<br>";
    
    $inbox = imap_open($mailbox, $username, $password);
    if (!$inbox) {
        echo "ERRORE IMAP: " . imap_last_error() . "<br>";
        return;
    }
    
    $emails = imap_search($inbox, 'UNSEEN');
    if ($emails) {
        echo "Trovate " . count($emails) . " email<br>";
        foreach ($emails as $email_id) {
            $body = imap_fetchbody($inbox, $email_id, 1);
            
            if ($tipo == 'candidato') {
                preg_match('/Nome:\s*(.+)/i', $body, $m);
                $nome = trim($m[1] ?? 'Sconosciuto');
                preg_match('/Email:\s*(.+)/i', $body, $m);
                $email = trim($m[1] ?? '');
                
                if ($email) {
                    $stmt = $pdo->prepare("INSERT INTO candidati (nome, email) VALUES (?, ?)");
                    $stmt->execute([$nome, $email]);
                    echo "✅ Candidato salvato: $nome<br>";
                }
            } else {
                preg_match('/Azienda:\s*(.+)/i', $body, $m);
                $azienda = trim($m[1] ?? 'Sconosciuta');
                preg_match('/Email:\s*(.+)/i', $body, $m);
                $email = trim($m[1] ?? '');
                
                if ($email) {
                    $stmt = $pdo->prepare("INSERT INTO richieste_aziende (nome_azienda, email_referente) VALUES (?, ?)");
                    $stmt->execute([$azienda, $email]);
                    echo "✅ Azienda salvata: $azienda<br>";
                }
            }
            imap_setflag_full($inbox, $email_id, "\\Seen");
        }
    } else {
        echo "Nessuna email non letta<br>";
    }
    imap_close($inbox);
}

echo "--- LETTURA CANDIDATI ---<br>";
leggiMail($mailbox_candidati, USER_OPERAI, PASS_OPERAI, 'candidato');

echo "<br>--- LETTURA AZIENDE ---<br>";
leggiMail($mailbox_aziende, USER_AZIENDE, PASS_AZIENDE, 'azienda');

echo "<br>PARSER COMPLETATO";
?>