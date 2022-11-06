<?php 

session_start();

define('DATA_DIR', '/home/www-data/');
define('DRIVES_DIR', DATA_DIR . 'drives/');
define('PROFILES_DIR', DATA_DIR . 'profiles/');
define('DEFAULT_MODULE', 'drive');

require './db.php';
require './utils.php';

if (!is_dir(DRIVES_DIR)) {
    mkdir(DRIVES_DIR);
}
if (!is_dir(PROFILES_DIR)) {
    mkdir(PROFILES_DIR);
}

$uri = htmlspecialchars(rtrim(substr(urldecode($_SERVER['REQUEST_URI']), 1), '/'), ENT_QUOTES, 'UTF-8');
$uri_arr = explode('/', $uri);
$module = $uri_arr[0];

$path_controller = 'modules/' . $module;
if (!is_dir('modules/' . $module)) {
    $module = DEFAULT_MODULE;
    array_unshift($uri_arr, $module);
    $path_controller =  'modules/' . $module;
}

$file_controller = $path_controller . '/controller.php';
$action = $_POST['action'] ?? ($module == 'drive' ? 'read' : $uri_arr[1] ?? '');

require $file_controller;
$controller_class = $module . 'Controller';
$controller = new $controller_class;
if (method_exists($controller, $action)) {
    $controller->{$action}();
} else {
    require './page404.php';
    die();
}
header('Location: ' . referrer());
?>