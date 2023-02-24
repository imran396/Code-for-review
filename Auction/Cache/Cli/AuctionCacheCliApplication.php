<?php
/**
 * SAM-6292: Move fields from auction_cache to auction_details_cache
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug. 31, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache\Cli;


use Exception;
use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Cache\Cli\Command\UpdateAuctionDetailsCacheCommand;
use Symfony\Component\Console\Application;

/**
 * Class AuctionCacheCliApplication
 * @package Sam\Auction\Cache\Cli
 */
class AuctionCacheCliApplication extends CustomizableClass
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
     * @throws Exception
     */
    public function run(): int
    {
        $application = new Application();
        $application->add(new UpdateAuctionDetailsCacheCommand());
        $application->setDefaultCommand(UpdateAuctionDetailsCacheCommand::NAME, true);

        return $application->run();
    }
}
