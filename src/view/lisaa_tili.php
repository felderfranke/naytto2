<?php $this->layout('template', ['title' => 'Luo uusi tili'])?>

<h1>Luo uusi tili</h1>

<form action="" method="POST">
    <div>
        <label for="nimi">Nimi:</label>
        <input id="nimi" type="text" name="nimi" value="<?= getValue($formdata,'nimi') ?>">
        <div class="error"><span><?= getValue($error,'nimi'); ?></span></div>
    </div>

    <div>
        <label>Sähköposti:</label>
        <input type="text" name="email" value="<?= getValue($formdata,'email') ?>">
        <div class="error"><?= getValue($error,'email'); ?></div>
    </div>

    <div>
        <label>Salasana:</label>
        <input type="password" name="salasana1">
        <div class="error"><?= getValue($error,'salasana'); ?></div>
    </div>
    <div>
        <label>Salasana uudelleen:</label>
        <input type="password" name="salasana2">
    </div>

    <div>
        <input type="submit" name="laheta" value="Luo tili">
    </div>
</form>

<!--
    Tämä sivu on muuten samanlainen, kuin aikaisemmin, paitsi lomakekenttien arvot määritellään $formdata-taulukon 
    arvoista ja kentän alle muodostetaan div-elementti, joka kuuluu error-luokkaan ja siihen sijoitetaan mahdollinen 
    virheteksti. Huomaa, että tämä div-elementti luodaan riippumatta siitä oliko kentässä virhe vai ei. 
    Jos kentässä ei ollut virhettä, niin elementti on tyhjä ja ei siksi näy käyttäjälle.