<?php $this->layout('template', ['title' => 'Kirjautuminen']) ?>

<h1>Kirjaudu sisään</h1>

<form action="https://<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>" method="POST">
  <div>
    <label>Sähköposti:</label>
    <input type="text" name="email">
  </div>
  <div>
    <label>Salasana:</label>
    <input type="password" name="salasana">
  </div>
  <div class="error"><?= getValue($error,'virhe'); ?></div>
  <div>
    <input type="submit" name="laheta" value="Kirjaudu">
  </div>
  <div class="info">
  <a href="tilaa_vaihtoavain">Salasana unohtunut?</a>.
</div>
</form>