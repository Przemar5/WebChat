<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Controllers\HomeController;

class ControllerFactory
{
	public static function create(string $name, View $view): ?Controller
	{
		switch ($name) {
			'home': return new HomeController($view);
			default: return null;
		}
	}
}