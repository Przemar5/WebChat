<?php

namespace App\Views;

use App\Views\BrowserView;

class ViewFactory
{
	public static function createDefaultBrowserView(): BrowserView
	{
		$view = new BrowserView('public/templates', 'layouts/default', 'php');

		return $view;
	}

	public static function createHtmlEmailView(): BrowserView
	{
		$view = new BrowserView('public/templates', 'layouts/email', 'php');

		return $view;
	}
}