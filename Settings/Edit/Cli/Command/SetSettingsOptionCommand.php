<?php
/**
 * SAM-5843: System Parameters management by CLI script
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Edit\Cli\Command;

use Account;
use InvalidArgumentException;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Settings\Edit\Dto\AuctionParametersDtoBuilder;
use Sam\Settings\Edit\Mutual\AuctionParametersMutualContext;
use Sam\Settings\Edit\Save\AuctionParametersProducer;
use Sam\Settings\Edit\Validate\AuctionParametersValidator;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sam\Core\Constants;

/**
 * Class SetSettingCommand
 * @package Sam\Settings\Edit\Cli
 */
class SetSettingsOptionCommand extends CommandBase
{
    use SettingsManagerAwareTrait;
    use UserLoaderAwareTrait;

    public const NAME = 'set';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->addOption('account', 'a', InputOption::VALUE_OPTIONAL, 'Specify Account id to update account setting or "all" for all accounts');
        $this->addOption('key', 'k', InputOption::VALUE_REQUIRED, 'Settings Option name');
        $this->addOption('value', null, InputOption::VALUE_REQUIRED, 'Settings Option value');
        $this->setDescription('This command sets settings option value');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputSettingsAccount = $input->getOption('account') ?? 'main';
        $settingsOptionKey = $this->normalizeKey($input->getOption('key'));
        $settingsOptionValue = $input->getOption('value');
        if (
            $settingsOptionKey === ''
            || $settingsOptionValue === null
        ) {
            throw new InvalidArgumentException('Options --key, --value are required');
        }

        $accounts = $this->retrieveAccounts($inputSettingsAccount);
        $systemUserId = $this->getUserLoader()->loadSystemUserId();
        foreach ($accounts as $account) {
            $context = AuctionParametersMutualContext::new()->constructForCli($systemUserId, $account->Id);
            $dtoBuilder = AuctionParametersDtoBuilder::new()->construct($context);
            $dtoBuilder->applyCliOptionValueToContextDto($settingsOptionKey, $settingsOptionValue);
            $context = $dtoBuilder->getContext();
            $validator = AuctionParametersValidator::new()->construct($context);
            if (!$validator->validate()) {
                $this->displayErrors($account, $settingsOptionKey, $validator->errorStatuses(), $output);
            } else {
                foreach ($validator->warningStatuses() as $warning) {
                    $output->writeln(sprintf('<comment>%s</comment>', $warning->getMessage()));
                }
                $context = $validator->getContext();
                $oldValue = $this->getSettingsManager()->get($settingsOptionKey, $account->Id);
                AuctionParametersProducer::new()->construct($context)->update();
                $newValue = $this->getSettingsManager()->get($settingsOptionKey, $account->Id);
                $output->writeln(
                    sprintf(
                        '<info>Option "%s" for account "%s" updated successfully! %s</info>',
                        $settingsOptionKey,
                        $account->Name,
                        composeSuffix(['old' => $oldValue, 'new' => $newValue])
                    )
                );
            }
        }
        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @param Account $account
     * @param string $option
     * @param ResultStatus[] $errors
     * @param OutputInterface $output
     */
    private function displayErrors(
        Account $account,
        string $option,
        array $errors,
        OutputInterface $output
    ): void {
        $output->write('<error>');
        $output->write(
            sprintf(
                'An error has occurred while saving option "%s" for account "%s" with ID "%s"',
                $option,
                $account->Name,
                $account->Id
            )
        );
        $output->writeln('</error>');
        foreach ($errors as $error) {
            $output->writeln('  * ' . $error->getMessage());
        }
    }
}
