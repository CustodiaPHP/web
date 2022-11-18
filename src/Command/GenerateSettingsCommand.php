<?php

namespace App\Command;

use App\Entity\Setting;
use App\Helper\SettingsHelper;
use App\Repository\SettingRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-settings',
    description: 'Generate default settings for general usage',
)]
class GenerateSettingsCommand extends Command
{

    private SettingRepository $settingRepository;
    private ManagerRegistry $managerRegistry;

    public function __construct(SettingRepository $settingRepository, ManagerRegistry $manager, string $name = null)
    {
        parent::__construct($name);

        $this->settingRepository = $settingRepository;
        $this->managerRegistry = $manager;
    }

    protected function configure(): void
    {
        $this
            ->addOption('force', null, InputOption::VALUE_NONE, 'Replace existing settings if they exist')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach (SettingsHelper::DEFAULT_SETTINGS as $settingKey => $settingValue)
        {
            if($this->settingRepository->findOneBy(['name' => $settingKey]) === null)
            {
                $setting = new Setting();

                $setting->setName($settingKey);
                $setting->setValue($settingValue);

                $this->managerRegistry->getManager()->persist($setting);
            }
            else if ($input->getOption('force'))
            {
                $setting = $this->settingRepository->findOneBy(['name' => $settingKey]);
                $setting->setValue($settingValue);
            }
        }

        $this->managerRegistry->getManager()->flush();
        $io->success('Generated all needed settings entries');

        return Command::SUCCESS;
    }
}
