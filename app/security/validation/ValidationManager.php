<?php

namespace App\Security\Validation;

class ValidationManager
{
	private array $schema = [];
	private array $errors = [];
	private array $validators;

	public function __construct()
	{
		$this->validators = [
			'isset' 	=> fn($v) => isset($v),
			'required' 	=> fn($v) => !empty($v),
			'nullable'	=> function ($v) {
				if (empty($v))
					throw new \Exception('');
				else
					return true;
			},
			'string' 	=> fn($v) => is_string($v),
			'number' 	=> fn($v) => is_numeric($v),
			'array' 	=> fn($v) => is_array($v),
			'length' 	=> fn(string $v, int $l) => strlen($v) === $l,
			'min' 		=> fn(string $v, int $l) => strlen($v) >= $l,
			'max' 		=> fn(string $v, int $l) => strlen($v) <= $l,
		];
	}

	public function getPrepared(
		string $name, 
		array $args = [], 
		?string $msg = null
	): array
	{
		if (!isset($this->validators[$name])) {
			throw new \Exception("Validator '$name' is missing.");
		}
		elseif (!is_callable($this->validators[$name])) {
			throw new \Exception("Validator '$name' must be callable.");
		}
		return [$name, $args, $msg];
	}

	public function setSchema(array $schema): void
	{
		$this->schema = [];

		foreach ($schema as $field => $data) {
			$this->schema[$field] = array_map(
				fn($d) => call_user_func_array([$this, 'getPrepared'], $d), $data);
		}
	}

	private function validatorTemplate(
		string $field, 
		string $validator, 
		array $args, 
		?string $msg
	): bool
	{
		if (!call_user_func_array($this->validators[$validator], $args)) {
			$this->errors[$field] = $msg;
			return false;
		}
		else {
			return true;
		}
	}

	public function validate(array $values): bool
	{
		$this->errors = [];

		foreach ($this->schema as $key => $data) {
			try {
				foreach ($data as $d) {
					$args = $d;
					array_unshift($args[1], $values[$key] ?? null);
					array_unshift($args, $key);
					
					if (!call_user_func_array(
						[$this, 'validatorTemplate'], $args)) {
						break 1;
					}
				}
			}
			catch (\Exception $e) {
				break 1;
			}
		}
		return ($this->errors === []) ? true : false;
	}

	public function getErrors(): array
	{
		return $this->errors;
	}
}