<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/13/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Cli\Command\Info;

use Sam\Core\Constants;
use Sam\Rtb\Pool\Cli\Command\Base\CommandBase;
use Sam\Rtb\Pool\Cli\ApplicationConstants;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InfoCommand
 * @package
 */
class InfoCommand extends CommandBase
{
    /**
     * @var string
     */
    protected static $defaultName = ApplicationConstants::C_INFO;

    /**
     * Executes the current command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $isAll = (bool)$input->getOption(InfoConstants::O_ALL);
        InfoHandler::new()
            ->enableAll($isAll)
            ->setOutput($output)
            ->handle();
        return Constants\Cli::EXIT_SUCCESS;
    }
}
