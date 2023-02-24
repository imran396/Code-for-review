<?php
/**
 * SAM-5843: System Parameters management by CLI script
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Edit\Cli\Command;

use Sam\Core\Constants;
use Sam\Settings\SettingsManagerAwareTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class SetSettingCommand
 * @package Sam\Settings\Edit\Cli
 */
class GetSettingsOptionCommand extends CommandBase
{
    use SettingsManagerAwareTrait;

    public const NAME = 'get';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->addOption('account', 'a', InputOption::VALUE_OPTIONAL, 'Specify Account id to get account setting or "all" for all accounts');
        $this->addOption('key', 'k', InputOption::VALUE_OPTIONAL, 'Settings Option name');
        $this->setDescription('This command gets settings option value');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputSettingsAccount = $input->getOption('account') ?? 'all';
        $settingOptionKey = $this->normalizeKey($input->getOption('key'));

        $keys = $settingOptionKey ? [$settingOptionKey] : $this->getAllOptionKeys();
        $accounts = $this->retrieveAccounts($inputSettingsAccount);
        $io = new SymfonyStyle($input, $output);

        foreach ($accounts as $account) {
            if (count($accounts) > 1) {
                $io->section("Account {$account->Name}");
            }
            $this->displayOptionValues($account->Id, $keys, $io);
        }
        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @param int $accountId
     * @param array $optionNames
     * @param SymfonyStyle $io
     */
    private function displayOptionValues(int $accountId, array $optionNames, SymfonyStyle $io): void
    {
        $rows = [];
        foreach ($optionNames as $optionName) {
            $optionValue = $this->getSettingsManager()->get($optionName, $accountId);
            $rows[] = [$optionName, var_export($optionValue, true)];
        }
        $io->table(
            [
                'Option',
                'Value'
            ],
            $rows
        );
    }

    /**
     * @return array
     */
    private function getAllOptionKeys(): array
    {
        $keys = array_keys(Constants\Setting::$typeMap);
        return $keys;
    }
}
