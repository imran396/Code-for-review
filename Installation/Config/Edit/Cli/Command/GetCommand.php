<?php
/**
 * SAM-5708: Local configuration management by CLI script
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Cli\Command;

use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Cli\Exception\CliApplicationException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Cli command for getting configuration option value
 *
 * Class GetCommand
 * @package Sam\Installation\Config
 */
class GetCommand extends CommandBase
{
    public const NAME = 'get';

    /**
     * Configures command.
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Get configuration option value')
            ->addOption('key', 'k', InputOption::VALUE_REQUIRED, 'Configuration option path');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws CliApplicationException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $optionKey = $this->prepareInputOption($input->getOption('key'));
        if (!$this->isOptionExist($optionKey)) {
            throw new CliApplicationException('Option does not exist');
        }
        $descriptorCollection = $this->getDescriptorCollection();
        $optionHelper = $this->getOptionHelper();

        $globalOptions = $optionHelper->toGlobalOptions($descriptorCollection);
        $output->writeln('Global value: ' . var_export($globalOptions[$optionKey], true));

        $localOptions = $optionHelper->toLocalOptions($descriptorCollection);
        if (array_key_exists($optionKey, $localOptions)) {
            $output->writeln('Local value: ' . var_export($localOptions[$optionKey], true));
        } else {
            $output->writeln('<info>Local value is not present</info>');
        }

        return Constants\Cli::EXIT_SUCCESS;
    }
}
