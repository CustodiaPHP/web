<?php

namespace App\Helper;

use App\Repository\SettingRepository;

class SettingsHelper {

	const SETTINGS = [
		'general',
		'footer'
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
		]
	];

	private SettingRepository $settingRepository;

	public function __construct(SettingRepository $settingRepository) {
		$this->settingRepository = $settingRepository;
	}

	public function getGeneral(string $key) : mixed
	{
		$generalSettings = $this->settingRepository->findOneBy(['name' => 'general']);
		return $generalSettings ? $generalSettings->getValue()[$key] : self::DEFAULT_SETTINGS['general'][$key];
	}

	public function getFooter(string $key) : mixed
	{
		$generalSettings = $this->settingRepository->findOneBy(['name' => 'footer']);
		return $generalSettings ? $generalSettings->getValue()[$key] : self::DEFAULT_SETTINGS['footer'][$key];
	}

}