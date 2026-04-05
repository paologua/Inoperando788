<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: index.php');
    exit;
}
require_once '../includes/config.php';
require_once '../includes/functions.php';

echo "<!DOCTYPE html><html><head><title>Matching</title><style>body{background:#0F0F0D;color:#F5F0E8;font-family:Arial;padding:40px;}</style></head><body>";

echo "<h1>Matching automatico</h1>";

// Recupera tutti i candidati
$candidati = $pdo->query("SELECT * FROM candidati")->fetchAll();
// Recupera tutte le aziende
$aziende = $pdo->query("SELECT * FROM richieste_aziende")->fetchAll();

echo "<p>Candidati trovati: " . count($candidati) . "</p>";
echo "<p>Aziende trovate: " . count($aziende) . "</p>";

$match_creati = 0;

foreach ($aziende as $azienda) {
    foreach ($candidati as $candidato) {
        $punteggio = calcolaPunteggio($candidato, $azienda);
        
        if ($punteggio >= 3) { // soglia minima
            // Controlla se esiste già
            $check = $pdo->prepare("SELECT id FROM match_db WHERE candidato_id = ? AND azienda_id = ?");
            $check->execute([$candidato['id'], $azienda['id']]);
            if (!$check->fetch()) {
                $stmt = $pdo->prepare("INSERT INTO match_db (candidato_id, azienda_id, punteggio) VALUES (?, ?, ?)");
                $stmt->execute([$candidato['id'], $azienda['id'], $punteggio]);
                $match_creati++;
                echo "✅ Match: {$candidato['nome']} - {$azienda['nome_azienda']} (punteggio: $punteggio)<br>";
            }
        }
    }
}

if ($match_creati == 0) {
    echo "<p>Nessun nuovo match trovato.</p>";
} else {
    echo "<p><strong>Creati $match_creati nuovi match!</strong></p>";
}

echo '<br><a href="dashboard.php" style="color:#5AA9D9;">← Torna alla dashboard</a>';
echo "</body></html>";
?>