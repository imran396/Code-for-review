<?php
/**
 * SAM-5796: Single CLI application for data integrity checkers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\DataIntegrityChecker\Cli\Command;

use Exception;
use Sam\DataIntegrityChecker\Cli\Command\Base\CommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sam\Core\Constants;

/**
 * Command calls all data integrity checkers
 *
 * Class AllCheckerCommand
 * @package Sam\DataIntegrityChecker\Cli
 */
class AllCheckerCommand extends CommandBase
{
    public const NAME = 'all';

    /**
     * Configures command.
     */
    protected function configure(): void
    {
        $this->setDescription('Run all checkers for all accounts');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $all = $this->getApplication() ? $this->getApplication()->all() : [];
        foreach ($all as $command) {
            if (is_a($command, CommandBase::class) && !is_a($command, static::class)) {
                $output->writeln('');
                $output->writeln(sprintf('>>> %s checker', $command->getName()));
                $command->run($input, $output);
            }
        }
        return Constants\Cli::EXIT_SUCCESS;
    }
}
