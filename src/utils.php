<?php

function secure_format($str) {
    return str_replace('..', '', htmlspecialchars($str));
}

function convert_path($path) {
	$path = explode('/', $path);
	for ($i=0; $i<count($path); $i++) {
		if (!strcmp($path[$i], '..')) {
			unset($path[$i], $path[$i-1]);
		}
	}
	return join('/', $path);
}

function secure_path($root, $str) {
    $path = htmlspecialchars(convert_path($str));
    if (!str_starts_with($path, $root)) {
        var_dump(array(
            "root" => $root,
            "str" => $str,
            "path" => $path
        ));
        throw new Exception();
    }
    return $path;
}

function render($str, $data = array()) {
    global $path_controller;
    require $path_controller . '/views/' . $str . '.php';
    die();
}

?>