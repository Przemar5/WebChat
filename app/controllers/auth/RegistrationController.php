<?php

declare(strict_types = 1);

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Entities\User;
use App\Entities\Token;
use App\Entities\Factories\TokenFactory;
use App\Security\Validation\ValidationManager;
use App\Database\Tables\TableFactory;

class RegistrationController extends Controller
{
	private array $errors = [];

	public function page(): void
	{
		$table = TableFactory::create('tokens');
		$token = TokenFactory::generate('csrf_token', 0);
		$table->save($token);
		
		$this->view->render('auth/register', [
			'token' => $token,
			'errors' => $this->errors,
		]);
	}

	public function process(): void
	{
		$token = $this->getTokenFromRequestByName('csrf_token');
		$data = $this->trimSendData($_POST);

		if ($this->validateToken($token) && $this->validateData($data)) {
			$this->sanitizeData($data);

			$user = $this->createUserAndGet($data);
			$table = TableFactory::create('tokens');
			$token = TokenFactory::generate(
				'verification_email_token', $id);
			$this->sendVerificationEmail($user, $token);
		}
		else {
			$this->page();
		}
	}

	private function trimSendData(array $data): array
	{
		$result = [];
		
		foreach ($data as $key => $value) {
			$result[$key] = trim($value);
		}
		return $result;
	}

	private function getTokenFromRequestByName(string $name): ?Token
	{
		return TokenFactory::getByNameValueUserId($name, $_POST[$name]);
	}

	private function validateToken(Token $token): bool
	{
		$table = TableFactory::create('tokens');
		$token = $table->find([
			'name' => $token->name,
			'value' => $token->value,
			'user_id' => 0,
		]);
		
		if (!$token) {
			return false;
		}
		elseif ($token->expiry < date('Y-m-d H:i:s', time())) {
			$table->delete(['id' => $token]);
			return false;
		}
		else {
			return true;
		}
	}

	private function validateData(array $data): bool
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

		if ($validation->validate($_POST)) {
			return true;
		}
		else {
			$this->errors = $validation->getErrors();
			return false;
		}
	}

	private function sanitizeData(array $data): array
	{
		return $data;
	}

	private function createUserAndGet(array $data): User
	{
		$table =  TableFactory::create('users');
		$user = new User();
		$user->login = $data['login'];
		$user->email = $data['email'];
		$user->password = $data['password'];
		$table->save($user);
		
		if (!is_int($id = $table->lastInsertId())) {
			throw new \Excpetion('Cannot add user.');
		}
		$user->id = $id;
		return $user;
	}

	private function sendVerificationEmail(User $user, Token $token): void
	{
		
	}
}