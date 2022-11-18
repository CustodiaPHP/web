<?php

namespace App\Controller\Admin;

use App\Helper\SettingsHelper;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/setting')]
class SettingController extends AbstractController
{

    #[Route('/', name: 'app_admin_setting_index', methods: ['GET', 'POST'])]
    public function index(Request $request, SettingRepository $settingRepository): Response
    {
		if ($request->getMethod() === 'POST') {
			foreach (SettingsHelper::SETTINGS as $setting) {
				$value = $this->sanitizeSetting($setting, (array) $request->request->get($setting));
				$settingRepository->update($setting, $value);
			}
			$settingRepository->flush();
		}

        return $this->render('admin/setting/index.html.twig', [
            'settings' => $settingRepository,
        ]);
    }

	private function sanitizeSetting(string $key, array $value) : array
	{
		return array_merge(SettingsHelper::DEFAULT_SETTINGS[$key], $value);
	}

}
