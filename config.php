<?php
define('BASE_URL', '/web_escolar_php_final_actualizado/');
define('FULL_URL', 'http://localhost' . BASE_URL);

function redirigir($ruta) {
    header("Location: " . BASE_URL . $ruta);
    exit();
}
?>
