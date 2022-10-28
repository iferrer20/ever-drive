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

function format_bytes($bytes, $decimals = 2) {
    $unit_list = array('B', 'KB', 'MB', 'GB', 'PB');
  
    if ($bytes == 0) {
      return $bytes . ' ' . $unit_list[0];
    }
  
    $unit_count = count($unit_list);
    for ($i = $unit_count - 1; $i >= 0; $i--) {
      $power = $i * 10;
      if (($bytes >> $power) >= 1)
        return round($bytes / (1 << $power), $decimals) . ' ' . $unit_list[$i];
    }
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

function uri($i = -1) {
    global $uri;
    global $uri_arr;
    if ($i < 0) {
        return $uri;
    }
    return $uri_arr[$i] ?? '';
}

function getfile($str) {
    return $_FILES[$str] ?? NULL;
}

function referrer() {
    return $_SERVER['HTTP_REFERER'];
}

?>