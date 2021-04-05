<?php

namespace App\Routing\Routers;

use App\Routing\Route;

abstract class Router
{
	public function __construct()
	{
		//
	}

	abstract public function route(Route $route): void;
}