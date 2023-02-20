<?php

namespace App\Helper;

use App\Repository\SettingRepository;

class SettingsHelper {

	const SETTINGS = [
		'general',
		'footer',
		'notifications',
		'email'
	];

	const DEFAULT_SETTINGS = [
		'general' => [
			'page_name' => 'CustodiaPHP',
			'dark_mode' => 0,
		],
		'footer' => [
			'privacy_link' => false,
			'imprint_link' => false,
			'dashboard_link' => true
		],
		'notifications' => [
			'enabled' => false,
			'api_secret' => '',
			'status_update' => false,
			'new_incident' => false
		],
		'email' => [
			'enabled' => false,
			'host' => 'localhost',
			'port' => 587,
			'username' => '',
			'password' => '',
			'from' => '',
			'from_name' => '',
		]
	];

	private SettingRepository $settingRepository;

	public function __construct(SettingRepository $settingRepository) {
		$this->settingRepository = $settingRepository;
	}

	public function getGeneral(string $key) : mixed
	{
		$generalSettings = $this->settingRepository->findOneBy(['name' => 'general']);
		return $generalSettings !== null ? ((array) $generalSettings->getValue())[$key] : self::DEFAULT_SETTINGS['general'][$key];
	}

	public function getFooter(string $key) : mixed
	{
		$footerSettings = $this->settingRepository->findOneBy(['name' => 'footer']);
		return $footerSettings !== null ? ((array) $footerSettings->getValue())[$key] : self::DEFAULT_SETTINGS['footer'][$key];
	}

	public function getNotifications(string $key) : mixed
	{
		$notificationSettings = $this->settingRepository->findOneBy(['name' => 'notifications']);
		return $notificationSettings !== null ? ((array) $notificationSettings->getValue())[$key] : self::DEFAULT_SETTINGS['notifications'][$key];
	}


	public function getEmail(string $key) : mixed
	{
		$emailSettings = $this->settingRepository->findOneBy(['name' => 'email']);
		return $emailSettings !== null ? ((array) $emailSettings->getValue())[$key] : self::DEFAULT_SETTINGS['email'][$key];
	}

	public function getEmailSender() : string
	{
		return $this->getEmail('from_name') . ' <' . $this->getEmail('from') . '>';
	}

	public function getMailerDSN() : string
	{
		if ( $this->getEmail('enabled') == 0 ) {
			return 'null://null';
		}

		return sprintf(
			'smtp://%s:%s@%s:%d',
			$this->getEmail('username'),
			$this->getEmail('password'),
			$this->getEmail('host'),
			$this->getEmail('port')
		);
	}
}