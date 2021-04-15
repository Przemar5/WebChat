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
	public bool $verified = false;
	public bool $deleted = false;

	public function setCreatedOn()
	{
		$this->created_on = date('Y-m-d H:i:s', strtotime('now'));
	}

	public function setLastLogin(?string $time = null)
	{
		$this->last_login = ($time) ? 
			$time : date('Y-m-d H:i:s', strtotime('now'));
	}
}