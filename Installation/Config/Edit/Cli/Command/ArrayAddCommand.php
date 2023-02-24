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

use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Cli\Exception\CliApplicationException;
use Sam\Installation\Config\Edit\Cli\Processor\ConfigUpdateProcessor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Cli command for adding record to the end of array config option
 *
 * Class ArrayAddCommand
 * @package Sam\Installation\Config
 */
class ArrayAddCommand extends CommandBase
{
    public const NAME = 'array-add';

    /**
     * Configures command.
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Add record to the end of array')
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

        if (!$this->isOptionTypeArray($optionKey)) {
            throw new CliApplicationException('Option is not an array');
        }

        $updatedValue = $this->processValue($optionKey, $input->getOption('value'));

        $descriptor = $this->getDescriptorCollection()->get($optionKey);
        $oldValue = $descriptor->getActualValue();

        $success = ConfigUpdateProcessor::new()->process(
            $optionKey,
            $updatedValue,
            false,
            $this->getDescriptorCollection()
        );
        if (!$success) {
            throw new CliApplicationException('Option value array record has not been added');
        }

        $output->writeln(
            'Option value array record has been added successfully'
            . composeSuffix([$optionKeyMeta => ['Old' => $oldValue, 'New' => $updatedValue]])
        );

        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @param string $optionKey
     * @param string $value
     * @return array
     */
    private function processValue(string $optionKey, string $value): array
    {
        $normalizedValue = $this->normalizeInputValue($value);
        $actualOptions = $this->getOptionHelper()->toActualOptions($this->getDescriptorCollection());
        $optionValue = $actualOptions[$optionKey];
        return array_merge($optionValue, [$normalizedValue]);
    }

    /**
     * @param string $optionKey
     * @return bool
     */
    private function isOptionTypeArray(string $optionKey): bool
    {
        return $this->getDescriptorCollection()->get($optionKey)->getType() === Constants\Type::T_ARRAY;
    }
}
