<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Views\View;

abstract class Controller
{
	protected View $view;

	public function __construct(View $view)
	{
		$this->view = $view;
	}
}