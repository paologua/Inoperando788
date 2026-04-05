<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: index.php');
    exit;
}
require_once '../includes/config.php';

// Recupera candidati
$candidati = $pdo->query("SELECT id, nome, email, comune, data_inserimento FROM candidati ORDER BY data_inserimento DESC")->fetchAll();

// Recupera aziende
$aziende = $pdo->query("SELECT id, nome_azienda, email_referente, comune, data_inserimento FROM richieste_aziende ORDER BY data_inserimento DESC")->fetchAll();

// Recupera match
$matches = $pdo->query("
    SELECT m.*, 
           c.nome as candidato_nome,
           a.nome_azienda
    FROM match_db m
    LEFT JOIN candidati c ON m.candidato_id = c.id
    LEFT JOIN richieste_aziende a ON m.azienda_id = a.id
    ORDER BY m.punteggio DESC, m.data_match DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #0F0F0D; color: #F5F0E8; font-family: 'Helvetica Neue', Arial, sans-serif; padding: 40px; }
        h1, h2 { font-family: 'Bebas Neue', sans-serif; color: #5AA9D9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 40px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.07); }
        th { color: #5AA9D9; }
        a { color: #5AA9D9; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .btn { background: #5AA9D9; color: #0F0F0D; padding: 6px 12px; border-radius: 4px; text-decoration: none; }
        .btn:hover { background: #ff5c12; text-decoration: none; }
        .logout { float: right; background: #333; padding: 8px 16px; border-radius: 4px; }
        .section { margin-bottom: 50px; }
        .match-btn { background: #5AA9D9; color: #0F0F0D; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; margin-left: 10px; }
        .match-btn:hover { background: #ff5c12; }
    </style>
</head>
<body>
    <a href="logout.php" class="logout">Esci</a>
    <h1>Dashboard Admin</h1>

    <!-- CANDIDATI -->
    <div class="section">
        <h2>Candidati</h2>
        <table>
            <thead>
                <tr><th>Nome</th><th>Email</th><th>Comune</th><th>Data</th><th>Azioni</th></tr>
            </thead>
            <tbody>
                <?php if (count($candidati) == 0): ?>
                    <tr><td colspan="5">Nessun candidato</td></tr>
                <?php else: ?>
                    <?php foreach ($candidati as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['nome']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['comune']) ?></td>
                        <td><?= $c['data_inserimento'] ?></td>
                        <td><a href="scheda_candidato.php?id=<?= $c['id'] ?>">Vedi scheda</a></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- AZIENDE -->
    <div class="section">
        <h2>Aziende</h2>
        <table>
            <thead>
                <tr><th>Azienda</th><th>Email</th><th>Comune</th><th>Data</th><th>Azioni</th>
            </thead>
            <tbody>
                <?php if (count($aziende) == 0): ?>
                    <tr><td colspan="5">Nessuna azienda</td></tr>
                <?php else: ?>
                    <?php foreach ($aziende as $a): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['nome_azienda']) ?></td>
                        <td><?= htmlspecialchars($a['email_referente']) ?></td>
                        <td><?= htmlspecialchars($a['comune']) ?></td>
                        <td><?= $a['data_inserimento'] ?></td>
                        <td><a href="scheda_azienda.php?id=<?= $a['id'] ?>">Vedi scheda</a></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- MATCH -->
    <div class="section">
        <h2>
            Match automatici 
            <a href="match.php" class="match-btn">Ricalcola match</a>
        </h2>
        <table>
            <thead>
                <tr><th>Candidato</th><th>Azienda</th><th>Punteggio</th><th>Data</th><th>Azioni</th>
            </thead>
            <tbody>
                <?php if (count($matches) == 0): ?>
                    <tr><td colspan="5">Nessun match. Clicca "Ricalcola match" per generare.</td></tr>
                <?php else: ?>
                    <?php foreach ($matches as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['candidato_nome'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($m['nome_azienda'] ?? 'N/A') ?></td>
                        <td><?= $m['punteggio'] ?></td>
                        <td><?= $m['data_match'] ?></td>
                        <td>
                            <a href="scheda_candidato.php?id=<?= $m['candidato_id'] ?>">Candidato</a> |
                            <a href="scheda_azienda.php?id=<?= $m['azienda_id'] ?>">Azienda</a> |
                            <a href="invia_match.php?match_id=<?= $m['id'] ?>" class="btn">Invia proposta</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>