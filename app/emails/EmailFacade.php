<?php

namespace App\Emails;

use App\Entities\User;
use App\Entities\Token;
use App\Views\ViewFactory;
use App\Entities\Factories\TokenFactory;
use App\Emails\EmailAdapter;
use App\Utils\Converter;

class EmailFacade
{
	public static function sendRegistrationConfirmEmail(
		User $user,
		Token $token
	): void
	{
		$view = ViewFactory::createHtmlEmailView();
		$email = new EmailAdapter($view);
		$email->subject = 'Registration confirmation';
		$email->receivers = [$user];
		static::completeEmailWithConfig($email);
		$email->parseTemplate('emails/registration_confirm_email', [
			'receiver' => $user,
			'token' => $token,
			'route' => route('registration_verify'),
		]);
		dd(__LINE__);
		$email->send();
	}

	public static function sendForgotPasswordEmail(
		User $user,
		Token $token
	): void
	{
		$view = ViewFactory::createHtmlEmailView();
		$email = new EmailAdapter($view);
		static::completeEmailWithConfig($email);
		$email->subject = 'Forgotten password';
		$email->receivers = [$user];
		$email->parseTemplate('emails/reset_password_email', [
			'receiver' => $user,
			'token' => $token,
			'route' => route('forgot_password_verify'),
		]);
		$email->send();
	}

	private static function completeEmailWithConfig(
		EmailAdapter &$email
	)
	{
		$path = realpath('./config/email.json');
		static::throwExceptionsIfPathNotValid($path);
		$data = static::loadAndParseFile($path);
		
		static::completeEmailWithData($email, $data);
	}

	private static function throwExceptionsIfPathNotValid(string $path): void
	{
		if (!file_exists($path)) {
			throw new \Exception(sprintf("File '%s' doesn't exist.", $path));
		}
		elseif (!is_readable($path)) {
			throw new \Exception(sprintf("File '%s' isn't readable.", $path));
		}
	}

	private static function loadAndParseFile(string $path): array
	{
		$content = file_get_contents($path);

		return Converter::jsonToArray($content);
	}

	private static function completeEmailWithData(
		EmailAdapter &$email,
		array $data
	)
	{
		$email->host = $data['host'];
		$email->username = $data['username'];
		$email->password = $data['password'];
		$email->encryption = $data['encryption'];
		$email->from = $data['from'];
		$email->fromName = $data['from_name'];
		$email->replyTo = $data['reply_to'];
		$email->replyName = $data['reply_name'];
	}
}