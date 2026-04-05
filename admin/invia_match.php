<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: index.php');
    exit;
}
require_once '../includes/config.php';

$match_id = $_GET['match_id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT m.*, 
           c.nome as candidato_nome, c.email as candidato_email,
           a.nome_azienda, a.email_referente, a.referente
    FROM match_db m
    LEFT JOIN candidati c ON m.candidato_id = c.id
    LEFT JOIN richieste_aziende a ON m.azienda_id = a.id
    WHERE m.id = ?
");
$stmt->execute([$match_id]);
$match = $stmt->fetch();

if (!$match) die("Match non trovato");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $messaggio_candidato = $_POST['messaggio_candidato'];
    $messaggio_azienda = $_POST['messaggio_azienda'];
    
    mail($match['candidato_email'], "Proposta di lavoro da " . $match['nome_azienda'], $messaggio_candidato, "From: " . ADMIN_EMAIL);
    mail($match['email_referente'], "Candidato per la tua ricerca", $messaggio_azienda, "From: " . ADMIN_EMAIL);
    
    $upd = $pdo->prepare("UPDATE match_db SET gestito = 1 WHERE id = ?");
    $upd->execute([$match_id]);
    
    $success = "Proposte inviate con successo!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invia proposta</title>
    <style>
        body { background: #0F0F0D; color: #F5F0E8; font-family: Arial; padding: 40px; }
        .container { max-width: 800px; margin: 0 auto; background: #1A1A17; padding: 30px; border-radius: 8px; }
        textarea, input { width: 100%; margin: 10px 0; padding: 10px; background: #222; border: 1px solid #333; color: #fff; }
        button { background: #5AA9D9; border: none; padding: 10px 20px; cursor: pointer; }
        .back { display: inline-block; margin-top: 20px; color: #5AA9D9; }
    </style>
</head>
<body>
<div class="container">
    <h2>Invia proposta match</h2>
    <p><strong>Candidato:</strong> <?= htmlspecialchars($match['candidato_nome']) ?> (<?= $match['candidato_email'] ?>)</p>
    <p><strong>Azienda:</strong> <?= htmlspecialchars($match['nome_azienda']) ?> (<?= $match['email_referente'] ?>)</p>
    
    <?php if (isset($success)) echo "<p style='color:green'>$success</p>"; ?>
    
    <form method="post">
        <label>Messaggio per il candidato:</label>
        <textarea name="messaggio_candidato" rows="6" required>Buongiorno,
abbiamo trovato un'azienda interessata al tuo profilo: <?= $match['nome_azienda'] ?>.
Contattaci per maggiori dettagli.

Team Inoperando</textarea>
        
        <label>Messaggio per l'azienda:</label>
        <textarea name="messaggio_azienda" rows="6" required>Buongiorno,
abbiamo trovato un candidato interessante per la tua ricerca: <?= $match['candidato_nome'] ?>.
Contattaci per maggiori dettagli.

Team Inoperando</textarea>
        
        <button type="submit">Invia proposte</button>
    </form>
    
    <a href="dashboard.php" class="back">← Torna alla dashboard</a>
</div>
</body>
</html>