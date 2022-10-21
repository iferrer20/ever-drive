<?php 

require './utils.php';

session_start();
define('DRIVES_DIR', '/home/www-data/');
define('DEFAULT_MODULE', 'drive');
define('DEFAULT_ACTION', 'list');

$action = $_POST['action'] ?? DEFAULT_ACTION;
$uri = secure_format(rtrim(substr(urldecode($_SERVER['REQUEST_URI']), 1), '/'));
$uri_arr = explode('/', $uri);
$module = $uri_arr[0];

$path_controller = 'modules/' . $module;
if (!is_dir('modules/' . $module)) {
    $module = DEFAULT_MODULE;
    array_unshift($uri_arr, $module);
    $path_controller =  'modules/' . $module;
}
$file_controller = $path_controller . '/controller.php';

require $file_controller;
$controller_class = $module . 'Controller';
$controller = new $controller_class;
$controller->{$action}();

header('Location: /' . $uri);
?>