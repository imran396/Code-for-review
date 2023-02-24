<?php
/**
 * SAM-9809:  Refactor Action Queue Module
 * https://bidpath.atlassian.net/browse/SAM-9809
 *
 * @copyright       2021 Bidpath, Inc.654
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\ActionQueue\Cli\Command\Run;

use Sam\Core\Constants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunCommand
 * @package Sam\ActionQueue\Cli\Command\Run
 */
class RunCommand extends Command
{
    public const NAME = 'action-queue-run';

    public function __construct()
    {
        parent::__construct(static::NAME);
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setDescription('Process action queue events data');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $runHandler = RunHandler::new();
        $runHandler->handle();
        $message = 'Successfully run action queue handlers';
        $output->writeln($message);
        return Constants\Cli::EXIT_SUCCESS;
    }
}
