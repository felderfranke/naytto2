<?php

require_once HELPERS_DIR . 'DB.php';

function lisaaHenkilo($nimi,$email,$salasana) {
  DB::run('INSERT INTO henkilo2 (nimi, email, salasana) VALUE  (?,?,?);',[$nimi,$email,$salasana]);
  return DB::lastInsertId();
}

function haeHenkilo($email) {
  return DB::run('SELECT * FROM henkilo2 WHERE email = ?;', [$email])->fetch();
}
  
function haeHenkiloSahkopostilla($email) {
  return DB::run('SELECT * FROM henkilo2 WHERE email = ?;', [$email])->fetchAll();
}

function paivitaVahvavain($email,$avain) {
  return DB::run('UPDATE henkilo2 SET vahvavain = ? WHERE email = ?', [$avain,$email])->rowCount();
}

function vahvistaTili($avain) {
  return DB::run('UPDATE henkilo2 SET vahvistettu = TRUE WHERE vahvavain = ?', [$avain])->rowCount();
}
  
function haeTiliSahkopostilla($email) {
  return DB::run('SELECT * FROM henkilo2 WHERE email =?;', [$email])->fetchAll();
}
  
function asetaVaihtoavain($email,$avain) {
  return DB::run('UPDATE henkilo2 SET nollausavain = ?, nollausaika = NOW() + INTERVAL 30 MINUTE WHERE email = ?', [$avain,$email])->rowCount();
}

function tarkistaVaihtoavain($avain) {
  return DB::run('SELECT nollausavain, nollausaika-NOW() AS aikaikkuna FROM henkilo2 WHERE nollausavain = ?', [$avain])->fetch();
}

function vaihdaSalasanaAvaimella($salasana,$avain) {
  return DB::run('UPDATE henkilo2 SET salasana = ?, nollausavain = NULL, nollausaika = NULL WHERE nollausavain = ?', [$salasana,$avain])->rowCount();
}

?>