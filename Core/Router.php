<?php

class Router
{
	function LoadController($controller, $dir)
	{
		$controllerName = ucwords($controller) . "Controller";

		if ($dir == null) {
			$controllerFile = './Controller/' . ucwords($controller) . 'Controller.php';
		} else {
			$controllerFile = './Controller/' . $dir . '/' . ucwords($controller) . 'Controller.php';
		}

		if (!is_file($controllerFile)) {
			$controllerFile = './Controller/IndexController.php';
			$controllerName = 'IndexController';
		}

		require_once $controllerFile;
		$control = new $controllerName();
		return $control;
	}

	function LoadAction($controller, $action, $id = null)
	{
		if (isset($action) && method_exists($controller, $action)) {
			if ($id == null) {
				$controller->$action();
			} else {
				$controller->$action($id);
			}
		} else {
			require_once "./Controller/IndexController.php";
			$controller = new IndexController();
			$controller->Index();
		}
	}
}
