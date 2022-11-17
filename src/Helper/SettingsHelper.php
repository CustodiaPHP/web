<?php

namespace App\Helper;

use App\Repository\SettingRepository;

class SettingsHelper {

	private SettingRepository $settingRepository;

	public function __construct(SettingRepository $settingRepository) {
		$this->settingRepository = $settingRepository;
	}

	public function getGeneral(string $key) : mixed
	{
		$generalSettings = $this->settingRepository->findOneBy(['key' => 'general']);
		return $generalSettings ? $generalSettings->getValue()[$key] : null;
	}

}