<?php

class Environment
{

	private static $env = [
		'APP_ENV' => 'dev',
		'APP_SECRET' => 'a1b2c3d4e5f6g7h8i9j0',
		'DATABASE_URL' => 'mysql://user:password@localhost:3306/database'
	];

	public static function getEnv($key): string
    {
		return self::$env[$key];
	}

	public static function setEnv($key, $value): void
    {
		self::$env[$key] = $value;
	}

	public static function writeEnv(): void
    {
		$env = '';
		foreach (self::$env as $key => $value) {
			$env .= $key . '=' . $value . PHP_EOL;
		}
		file_put_contents(dirname(__DIR__) . '/.env', $env);
	}
}
