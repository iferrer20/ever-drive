<?php

function secure_format($str) {
    return str_replace('..', '', htmlspecialchars($str));
}

function render($str, $data = array()) {
    global $path_controller;
    require $path_controller . '/views/' . $str . '.php';
    die();
}

?>