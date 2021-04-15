<?php

declare(strict_types = 1);

namespace App\Entities;

use App\Entities\Entity;

class User extends Entity
{
	public string $login;
	public string $password;
	public string $email;
	public string $created_on;
	public string $last_login;
	public bool $verifid = false;
	public bool $deleted = false;
}