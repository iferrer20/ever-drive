<?php 

session_start();
define('DRIVES_DIR', '/home/www-data/');
define('DEFAULT_MODULE', 'drive');
define('DEFAULT_ACTION', 'list');

$action = $_POST['action'] ?? DEFAULT_ACTION;
$uri = htmlspecialchars(rtrim(substr(str_replace('..', '', urldecode($_SERVER['REQUEST_URI'])), 1) . '/', '/'));
$uri_arr = explode('/', $uri);
$module = $uri_arr[0];

file_controller:
$file_controller = 'modules/' . $module . '/controller.php';
if (!file_exists($file_controller)) {
    $module = DEFAULT_MODULE;
    array_unshift($uri_arr, $module);
    goto file_controller;
}

require $file_controller;
$controller_class = $module . 'Controller';
$controller = new $controller_class;
$controller->{$action}();

?>