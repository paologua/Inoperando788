<?php
function calcolaPunteggio($candidato, $azienda) {
    $punteggio = 0;
    
    // Figure professionali (peso 3)
    $figure_cand = json_decode($candidato['figure_professionali'], true) ?: [];
    $figure_az = json_decode($azienda['figure_professionali'], true) ?: [];
    $match_figure = array_intersect($figure_cand, $figure_az);
    $punteggio += count($match_figure) * 3;
    
    // Competenze tecniche (peso 2)
    $comp_cand = json_decode($candidato['competenze_tecniche'], true) ?: [];
    $comp_az = json_decode($azienda['competenze_tecniche'], true) ?: [];
    $match_comp = array_intersect($comp_cand, $comp_az);
    $punteggio += count($match_comp) * 2;
    
    // Luogo (stessa provincia, peso 2)
    if (!empty($candidato['comune']) && !empty($azienda['comune'])) {
        $prov_cand = explode(' ', $candidato['comune'])[0];
        $prov_az = explode(' ', $azienda['comune'])[0];
        if ($prov_cand == $prov_az) $punteggio += 2;
    }
    
    // Disponibilità turni (peso 1)
    if ($candidato['disponibilita_turni'] == 'si' && $azienda['orario_turni'] != 'solo giornaliero') $punteggio += 1;
    
    // Disponibilità straordinari (peso 1)
    if ($candidato['disponibilita_straordinari'] == 'si') $punteggio += 1;
    
    // Disponibilità trasferte (peso 1)
    if ($candidato['disponibilita_trasferte'] == 'si') $punteggio += 1;
    
    return $punteggio;
}

function inviaNotificaMatch($candidato, $azienda, $punteggio) {
    $to = ADMIN_EMAIL;
    $subject = "Nuovo match: " . $azienda['nome_azienda'] . " - " . $candidato['nome'];
    $message = "Candidato: " . $candidato['nome'] . " (" . $candidato['email'] . ")\n";
    $message .= "Azienda: " . $azienda['nome_azienda'] . " (" . $azienda['email_referente'] . ")\n";
    $message .= "Punteggio: " . $punteggio . "\n\n";
    $message .= "https://inoperando.it/admin/dashboard.php";
    mail($to, $subject, $message, "From: " . ADMIN_EMAIL);
}
?>