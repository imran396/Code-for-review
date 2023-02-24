<?php
/**
 * SAM-5306: Local installation correctness check
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Validate\Cli\Command\Config;

use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Installation\Config\Edit\Validate\LocalFile\LocalConfigValidator;
use Sam\Installation\Validate\Cli\Command\Base\CommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This class is a console command that checks local config files
 *
 * Class CheckConfigCommand
 * @package Sam\Installation\Cli
 */
class ConfigCommand extends CommandBase
{
    public const NAME = 'config';

    /**
     * Configures command.
     */
    protected function configure(): void
    {
        $this->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Config name for checking');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configNameOpt = $input->getOption('name');
        $validationConfigs = $configNameOpt ? [$configNameOpt] : Constants\Installation::AVAILABLE_CONFIG_NAMES;
        foreach ($validationConfigs as $configName) {
            $validator = LocalConfigValidator::new();
            if (!$validator->validate($configName)) {
                $this->showValidationErrors($output, $configName, $validator->errorStatuses());
            } else {
                $this->showSuccessMessage($output, $configName);
            }
        }
        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @param OutputInterface $output
     * @param string $configName
     * @param ResultStatus[] $errors
     */
    private function showValidationErrors(OutputInterface $output, string $configName, array $errors): void
    {
        $output->write('<error>');
        foreach ($errors as $error) {
            $output->writeln(
                sprintf(
                    'Invalid option "%s->%s" with value "%s". %s',
                    $configName,
                    $this->formatOptionName($error->getPayload()['option']),
                    $error->getPayload()['value'],
                    $error->getMessage()
                )
            );
        }
        $output->write('</error>');
    }

    /**
     * @param string $optionName
     * @return string
     */
    private function formatOptionName(string $optionName): string
    {
        return str_replace(
            Constants\Installation::DELIMITER_GENERAL_OPTION_KEY,
            Constants\Installation::DELIMITER_RENDER_OPTION_KEY,
            $optionName
        );
    }

    /**
     * @param OutputInterface $output
     * @param string $config
     */
    private function showSuccessMessage(OutputInterface $output, string $config): void
    {
        $output->write('<info>');
        $output->write(sprintf('Config "%s" correct', $config));
        $output->writeln('</info>');
    }
}
