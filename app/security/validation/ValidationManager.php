<?php

namespace App\Security\Validation;

use App\Database\Tables\TableFactory;

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
			'unique'	=> function (
				string $v, 
				string $table, 
				string $col, 
				?string $delCol = null
			) {
				$table = TableFactory::create($table);
				$result = $table->find((is_null($delCol)) 
					? [$col => $v] : [$col => $v, $delCol => false]);
				return empty($result);
			},
			'exists'	=> function (
				string $v, 
				string $table, 
				string $col,
				?string $delCol = null
			) {
				$table = TableFactory::create($table);
				$result = $table->find((is_null($delCol)) 
					? [$col => $v] : [$col => $v, $delCol => false]);
				return !empty($result);
			},
			'pattern'	=> fn(string $v, string $pattern) => (bool) preg_match($pattern,  $v),
			'email'		=> fn(string $v) => 
				(bool) preg_match("/(?:[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/u", $v),
		];
	}

	public function setSchema(array $schema): void
	{
		$this->schema = [];

		foreach ($schema as $field => $data) {
			$this->schema[$field] = array_map(
				fn($d) => call_user_func_array([$this, 'getPrepared'], $d), $data);
		}
	}

	private function getPrepared(
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
				//
			}
		}
		return ($this->errors === []) ? true : false;
	}

	public function getErrors(): array
	{
		return $this->errors;
	}
}