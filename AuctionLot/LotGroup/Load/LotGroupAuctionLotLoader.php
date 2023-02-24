<?php
/**
 * SAM-5202: Apply constants for LotGroup lot grouping and refactor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/26/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotGroup\Load;

use AuctionLotItem;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class LotGroupAuctionLotLoader
 * @package Sam\AuctionLot\LotGroup\Load
 */
class LotGroupAuctionLotLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get AuctionLotItem records for a LotGroup id
     *
     * @param int $auctionId auction.id
     * @param int|null $groupId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem[]
     */
    public function loadAvailable(int $auctionId, ?int $groupId, bool $isReadOnlyDb = false): array
    {
        if (!$groupId) {
            return [];
        }
        return $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterGroupId($groupId)
            ->filterLotStatusId([Constants\Lot::LS_ACTIVE, Constants\Lot::LS_UNSOLD])
            ->orderByOrderAndLotFullNumber()
            ->loadEntities();
    }
}
