<?php
/**
 * SAM-5796: Single CLI application for data integrity checkers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\DataIntegrityChecker\Cli;

use Sam\Core\Service\CustomizableClass;
use Sam\DataIntegrityChecker\Cli\Command;
use Symfony\Component\Console\Application;

/**
 * The main entry point to console data integrity checker commands.
 *
 * Class DataIntegrityCheckerCliApplication
 * @package Sam\DataIntegrityChecker\Cli
 */
class DataIntegrityCheckerCliApplication extends CustomizableClass
{
    /** @var array */
    private array $commands = [
        Command\ActiveInSingleAuctionCheckerCommand::class,
        Command\ActiveOneAuctionCustomFieldCheckerCommand::class,
        Command\ActiveOneLotCustomFieldCheckerCommand::class,
        Command\ActiveOneUserCustomFieldCheckerCommand::class,
        Command\AllCheckerCommand::class,
        Command\AuctionLotWithBuyNowAndBulkGroupCheckerCommand::class,
        Command\BidIncrementUniqueCheckerCommand::class,
        Command\BuyersPremiumUniqueCheckerCommand::class,
        Command\InvoiceUniqueForLotCheckerCommand::class,
        Command\ItemNumUniqueForAccountCheckerCommand::class,
        Command\SaleNoUniqueCheckerCommand::class,
        Command\UserCustomerNoUniqueCheckerCommand::class,
        Command\UserEmailUniqueCheckerCommand::class,
        Command\UserUsernameUniqueCheckerCommand::class,
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function run(): int
    {
        $application = new Application();
        foreach ($this->commands as $command) {
            $application->add(new $command());
        }
        return $application->run();
    }
}
