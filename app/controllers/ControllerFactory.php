<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Controllers\HomeController;
use App\Views\BrowserView;

class ControllerFactory
{
	public static function create(string $name, View $view): ?Controller
	{
		switch ($name) {
			case 'home': return new HomeController($view);
			default: return null;
		}
	}

	public static function createCallbackFromString(
		string $controllerDotMethod
	): array
	{
		$view = new BrowserView(
			BASE_DIR . '/public/templates/',
			'layouts/default',
			'php'
		);
		[$controller, $method] = explode('.', $controllerDotMethod);
		$controller = '\\App\\Controllers\\' . $controller;

		if (!class_exists($controller)) {
			throw new \Exception("Class '$controller' does not exist.");
		}
		elseif (!method_exists($controller, $method)) {
			throw new \Exception(
				"Method '$method' missing in class '$controller'.");
		}
		$controller = new $controller($view);

		return [$controller, $method];
	}
}