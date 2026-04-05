<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: index.php');
    exit;
}
require_once '../includes/config.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM richieste_aziende WHERE id = ?");
$stmt->execute([$id]);
$a = $stmt->fetch();
if (!$a) die("Azienda non trovata");

$figure = json_decode($a['figure_professionali'], true) ?: [];
$competenze = json_decode($a['competenze_tecniche'], true) ?: [];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Scheda azienda</title>
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
    <h1>Scheda azienda</h1>
    <div class="field"><span class="label">Azienda:</span> <?= htmlspecialchars($a['nome_azienda']) ?></div>
    <div class="field"><span class="label">Partita IVA:</span> <?= htmlspecialchars($a['piva']) ?></div>
    <div class="field"><span class="label">Comune:</span> <?= htmlspecialchars($a['comune']) ?></div>
    <div class="field"><span class="label">Referente:</span> <?= htmlspecialchars($a['referente']) ?></div>
    <div class="field"><span class="label">Telefono:</span> <?= htmlspecialchars($a['telefono_referente']) ?></div>
    <div class="field"><span class="label">Email:</span> <?= htmlspecialchars($a['email_referente']) ?></div>
    <div class="field"><span class="label">Ruolo richiesto:</span> <?= htmlspecialchars($a['ruolo_richiesto']) ?></div>
    <div class="field"><span class="label">Numero persone:</span> <?= $a['numero_persone'] ?></div>
    <div class="field"><span class="label">Descrizione:</span> <?= nl2br(htmlspecialchars($a['descrizione_lavoro'])) ?></div>
    <div class="field"><span class="label">Figure richieste:</span> <?= implode(', ', $figure) ?></div>
    <div class="field"><span class="label">Competenze richieste:</span> <?= implode(', ', $competenze) ?></div>
    <div class="field"><span class="label">Luogo lavoro:</span> <?= htmlspecialchars($a['luogo_lavoro']) ?></div>
    <div class="field"><span class="label">Inizio richiesto:</span> <?= htmlspecialchars($a['inizio_richiesto']) ?></div>
    <div class="field"><span class="label">Durata:</span> <?= htmlspecialchars($a['durata']) ?></div>
    <div class="field"><span class="label">Orario/turni:</span> <?= htmlspecialchars($a['orario_turni']) ?></div>
    <div class="field"><span class="label">Contratto:</span> <?= htmlspecialchars($a['contratto_previsto']) ?></div>
    <div class="field"><span class="label">Retribuzione:</span> <?= htmlspecialchars($a['retribuzione_indicativa']) ?></div>
    <div class="field"><span class="label">Urgenza:</span> <?= htmlspecialchars($a['urgenza']) ?></div>
    <div class="field"><span class="label">Note:</span> <?= nl2br(htmlspecialchars($a['note'])) ?></div>
    <a href="dashboard.php" class="back">← Torna alla dashboard</a>
</div>
</body>
</html>