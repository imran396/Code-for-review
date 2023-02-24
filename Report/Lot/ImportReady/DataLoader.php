<?php
/**
 * SAM-4643: Refactor "CSV import-ready export" report
 * https://bidpath.atlassian.net/browse/SAM-4643
 *
 * @author        Oleg Kovalov
 * @since         Jan 18, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 */

namespace Sam\Report\Lot\ImportReady;

use AuctionLotItem;
use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Report\Lot\ImportReady
 */
class DataLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use FilterAuctionAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return AuctionLotItem[]
     */
    public function load(): array
    {
        $auctionLots = $this->loadLotsOrderedByLotNumber($this->getFilterAuctionId());
        return $auctionLots;
    }

    /**
     * @param int|int[] $auctionId
     * @return AuctionLotItem[]
     */
    protected function loadLotsOrderedByLotNumber(int|array|null $auctionId): array
    {
        $auctionLots = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->orderByLotNumPrefix()
            ->orderByLotNum()
            ->orderByLotNumExt()
            ->loadEntities();
        return $auctionLots;
    }
}
