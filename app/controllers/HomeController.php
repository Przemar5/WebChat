<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Controllers\Controller;

class HomeController extends Controller
{
	public function __construct(View $view)
	{
		parent::__construct($view);
	}

	public function homePage()
	{
		$this->view->render();
	}
}