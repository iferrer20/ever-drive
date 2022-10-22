<?php

class BadRequestException extends Exception {
    public function __construct($message) {
        http_response_code(400);
        parent::__construct($message);
    }
}

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

function redirect($path) {
    header('Location: /' . $path);
    die();
}

function is_post() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function post($str) {
    return $_POST[$str] ?? NULL;
}

function session($str) {
    return $_SESSION[$str] ?? NULL;
}

function session_set($str, $value) {
    $_SESSION[$str] = $value;
}

function uri($i) {
    global $uri_arr;
    return $uri_arr[$i];
}

?>