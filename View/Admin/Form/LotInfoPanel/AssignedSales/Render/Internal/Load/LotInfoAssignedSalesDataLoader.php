<?php
/**
 * SAM-6717: Refactor assigned sales label at Lot Item Edit page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 25, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\AssignedSales\Render\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class LotInfoAssignedSalesDataLoader
 * @package Sam\View\Admin\Form\LotInfoPanel\AssignedSales\Render\Internal\Load
 */
class LotInfoAssignedSalesDataLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;

    protected int $filterLotItemId;
    protected ?int $skipAuctionId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $filterLotItemId
     * @param int|null $skipAuctionId null - no auction to skip
     * @return $this
     */
    public function construct(int $filterLotItemId, ?int $skipAuctionId): static
    {
        $this->filterLotItemId = $filterLotItemId;
        $this->skipAuctionId = $skipAuctionId;
        return $this;
    }

    /**
     * @return int - return value of Auction Lot Items count
     */
    public function count(): int
    {
        return $this->prepareAuctionLotItemRepository()->count();
    }

    /**
     * @return LotInfoAssignedSalesDto[] - return values for Auction Lot Items
     */
    public function load(): array
    {
        $dtos = [];
        $rows = $this->prepareAuctionLotItemRepository()->loadRows();
        foreach ($rows as $row) {
            $dtos[] = LotInfoAssignedSalesDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return AuctionLotItemReadRepository
     */
    private function prepareAuctionLotItemRepository(): AuctionLotItemReadRepository
    {
        $repo = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->filterLotItemId($this->filterLotItemId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses);
        if ($this->skipAuctionId) {
            $repo->skipAuctionId($this->skipAuctionId);
        }
        $repo->select(['ali.auction_id', 'a.sale_num', 'a.sale_num_ext']);
        return $repo;
    }
}
