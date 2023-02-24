<?php
/**
 * SAM-6923: Login to bid, completing signup is not redirecting into the auction registration process
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Login\TargetUrl\Internal\Load;

use Auction;
use Sam\Application\Landing\Responsive\ResponsiveLandingPageDetector;
use Sam\Auction\Load\AuctionLoader;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderChecker;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Responsive\Login\TargetUrl\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isAuctionRegistered(int $editorUserId, int $auctionId): bool
    {
        return AuctionBidderChecker::new()->isAuctionRegistered($editorUserId, $auctionId);
    }

    public function detectLandingUrl(int $systemAccountId): string
    {
        $detector = ResponsiveLandingPageDetector::new()->construct($systemAccountId);
        $detector->detect();
        return $detector->getRedirectUrl();
    }

    public function loadAuction(int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
    }
}
