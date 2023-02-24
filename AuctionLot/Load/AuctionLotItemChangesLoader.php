<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Load;

use AuctionLotItemChanges;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItemChanges\AuctionLotItemChangesReadRepositoryCreateTrait;

/**
 * Class AuctionLotItemChangesLoader
 * @package Sam\AuctionLot\Load
 */
class AuctionLotItemChangesLoader extends CustomizableClass
{
    use AuctionLotItemChangesReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load a AuctionLotItemChanges
     * @param int|null $userId
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb query to read-only db
     * @return AuctionLotItemChanges|null
     */
    public function load(?int $userId, ?int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?AuctionLotItemChanges
    {
        if (
            !$userId
            || !$lotItemId
            || !$auctionId
        ) {
            return null;
        }

        $auctionLotChanges = $this->createAuctionLotItemChangesReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterUserId($userId)
            ->loadEntity();
        return $auctionLotChanges;
    }
}
