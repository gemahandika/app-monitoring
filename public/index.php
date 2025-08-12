<?php

require_once '../app/init.php';


$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home/index';
$url = explode('/', $url);

// Default controller dan method
$controllerName = !empty($url[0]) ? ucfirst($url[0]) : 'Home';
$methodName = isset($url[1]) ? $url[1] : 'index';
$params = array_slice($url, 2);

// Include controller
$controllerFile = '../app/controller/' . $controllerName . '.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName;

    if (method_exists($controller, $methodName)) {
        call_user_func_array([$controller, $methodName], $params);
    } else {
        header('Location: ' . BASE_URL . '/home');
    }
} else {
    header('Location: ' . BASE_URL . '/home');
}
