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

    #[Route('/general', name: 'app_admin_setting_general', methods: ['GET', 'POST'])]
    public function general(Request $request, SettingRepository $settingRepository): Response
    {
		if(!$this->isGranted('ROLE_SUPER_ADMIN')){
			return $this->redirectToRoute('app_admin_dashboard');
		}

		if ($request->getMethod() === 'POST') {
			foreach (SettingsHelper::SETTINGS as $setting) {
				if($request->request->has($setting)) {
					$value = $this->sanitizeSetting($setting, (array) $request->request->get($setting));
					$settingRepository->update($setting, $value);
				}
			}
			$settingRepository->flush();
		}

        return $this->render( 'admin/setting/general.html.twig', [
            'settings' => $settingRepository,
        ]);
    }

	#[Route('/notifications', name: 'app_admin_setting_notifications', methods: ['GET', 'POST'])]
	public function notifications(Request $request, SettingRepository $settingRepository): Response
	{
		if(!$this->isGranted('ROLE_SUPER_ADMIN')){
			return $this->redirectToRoute('app_admin_dashboard');
		}

		if ($request->getMethod() === 'POST') {
			foreach (SettingsHelper::SETTINGS as $setting) {
				if($request->request->has($setting)) {
					$value = $this->sanitizeSetting($setting, (array) $request->request->get($setting));
					$settingRepository->update($setting, $value);
				}
			}
			$settingRepository->flush();
		}

		return $this->render( 'admin/setting/notifications.html.twig', [
			'settings' => $settingRepository,
		]);
	}

	private function sanitizeSetting(string $key, array $value) : array
	{
		return array_merge(SettingsHelper::DEFAULT_SETTINGS[$key], $value);
	}

}
