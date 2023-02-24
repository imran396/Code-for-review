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
use Sam\Installation\Config\Edit\Delete\Deleter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Cli command for removing configuration option value
 *
 * Class RemoveCommand
 * @package Sam\Installation\Config
 */
class RemoveCommand extends CommandBase
{
    public const NAME = 'rm';

    /**
     * Configures command.
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Remove configuration option value')
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
        $optionKey = $input->getOption('key');
        $optionKey = $this->prepareInputOption($optionKey);

        $deleter = Deleter::new();
        if ($deleter->deleteFromLocalConfig($optionKey, $this->getDescriptorCollection())) {
            $message = strip_tags(implode("\n", $deleter->successMessages()));
            $output->writeln($message);
        } else {
            throw CliApplicationException::createFromMessages($deleter->errorMessages());
        }

        return Constants\Cli::EXIT_SUCCESS;
    }
}
