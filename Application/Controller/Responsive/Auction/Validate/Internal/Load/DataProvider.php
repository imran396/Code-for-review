<?php
/**
 * SAM-6796: Validations at controller layer for v3.5 - AuctionControllerValidator at responsive site
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           31 Mar 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Auction\Validate\Internal\Load;

use Auction;
use Sam\Account\DomainAuctionVisibility\VisibilityChecker;
use Sam\Auction\Load\AuctionLoader;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Responsive\Auction\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $auctionId null leads to null
     * @param bool $isReadOnlyDb
     * @return Auction|null
     */
    public function loadAuction(?int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
    }

    /**
     * @param Auction $auction
     * @return bool
     */
    public function isAllowedForAuction(Auction $auction): bool
    {
        return VisibilityChecker::new()->isAllowedForAuction($auction);
    }
}
