<?php
/**
 * Interface for data manager for bidder outstanding limit calculation
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Mar 27, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\Outstanding\Storage;

use Sam\Core\Service\CustomizableClassInterface;

/**
 * Interface IDataManager
 * @package Sam\Bidder\Outstanding\Storage
 */
interface IDataManager extends CustomizableClassInterface
{
    /**
     * Calculate "Spent" value
     * @param int $auctionId
     * @param int $userId
     * @return float
     */
    public function calcSpent(int $auctionId, int $userId): float;

    /**
     * Calculate "Collected" value
     * @param int $auctionId
     * @param int $userId
     * @return float
     */
    public function calcCollected(int $auctionId, int $userId): float;

    /**
     * Return auction bidder ids, who have exceeded outstanding limit
     * @param int $auctionId
     * @return array
     */
    public function findExceededLimitAuctionBidderIds(int $auctionId): array;
}
