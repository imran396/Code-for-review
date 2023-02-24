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

namespace Sam\Installation\Validate\Cli\Command\Db;

use QMySqliDatabaseException;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Installation\Validate\Cli\Command\Base\CommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sam\Core\Constants;

/**
 * This class is a console command that checks DB configuration
 *
 * Class CheckDBCommand
 * @package Sam\Installation\Cli
 */
class DbCommand extends CommandBase
{
    use DbConnectionTrait;

    public const NAME = 'db';

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws QMySqliDatabaseException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        mysqli_report(MYSQLI_REPORT_STRICT);
        $this->getDb()->Connect();
        $output->writeln('<info>Database connection established successfully</info>');
        return Constants\Cli::EXIT_SUCCESS;
    }
}
