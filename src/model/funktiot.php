<?php

function liikevoitto($liikevaihto, $materiaalit, $henkilosto, $poistot, $muutkulut) {
    return $liikevaihto-$materiaalit-$henkilosto-$poistot-$muutkulut;
}

function voittoEnnenVeroja ($liikevoitto, $rahoitus) {
    return $liikevoitto-$rahoitus;
}

function tilikaudenVoitto ($voittoEnnenVeroja, $verot) {
    return $voittoEnnenVeroja-$verot;
}

function osaketuotto($tilikaudenVoitto, $osakkeidenMaara) {
    return $tilikaudenVoitto/$osakkeidenMaara;
}

function osakkeetAlussa($sijoitus, $osakehinta) {
    return $sijoitus/$osakehinta;
}

function sipo ($osaketuotto, $osakkeetAlussa, $sijoitus) {
    $tuotto€ = $osaketuotto * $osakkeetAlussa;
    $tuottoPros= (($osaketuotto * $osakkeetAlussa)/$sijoitus)*100;
    $tulos = array($tuotto€, $tuottoPros);
    return $tulos;
}

function tuottoVuosittain ($tuotto€, $osakehinta ) {
    return $tuotto€/$osakehinta;
}

function yhteismaara ($osakkeetAlussa, $uudetOsakkeet) {
    return $osakkeetAlussa + $uudetOsakkeet;
}

?>