<?php
/**
 * SAM-5708: Local configuration management by CLI script
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Cli\Command;

use Sam\Installation\Config\Edit\Cli\Exception\CliApplicationException;
use Sam\Installation\Config\Edit\Cli\Processor\ConfigUpdateProcessor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sam\Core\Constants;

/**
 * Cli command for setting configuration option value
 *
 * Class SetConfigValueCommand
 * @package Sam\Installation\Config
 */
class SetCommand extends CommandBase
{
    public const NAME = 'set';

    /**
     * Configures command.
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Set configuration option value')
            ->addOption('key', 'k', InputOption::VALUE_REQUIRED, 'Configuration option path')
            ->addOption('value', null, InputOption::VALUE_REQUIRED, 'Configuration option value');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws CliApplicationException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $optionKeyMeta = $input->getOption('key');
        $optionKey = $this->prepareInputOption($optionKeyMeta);
        if (!$this->isOptionExist($optionKey)) {
            throw new CliApplicationException('Option does not exist');
        }

        $descriptor = $this->getDescriptorCollection()->get($optionKey);
        $oldValue = $descriptor->getActualValue();

        $value = $this->normalizeInputValue($input->getOption('value'));
        $success = ConfigUpdateProcessor::new()->process(
            $optionKey,
            $value,
            true,
            $this->getDescriptorCollection()
        );
        if (!$success) {
            throw new CliApplicationException('Option value has not been saved');
        }

        $output->writeln(
            'Option value has been saved successfully'
            . composeSuffix([$optionKeyMeta => ['Old' => $oldValue, 'New' => $value]])
        );

        return Constants\Cli::EXIT_SUCCESS;
    }
}
