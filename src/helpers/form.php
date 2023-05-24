<?php

function siistiTiedot ($lista=[]) {
    $tulos = array();
    foreach ($lista as $avain => $arvo) {
        $siistitty = trim($arvo);   #poistaa tyhjät merkit alusta ja lopusta mm. sarkain, välilyönti ja rivinvaihto
        $siistitty = stripslashes($siistitty); #poistaa merkkien edestä / ohjausmerkin
        $tulos[$avain] = $siistitty;
    }
    return $tulos;
}

#taulukkotietojen tulostus
function getValue($arvot, $avain) {
    if (array_key_exists($avain, $arvot)) {
        return htmlspecialchars($arvot[$avain]);
    }else {
        return null;
    }
}

?>