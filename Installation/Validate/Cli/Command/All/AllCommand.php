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

namespace Sam\Installation\Validate\Cli\Command\All;

use RuntimeException;
use Sam\Core\Constants;
use Sam\Installation\Validate\Cli\Command\Base\CommandBase;
use Sam\Installation\Validate\Cli\ValidateInstallationCliApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This class is a console command that runs all local installation checkers
 *
 * Class AllCommand
 * @package Sam\Installation\Validate\Cli\Command\All
 */
class AllCommand extends CommandBase
{
    public const NAME = 'all';

    /**
     * Configures command.
     * @return void
     */
    protected function configure(): void
    {
        $this->addOption('fix', null, InputOption::VALUE_NONE);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $application = $this->getApplication();
        if (!$application) {
            throw new RuntimeException("Application instance is missing");
        }

        foreach (ValidateInstallationCliApplication::COMMANDS_RUN_ALL as $commandName) {
            $command = $application->find($commandName);
            $command->ignoreValidationErrors();
            $command->run($input, $output);
        }
        return Constants\Cli::EXIT_SUCCESS;
    }
}
