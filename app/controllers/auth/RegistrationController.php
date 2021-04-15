<?php

declare(strict_types = 1);

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Entities\Factories\TokenFactory;
use App\Security\Validation\ValidationManager;

class RegistrationController extends Controller
{
	private array $errors = [];

	public function page(): void
	{
		$this->view->render('auth/register');
	}

	public function process(): void
	{
		if ($this->validateData()) {
			$this->sanitizeData();
			$this->createUser();
		}
		else {
			$this->page();
		}
	}

	private function validateData(): bool
	{
		$validation = new ValidationManager();
		$validation->setSchema([
			'login' => [
				['required', [], 'Login is required.'],
				['string', [], ''],
				['min', [3], 'Login must be at least 3 characters long.'],
				['max', [60], 'Login must be less or equal 60 characters.'],
				['pattern', ['/^[\w\d\-]+$/u'], 'Login may contain only letters, ' . 
				'numbers, dashes and underscores.'],
				['unique', ['users', 'login', 'deleted'], 
					'Given login already exists in the database.'],
			],
			'email' => [
				['required', [], 'Email is required.'],
				['string', [], ''],
				['min', [3], 'Email must be at least 3 characters long.'],
				['max', [80], 'Email must be less or equal 80 characters.'],
				['email', [], 'Email must be a proper email address.'],
				['unique', ['users', 'email', 'deleted'], 
					'Given email already exists in the database.'],
			],
			'password' => [
				['required', [], 'Password is required.'],
				['string', [], ''],
				['min', [6], 'Password must be at least 6 characters long.'],
				['max', [60], 'Password must be less or equal 60 characters.'],
				['pattern', ['/^(?:(?=.*?\p{N})(?=.*?[\p{S}\p{P} ])(?=.*?\p{Lu})(?=.*?\p{Ll}))[^\p{C}]{8,60}$/u'],
				'Password must contain a lowercase and uppercase letter, ' . 
				'a number and special character.'],
			],
		]);
		dd($validation->validate($_POST));
		if ($validation->validate($_POST)) {
			// return true;
			dd('ok');
		}
		else {
			$this->errors = $validation->getErrors();
			dd('err');
			dd($this->errors);
			// return false;
		}
	}
}