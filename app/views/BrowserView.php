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
	private string $currentSection;

	public function __construct(string $templatesDir, string $layoutPath)
	{
		$this->templatesDir = rtrim($templatesDir, '/') . '/';
		$this->layoutPath = trim($layoutPath, '/');
	}

	public function render(string $path, array $args = []): void
	{
		$fullPath = $this->templatesDir . $path;
		$layoutPath = $this->templatesDir . $this->layoutPath;

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
		include_once $layoutPath;
		include_once $fullPath;
	}

	private function startSection(string $name): void
	{
		$this->currentSection = $name;
		ob_start();
	}

	private function endSection(): void
	{
		$this->sections[$this->currentSection] = ob_get_clean();
	}

	private function getSection(string $name): string
	{
		if (!isset($this->sections[$name])) {
			throw new \Exception("Section '$name' is missing.");
		}
		return $this->sections[$name];
	}
} 