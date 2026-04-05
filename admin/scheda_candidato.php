<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: index.php');
    exit;
}
require_once '../includes/config.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM candidati WHERE id = ?");
$stmt->execute([$id]);
$c = $stmt->fetch();
if (!$c) die("Candidato non trovato");

$figure = json_decode($c['figure_professionali'], true) ?: [];
$competenze = json_decode($c['competenze_tecniche'], true) ?: [];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Scheda candidato</title>
    <style>
        body { background: #0F0F0D; color: #F5F0E8; font-family: 'Helvetica Neue', Arial, sans-serif; padding: 40px; }
        .container { max-width: 800px; margin: 0 auto; background: #1A1A17; padding: 30px; border-radius: 8px; }
        h1 { color: #5AA9D9; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #5AA9D9; }
        .back { margin-top: 20px; display: inline-block; background: #5AA9D9; color: #0F0F0D; padding: 8px 16px; border-radius: 4px; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h1>Scheda candidato</h1>
    <div class="field"><span class="label">Nome:</span> <?= htmlspecialchars($c['nome']) ?></div>
    <div class="field"><span class="label">Età:</span> <?= $c['eta'] ?></div>
    <div class="field"><span class="label">Comune:</span> <?= htmlspecialchars($c['comune']) ?></div>
    <div class="field"><span class="label">Email:</span> <?= htmlspecialchars($c['email']) ?></div>
    <div class="field"><span class="label">Telefono:</span> <?= htmlspecialchars($c['telefono']) ?></div>
    <div class="field"><span class="label">Titolo studio:</span> <?= htmlspecialchars($c['titolo_studio']) ?></div>
    <div class="field"><span class="label">Anni esperienza:</span> <?= $c['anni_esperienza'] ?></div>
    <div class="field"><span class="label">Ultimo ruolo:</span> <?= htmlspecialchars($c['ultimo_ruolo']) ?></div>
    <div class="field"><span class="label">Figure professionali:</span> <?= implode(', ', $figure) ?></div>
    <div class="field"><span class="label">Competenze tecniche:</span> <?= implode(', ', $competenze) ?></div>
    <div class="field"><span class="label">Disponibilità turni:</span> <?= $c['disponibilita_turni'] ?></div>
    <div class="field"><span class="label">Disponibilità trasferte:</span> <?= $c['disponibilita_trasferte'] ?></div>
    <div class="field"><span class="label">Identità professionale:</span> <?= nl2br(htmlspecialchars($c['identita_professionale'])) ?></div>
    <a href="dashboard.php" class="back">← Torna alla dashboard</a>
</div>
</body>
</html>