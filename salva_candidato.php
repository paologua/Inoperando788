<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$nome = $_POST['nome'] ?? '';
$eta = $_POST['eta'] ?? '';
$comune = $_POST['comune'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$email = $_POST['email'] ?? '';
$titolo_studio = $_POST['titolo_studio'] ?? '';
$anno_diploma = $_POST['anno_diploma'] ?? '';
$corsi_tecnici = $_POST['corsi_tecnici'] ?? '';
$certificazioni = $_POST['certificazioni'] ?? '';

// Figure professionali - gestione array o stringa
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

// Competenze tecniche - gestione array o stringa
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

$anni_esperienza = $_POST['anni_esperienza'] ?? '';
$ultimo_ruolo = $_POST['ultimo_ruolo'] ?? '';
$mansioni = $_POST['mansioni'] ?? '';
$motivo_uscita = $_POST['motivo_uscita'] ?? '';
$cosa_sai_fare = $_POST['cosa_sai_fare'] ?? '';
$cosa_non_vuoi_fare = $_POST['cosa_non_vuoi_fare'] ?? '';
$inizio = $_POST['inizio'] ?? '';
$contratto = $_POST['contratto'] ?? '';
$turni = $_POST['turni'] ?? '';
$straordinari = $_POST['straordinari'] ?? '';
$trasferte = $_POST['trasferte'] ?? '';
$minima = $_POST['minima'] ?? '';
$desiderata = $_POST['desiderata'] ?? '';
$identita = $_POST['identita'] ?? '';

$to = 'operai@inoperando.it';
$subject = 'Nuova candidatura da ' . $nome;
$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$message = "NUOVA CANDIDATURA\n\n";
$message .= "Nome: $nome\n";
$message .= "Età: $eta\n";
$message .= "Comune: $comune\n";
$message .= "Telefono: $telefono\n";
$message .= "Email: $email\n\n";
$message .= "Titolo studio: $titolo_studio\n";
$message .= "Anno diploma: $anno_diploma\n";
$message .= "Corsi tecnici: $corsi_tecnici\n";
$message .= "Certificazioni: $certificazioni\n\n";
$message .= "Figure professionali: $figure_str\n\n";
$message .= "Competenze tecniche: $competenze_str\n\n";
$message .= "Anni esperienza: $anni_esperienza\n";
$message .= "Ultimo ruolo: $ultimo_ruolo\n";
$message .= "Mansioni: $mansioni\n";
$message .= "Motivo uscita: $motivo_uscita\n\n";
$message .= "Cosa sai fare: $cosa_sai_fare\n\n";
$message .= "Cosa non vuoi fare: $cosa_non_vuoi_fare\n\n";
$message .= "Disponibilità: inizio $inizio, contratto $contratto, turni $turni, straordinari $straordinari, trasferte $trasferte\n\n";
$message .= "Aspettative: minima $minima, desiderata $desiderata\n\n";
$message .= "Identità professionale: $identita\n";

if (mail($to, $subject, $message, $headers)) {
    header('Location: grazie.html');
} else {
    echo "Errore nell'invio della mail";
}
exit;
?>