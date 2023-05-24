<?php $this->layout('template', ['title' => 'Salasana unohtunut?']) ?>

<h1>Oletko unohtanut salasanasi?</h1>

<p>Ei hätää, voit vaihtaa unohtuneen salasan tilaamalla vahvistuslinkin sähköpostiisi.</p>

<form action="" method="POST">
  <div>
    <label for="email">Sähköposti:</label>
    <input id="email" type="email" name="email">
  </div>
  <div>
    <input type="submit" name="laheta" value="Lähetä">
  </div>
</form>