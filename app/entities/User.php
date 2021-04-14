<?php

declare(strict_types = 1);

namespace App\Entities;

use App\Entities\Entity;

class User extends Entity
{
	public final int $id;
	public string $login;
	public string $password;
	public string $email;
	public final string $created_on;
	public string $last_login;
	public boolean $verifid = false;
	public boolean $deleted = false;
}