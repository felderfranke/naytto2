<?php $this->layout('template', ['title' => 'Tulosta tiedot']); ?>

<div class="kirjautuminen">
<br><br>
<h2>Osake- ja sijoitustiedot</h2>
<br>
<h3> Valitse vertailtavat yritykset:</h3>
<br>

<?php
require_once MODEL_DIR . 'tulosta.php';
require_once MODEL_DIR . 'funktiot.php'; 

$firmat = haeTiedot();

?>

<div class="yritykset">
<form method="post" action="">
    
    <?php
    foreach ($firmat as $firma) { #checkbox täpätyt listana 'nimi[]' jotta toimii foreach loopissa.
        echo "<div class='checkbox'>
        <input type='checkbox' name='nimi[]' id='nimi[]' value='$firma[nimi]'> $firma[nimi]
        </div>";
    }
    ?> 

<br>
<div class="vertaa">
<input type="submit" value="Alkusijoitukset" name="tulosta"><br>
<input type="submit" value="5v tuotto" name="tulosta">
</div>
</form> 
</div>
<br>

<?php

if (isset($_POST['tulosta']) AND isset($_POST['nimi']))  { #nappia painettu JA RUUTU TÄPÄTTY
     $lomake = $_POST['tulosta'];
    
    switch($lomake){
        case 'Alkusijoitukset':
            $valitut = []; #tästä ajetaan tulostustiedot
        
            foreach ($_POST['nimi'] as $yritys) {
                $valitut = array_merge($valitut, haeYritys($yritys)); #täpätyt tiedot listaan
                
            }

            $osTuottoLista = array();  #kasataan kaikkien yritysten ekat osaketuotot PER OSAKE
            $osTuotto€ = array();
            $osTuottoPros = array();
            $maara = array();
            
            foreach ($valitut as $arvo) {
                $liikevoitto = liikevoitto($arvo['liikevaihto'], $arvo['materiaalit'], $arvo['henkilosto'], $arvo['poistot'], $arvo['muutkulut']);
                $voittoEnnenVeroja = voittoEnnenVeroja($liikevoitto, $arvo['rahoitus']);
                $tilikaudenVoitto = tilikaudenVoitto($voittoEnnenVeroja, $arvo['verot']);
                $osaketuotto = osaketuotto($tilikaudenVoitto, $arvo['kokonaismaara']); #tilikaudenvoitto/osakkeidenmaara
                array_push($osTuottoLista,$osaketuotto);
                $omatOsakkeetAlussa = osakkeetAlussa($arvo['sijoitus'], $arvo['osakehinta']); #sijoiitus/osakehinta
                $tulos = sipo($osaketuotto, $omatOsakkeetAlussa, $arvo['sijoitus']); #palauttaa listan $tulos jossa tuotto€ja tuottoPros
                $tuotto€ = $tulos[0]; #poimitaan listasta eka indeksi 
                $tuottoPros = $tulos[1]; #toinen indeksi
                array_push($osTuotto€, $tuotto€); #eurotuotto listalle
                array_push($osTuottoPros, $tuottoPros); #%tuotto listalle
                $uudetOsakkeet = tuottoVuosittain($tuotto€, $arvo['osakehinta']); #lasketaan uusien osakkeiden määrä
                array_push($maara, $uudetOsakkeet); #lisätään uusien osakkeiden määrä listalle
                $yhtmaara = yhteismaara($omatOsakkeetAlussa, $uudetOsakkeet); #lasketaan sijoittajan osakkeiden yhteismäärä   
            }
            if (COUNT($maara)>5){
                echo "Ole hyvä ja valitse korkeintaan 5 tulostettavaa yritystä";
                break;
            }
            echo "<div class='otsake'>TIEDOT OSAKKEISTA</div>";
            echo "<br>";
            echo "<table>";
            echo "<tr>";
            echo "<th></th>";
            
            foreach ($valitut as $arvo) {
            echo "<th> $arvo[nimi] </th>"; 
            }
            echo "</tr>";

            echo "<tr>";
            echo "<td>Osakkeiden kokonaismäärä kpl</td>";
            foreach ($valitut as $arvo) {
            echo "<td> $arvo[kokonaismaara] </td>";
            } 
            echo "</tr>";

            echo "<tr>";
            echo "<td>Osakkeen hinta €/osake</td>";
            foreach ($valitut as $arvo) {
            echo "<td> $arvo[osakehinta] </td>";
            } 
            echo "</tr>";

            echo "<tr>";
            echo "<td>Osakketuotto €/osake</td>";
            for ($i=0; $i<COUNT($valitut); $i++) {
            echo "<td>" . ROUND($osTuottoLista[$i],2) . "</td>";
            } 
            echo "</tr>";
            echo "</table>";
            echo "<br>";
        
            echo "SIJOITUSLASKURI";
            echo "<table>";
            echo "<tr>";
            echo "<th></th>";
            foreach ($valitut as $arvo) {
            echo "<th> $arvo[nimi] </th>"; 
            }
            echo "</tr>";

            echo "<tr>";
            echo "<td>Sijoitettava summa €</td>";
            foreach ($valitut as $arvo) {
            echo "<td>$arvo[sijoitus] </td>";
            } 
            echo "</tr>";

            echo "<tr>";
            echo "<td>Sijlituksella saadut osakkeet kpl</td>";
            foreach ($valitut as $arvo) {
            echo "<td>" . ROUND($arvo['sijoitus']/$arvo['osakehinta'],2) . "</td>";
            } 
            echo "</tr>";
            echo "</table>";
            echo "<br>";

            echo "SIJOITETUN PÄÄOMAN TUOTTO";
            echo "<table>";
            echo "<tr>";
            echo "<th></th>";
            foreach ($valitut as $arvo) {
            echo "<th> $arvo[nimi] </th>"; 
            }
            echo "</tr>";

            echo "<tr>";
            echo "<td>Tuotto €</td>";
            for ($i=0; $i<COUNT($valitut); $i++) {
                echo "<td>" . ROUND($osTuotto€[$i],2) . "</td>";
                }
            echo "</tr>";

            echo "<tr>";
            echo "<td>Tuotto suhteessa sijoitukseen %</td>";
            for ($i=0; $i<COUNT($valitut); $i++) {
                echo "<td>" . ROUND($osTuottoPros[$i],2) . "</td>";
                }
            echo "</tr>";
            echo "</table>";
            echo "<br>";
            break;

        case '5v tuotto':
            $valitut = array();

            foreach ($_POST['nimi'] as $yritys) {
                $valitut = array_merge($valitut, haeYritys($yritys)); #täpätyt tiedot listaan
            }   
        
            echo "<div class='otsake'>TUOTTOJEN SIJOITUS VUOSI VUODELTA</div>";
            echo "<hr>";

            foreach ($valitut as $arvo) {
                $maara2 = array();
                $euro = array();
                $pros= array();

                $liikevoitto = liikevoitto($arvo['liikevaihto'], $arvo['materiaalit'], $arvo['henkilosto'], $arvo['poistot'], $arvo['muutkulut']);
                $voittoEnnenVeroja = voittoEnnenVeroja($liikevoitto, $arvo['rahoitus']);
                $tilikaudenVoitto = tilikaudenVoitto($voittoEnnenVeroja, $arvo['verot']);
                $osaketuotto = osaketuotto($tilikaudenVoitto, $arvo['kokonaismaara']); #tilikaudenvoitto/osakkeidenmaara
                $omatOsakkeetAlussa = osakkeetAlussa($arvo['sijoitus'], $arvo['osakehinta']); #sijoiitus/osakehinta
                $tulos = sipo($osaketuotto, $omatOsakkeetAlussa, $arvo['sijoitus']); #palauttaa listan $tulos jossa tuotto€ja tuottoPros
                $tuotto€ = $tulos[0]; #poimitaan listasta eka indeksi 
                $tuottoPros = $tulos[1]; #toinen indeksi
                $uudetOsakkeet = tuottoVuosittain($tuotto€, $arvo['osakehinta']); #lasketaan uusien osakkeiden määrä
                $yhtmaara = yhteismaara($omatOsakkeetAlussa, $uudetOsakkeet); #lasketaan sijoittajan osakkeiden yhteismäärä  
                
                if (COUNT($arvo)>5){
                    echo "Ole hyvä ja valitse korkeintaan 5 tulostettavaa yritystä";
                    break;
                }

                echo "<table>";
                echo "<tr>";
                    echo "<th>$arvo[nimi]</th>";
                        for ($i=2; $i<6; $i++) {   #otsikot, nimi, vuodet
                            echo "<th>$i. vuosi</th>"; 
                        }
                echo "</tr>";

                echo "<tr>";
                echo "<td>Edellisen vuoden tuotolla hankitut osakkeet (kpl)</td>";
                for ($i=0; $i<4; $i++) {
                    array_push($maara2, $uudetOsakkeet);
                    echo "<td>" . ROUND($maara2[$i],2) . "</td>";
                    $tulos = sipo($osaketuotto, $yhtmaara, $arvo['sijoitus']);
                    array_push($euro, $tulos[0]);    # vai $tuotto€ = ROUND($tulos[0], 2); array_push($euro, $tuotto€);    
                    array_push($pros, $tulos[1]);  #vai $tuottoPros= ROUND($tulos[1], 2); array_push($pros, $tuottoPros);
                    $uudetOsakkeet = tuottoVuosittain($tulos[0], $arvo['osakehinta']); 
                    $yhtmaara += $uudetOsakkeet;
                }
                echo "</tr>";

                echo "<tr>";
                echo "<td>Osakkeiden yhteismäärä (kpl)</td>"; 
                $yht = $omatOsakkeetAlussa;
                for ($i=0; $i<4; $i++) {
                    $yht += $maara2[$i];
                    echo "<td>" . ROUND($yht,2) . "</td>";
                }
                echo "</tr>";

                echo "<tr>";
                echo "<td>Tuotto (€)</td>";
                for ($i=0; $i<4; $i++) {
                    echo "<td>" . ROUND($euro[$i],2) . "</td>";
                }
                echo "</tr>";

                echo "<tr>";
                echo "<td>Tuotto suhteessa alkusijoitukseen (%)</td>";
                for ($i=0; $i<4; $i++) {
                    echo "<td>" . ROUND($pros[$i],2) . "</td>";
                }
                echo "</tr>";
                echo "</table>"; 
                }
                break;
                
        }
} else { 
    if (isset($_POST['tulosta']) AND !isset($_POST['nimi'])){#nappia painettu mutta ruutua ei ole täpätty
        echo "<h4>Valitse ainakin yksi vaihtoehto</h4>";
}
}

?>