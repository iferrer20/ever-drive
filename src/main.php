<?php 

require './utils.php';

session_start();
define('DRIVES_DIR', '/home/www-data/');
define('DEFAULT_MODULE', 'drive');

$uri = secure_format(rtrim(substr(urldecode($_SERVER['REQUEST_URI']), 1), '/'));
$uri_arr = explode('/', $uri);
$module = $uri_arr[0];
$action = $_POST['action'] ?? $uri_arr[1] ?? '';

$path_controller = 'modules/' . $module;
if (!is_dir('modules/' . $module) || $module == 'drive') {
    $module = DEFAULT_MODULE;
    array_unshift($uri_arr, $module);
    $path_controller =  'modules/' . $module;
}
$file_controller = $path_controller . '/controller.php';

require $file_controller;
$controller_class = $module . 'Controller';
$controller = new $controller_class;
if (method_exists($controller, $action)) {
    $controller->{$action}();
} else if (property_exists($controller, 'default_action')) {
    $controller->{$controller::$default_action}();
} 

header('Location: /' . $uri);
?>