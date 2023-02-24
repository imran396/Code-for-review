<?php
/**
 * SAM-7949: Predictive upcoming auction stats script
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Stat\UpcomingAuction\Cli;

use Sam\Core\Service\CustomizableClass;
use Sam\Stat\UpcomingAuction\Cli\Command\GetUpcomingAuctionStatCommand;
use Symfony\Component\Console\Application;

/**
 * Class UpcomingAuctionStatCliApplication
 * @package Sam\Stat\UpcomingAuction\Cli
 */
class UpcomingAuctionStatCliApplication extends CustomizableClass
{
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
     */
    public function run(): int
    {
        $application = new Application();
        $application->add(new GetUpcomingAuctionStatCommand());
        $application->setDefaultCommand(GetUpcomingAuctionStatCommand::NAME, true);
        return $application->run();
    }
}
