<?php

function lisaaTili ($formdata, $baseurl='') {
    require_once (MODEL_DIR . 'henkilo.php');
    $error = [];

    //tarkistetaan lomakkeen tiedot
    if (!isset($formdata['nimi']) || !$formdata['nimi']) {
        $error['nimi'] = "Anna nimesi.";
    } else {
        if (!preg_match("/^[- '\p{L}]+$/u", $formdata['nimi'])) {
            $error['nimi'] = "Syötä nimesi ilman erikoismerkkejä";
        }
    }

    //tarkistetaan sposti määritelty ja oikea muoto eikä se ole jo varattu
    if (!isset($formdata['email']) || !$formdata['email']) {
        $error['email'] = "Anna sähköpostiosoitteesi.";
    } else {
      if (!filter_var($formdata['email'], FILTER_VALIDATE_EMAIL)) {
        $error['email'] = "Sähköpostiosoite on virheellisessä muodossa";
      } else {
        if (haeHenkiloSahkopostilla($formdata['email'])) {
          $error['email'] = "Sähköpostiosoite on jo käytössä.";
        }         
      }
    }

    // tarkistetaan annetut salasanat ja keskenään samat 
    if (isset($formdata['salasana1']) && $formdata['salasana1'] &&
        isset($formdata['salasana2']) && $formdata['salasana2']) {
        if ($formdata['salasana1'] != $formdata['salasana2']) {
            $error['salasana'] = "Salasanasi eivät täsmää";
        } 
    }else {
        $error['salasana'] = "Syötä salasanasi kahteen kertaan.";
    }

    //jos ei virheitä lisätään henkilö
    if (!$error) {
    
    $nimi = $formdata['nimi'];
    $email = $formdata['email'];
    $salasana = password_hash($formdata['salasana1'], PASSWORD_DEFAULT);

    $idhenkilo = lisaaHenkilo($nimi,$email,$salasana);
    
    //lähetetään aktivointikoodi annettuun sähköpostiosoitteeseen
    if ($idhenkilo) {

    require_once(HELPERS_DIR . "salainen.php");
    $avain = generateActivationCode($email);
    $url = 'https://' . $_SERVER['HTTP_HOST'] . $baseurl . "/vahvista?key=$avain";

    if (paivitaVahvavain($email,$avain) && lahetaVahvavain($email,$url)) {
      return [
        "status" => 200,
        "id"     => $idhenkilo,
        "data"   => $formdata
      ];
    } else {
      return [
        "status" => 500,
        "data"   => $formdata
      ];
    }
  } else {
    return [
      "status" => 500,
      "data"   => $formdata
    ];
  }

} else {

    // Lomaketietojen tarkistuksessa ilmeni virheitä.
    return [
      "status" => 400,
      "data"   => $formdata,
      "error"  => $error
    ];

  }
}

function lahetaVahvavain($email,$url) {
  $message = "Terve!\n\n" . 
             "Olet luonut tilin sijoutuskoneeseen tällä\n" . 
             "sähköpostiosoitteella. Klikkaamalla alla olevaa\n" . 
             "linkkiä vahvistat käyttämäsi sähköpostiosoitteen\n" .
             "ja pääset laskemaan sijoituksiasi koneella.\n\n" . 
             "$url\n\n";
             
  return mail($email,'Sijoituskoneen aktivointilinkki',$message);
}

function lahetaVaihtoavain($email,$url) {
  $message = "Terve!\n\n" .
             "Pyysit uutta salasanaa, klikkaamalla\n" .
             "alla olevaa linkkiä pääset vaihtamaan salasanasi.\n" .
             "Linkki on voimassa 30 minuuttia.\n\n" .
             "$url\n\n";
  return mail($email,'Salasanan vaihto sijoituskonepalveluun',$message);
}

function luoVaihtoavain($email, $baseurl='') {
  require_once(HELPERS_DIR . "salainen.php");
  $avain = generateResetCode($email);
  $url = 'https://' . $_SERVER['HTTP_HOST'] . $baseurl . "/reset?key=$avain";
  require_once(MODEL_DIR . 'henkilo.php');
  if (asetaVaihtoavain($email,$avain) && lahetaVaihtoavain($email,$url)) {
    return [
      "status"   => 200,
      "email"    => $email,
      "resetkey" => $avain
    ];
  } else {
    return [
      "status" => 500,
      "email"   => $email
    ];
  }

}

function resetoiSalasana($formdata, $resetkey='') {
  require_once(MODEL_DIR . 'henkilo.php');
  $error = "";
  if (isset($formdata['salasana1']) && $formdata['salasana1'] &&
      isset($formdata['salasana2']) && $formdata['salasana2']) {
    if ($formdata['salasana1'] != $formdata['salasana2']) {
      $error = "Salasanasi eivät olleet samat!";
    }
  } else {
    $error = "Syötä salasanasi kahteen kertaan.";
  }
  if (!$error) {
    $salasana = password_hash($formdata['salasana1'], PASSWORD_DEFAULT);

    $rowcount = vaihdaSalasanaAvaimella($salasana,$resetkey);
    if ($rowcount) {

      return [
        "status"   => 200,
        "resetkey" => $resetkey
      ];

    } else {

      return [
        "status"   => 500,
        "resetkey" => $resetkey
      ];

    }    

  } else {
    
    return [
      "status"   => 400,
      "resetkey" => $resetkey,
      "error"    => $error
    ];

  }

}

?>