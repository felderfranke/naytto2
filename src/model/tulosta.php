<?php

require_once HELPERS_DIR . 'DB.php';
function haeTiedot() {
    return DB::run('SELECT * FROM sijoitus;')->fetchAll();
}

function haeYritys($yritys) {
    return DB::run('SELECT * FROM sijoitus WHERE nimi = ?;' , [$yritys])->fetchAll();
}
?>