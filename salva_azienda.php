<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$nome_azienda = $_POST['nome_azienda'] ?? '';
$piva = $_POST['piva'] ?? '';
$comune = $_POST['comune'] ?? '';
$referente = $_POST['referente'] ?? '';
$telefono_referente = $_POST['telefono_referente'] ?? '';
$email_referente = $_POST['email_referente'] ?? '';
$ruolo = $_POST['ruolo'] ?? '';
$numero = $_POST['numero'] ?? '';
$descrizione = $_POST['descrizione_lavoro'] ?? '';

// Figure professionali
$figure = [];
if (isset($_POST['figure'])) {
    if (is_array($_POST['figure'])) {
        $figure = $_POST['figure'];
    } else {
        $figure = [$_POST['figure']];
    }
}
if (!empty($_POST['altre_figure'])) {
    $figure[] = $_POST['altre_figure'];
}
$figure_str = implode(', ', $figure);

// Competenze tecniche
$competenze = [];
if (isset($_POST['competenze'])) {
    if (is_array($_POST['competenze'])) {
        $competenze = $_POST['competenze'];
    } else {
        $competenze = [$_POST['competenze']];
    }
}
if (!empty($_POST['competenze_altro'])) {
    $competenze[] = $_POST['competenze_altro'];
}
$competenze_str = implode(', ', $competenze);

$luogo = $_POST['luogo'] ?? '';
$inizio_richiesta = $_POST['inizio_richiesta'] ?? '';
$durata = $_POST['durata'] ?? '';
$orario = $_POST['turni'] ?? '';
$contratto_previsto = $_POST['contratto_previsto'] ?? '';
$retribuzione = $_POST['retribuzione'] ?? '';
$urgenza = $_POST['urgenza'] ?? '';
$note = $_POST['note'] ?? '';

$to = 'aziende@inoperando.it';
$subject = 'Nuova richiesta da ' . $nome_azienda;
$headers = "From: " . $email_referente . "\r\n";
$headers .= "Reply-To: " . $email_referente . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$message = "NUOVA RICHIESTA AZIENDALE\n\n";
$message .= "Azienda: $nome_azienda\n";
$message .= "Partita IVA: $piva\n";
$message .= "Comune: $comune\n";
$message .= "Referente: $referente\n";
$message .= "Telefono: $telefono_referente\n";
$message .= "Email: $email_referente\n\n";
$message .= "Ruolo richiesto: $ruolo\n";
$message .= "Numero persone: $numero\n";
$message .= "Descrizione: $descrizione\n\n";
$message .= "Figure richieste: $figure_str\n\n";
$message .= "Competenze richieste: $competenze_str\n\n";
$message .= "Luogo: $luogo\n";
$message .= "Inizio: $inizio_richiesta\n";
$message .= "Durata: $durata\n\n";
$message .= "Orario: $orario\n";
$message .= "Contratto: $contratto_previsto\n";
$message .= "Retribuzione: $retribuzione\n\n";
$message .= "Urgenza: $urgenza\n\n";
$message .= "Note: $note\n";

if (mail($to, $subject, $message, $headers)) {
    header('Location: grazie.html');
} else {
    echo "Errore nell'invio della mail";
}
exit;
?>