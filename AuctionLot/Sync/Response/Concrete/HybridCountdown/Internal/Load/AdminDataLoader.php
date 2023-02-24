<?php
/**
 * Data loader for hybrid countdown(admin side)
 *
 * SAM-6388: Active countdown on admin - auction - lots
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/21/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;


/**
 * Class AdminDataLoader
 * @package Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\Load
 */
class AdminDataLoader extends CustomizableClass implements DataLoaderInterface
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
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function load(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        $row = $this->prepareRepository($lotItemId, $auctionId, $isReadOnlyDb)->loadRow();
        $orderNum = Cast::toInt($row['orderNum'] ?? null);
        return $orderNum;
    }


    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemReadRepository
     */
    protected function prepareRepository(int $lotItemId, int $auctionId, bool $isReadOnlyDb): AuctionLotItemReadRepository
    {
        $select = [
            'ali.order AS order_num',
        ];
        return $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinLotItemFilterActive(true)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->select($select);
    }
}
