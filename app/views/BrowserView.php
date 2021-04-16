<?php

declare(strict_types = 1);

namespace App\Views;

use App\Views\View;

class BrowserView extends View
{
	private string $layoutPath;
	private string $templatesDir;
	private array $sections = [];
	private string $outputBuffer = '';
	private ?string $currentSection = null;
	private string $extension;

	public function __construct(
		string $templatesDir, 
		string $layoutPath, 
		string $extension
	)
	{
		$this->templatesDir = rtrim($templatesDir, '/') . '/';
		$this->layoutPath = trim($layoutPath, '/');
		$this->extension = $extension;
	}

	public function render(string $path, array $args = []): void
	{
		$fullPath = $this->templatesDir . ltrim($path, '/') . 
			'.' . $this->extension;
		$layoutPath = $this->templatesDir . $this->layoutPath . 
			'.' . $this->extension;

		if (!file_exists($layoutPath)) {
			throw new \Exception(
				"File '" . $layoutPath . "' is missing.");
		}
		elseif (!is_readable($layoutPath)) {
			throw new \Exception(
				"File '" . $layoutPath . "' is not readable.");
		}
		elseif (!file_exists($fullPath)) {
			throw new \Exception(
				"File '" . $fullPath . "' is missing.");
		}
		elseif (!is_readable($fullPath)) {
			throw new \Exception(
				"File '" . $fullPath . "' is not readable.");
		}

		extract($args);
		include_once $fullPath;
		include_once $layoutPath;
	}

	private function startSection(string $name): void
	{
		$this->currentSection = $name;
		ob_start();
	}

	private function endSection(): void
	{
		if (is_null($this->currentSection)) {
			throw new \Exception("Any section didn't started.");
		}
		$this->sections[$this->currentSection] = ob_get_clean();
		$this->currentSection = null;
	}

	private function getSection(string $name): string
	{
		if (!isset($this->sections[$name])) {
			throw new \Exception("Section '$name' is missing.");
		}
		if (!is_string($this->sections[$name])) {
			throw new \Exception("Section '$name' must be string.");
		}
		return $this->sections[$name];
	}

	public function getAsString(string $path, ?array $args = []): string
	{
		ob_start();
		$this->render($path, $args);
		return ob_get_clean();
	}
} 