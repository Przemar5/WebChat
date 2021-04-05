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

	public function __construct(string $layoutPath, string $templatesDir)
	{
		$this->layoutPath = $layoutPath;
		$this->templatesDir = $templatesDir;
	}

	public function render(string $path, array $args = []): void
	{
		$fullPath = $this->templatesDir . $path

		if (!file_exists($this->layoutPath)) {
			throw new \Exception(
				"File '" . $this->layoutPath . "' is missing.");
		}
		elseif (!is_readable($this->layoutPath)) {
			throw new \Exception(
				"File '" . $this->layoutPath . "' is not readable.");
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
		include_once $this->layoutPath;
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

	private function getSection(string $name): void
	{
		if (!isset($this->sections[$name])) {
			throw new \Exception("Section '$name' is missing.");
		}
		return $this->sections[$name];
	}
} 