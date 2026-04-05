<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contatto.html');
    exit;
}

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$messaggio = $_POST['messaggio'] ?? '';

$to = 'info@inoperando.it';
$subject = 'Nuovo messaggio da ' . $nome;
$body = "Nome: $nome\nEmail: $email\n\nMessaggio:\n$messaggio";
$headers = "From: $email";

mail($to, $subject, $body, $headers);

header('Location: grazie.html');
exit;
?>