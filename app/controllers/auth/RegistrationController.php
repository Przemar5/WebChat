<?php

declare(strict_types = 1);

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Entities\Factories\TokenFactory;

class RegistrationController extends Controller
{
	public function page(): void
	{
		$this->view->render('auth/register');
	}

	public function process(): void
	{
		//
	}
}