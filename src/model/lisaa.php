<?php

require_once HELPERS_DIR . 'DB.php';

function lisaaTiedot ($nimi, $liikevaihto, $materiaalit, $henkilosto, $poistot, $muutkulut, 
                        $rahoitus, $verot, $osakkeidenMaara, $osakehinta, $sijoitus) {
    return DB::run('INSERT INTO sijoitus (nimi, liikevaihto, materiaalit, henkilosto, poistot, muutkulut, 
            rahoitus, verot, kokonaismaara, osakehinta, sijoitus) VALUE (?,?,?,?,?,?,?,?,?,?,?);',
            [$nimi, $liikevaihto, $materiaalit, $henkilosto, $poistot, $muutkulut, 
            $rahoitus, $verot, $osakkeidenMaara, $osakehinta, $sijoitus]);
    #return DB::lastInsertedId(); #????
}

?>