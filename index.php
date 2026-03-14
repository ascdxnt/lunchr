<?php
ob_start();
session_start();
require_once './Core/DefaultRoute.php';
require_once './Core/Router.php';

require_once "./Controller/IndexController.php";

$router = new Router();
$dir = null;
if (isset($_GET['dir'])) $dir = $_GET['dir'];

if (isset($_GET['controller'])) {
	$controller = $router->LoadController($_GET['controller'], $dir);
	if (isset($_GET['action'])) {
		if (isset($_GET['id'])) {
			$router->LoadAction($controller, $_GET['action'], $_GET['id']);
		} else {
			$router->LoadAction($controller, $_GET['action']);
		}
	} else {
		$router->LoadAction($controller, DEFAULT_ACTION);
	}
} else {
	$controller = $router->LoadController(DEFAULT_CONTROLLER, null);
	$defaultAction = DEFAULT_ACTION;
	$controller->$defaultAction();
}
