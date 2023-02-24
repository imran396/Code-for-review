<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 15, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\Cache;

use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Validate\AuctionStatusCheckerAwareTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;

/**
 * Class AuctionCheckerCache
 */
class AuctionCheckerCacher extends CustomizableClass
{
    use AuctionBidderCheckerAwareTrait;
    use AuctionStatusCheckerAwareTrait;
    use EditorUserAwareTrait;
    use UserFlagCacherAwareTrait;

    private array $closedAuctions = [];
    private array $approvedAuctions = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if auction is closed (cached)
     * @param int $auctionId
     * @return bool
     */
    public function isStatusClosed(int $auctionId): bool
    {
        if (!isset($this->closedAuctions[$auctionId])) {
            $this->closedAuctions[$auctionId] = $this->getAuctionStatusChecker()->isClosed($auctionId);
        }
        return $this->closedAuctions[$auctionId];
    }

    /**
     * Check if bidder approved in auction (cached)
     * @param int $auctionId
     * @return bool
     */
    public function isApproved(int $auctionId): bool
    {
        if (!$auctionId) {
            return false;
        }
        if (!$this->getEditorUserId()) {
            return false;
        }
        if (!isset($this->approvedAuctions[$auctionId])) {
            $this->approvedAuctions[$auctionId] = $this->getAuctionBidderChecker()
                ->isAuctionApproved($this->getEditorUserId(), $auctionId);
        }
        return $this->approvedAuctions[$auctionId];
    }
}
