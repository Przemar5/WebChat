<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Controllers\Controller;

class HomeController extends Controller
{
	public function homePage()
	{
		echo 'home';
		$this->view->render('pages/home.php');
	}
}