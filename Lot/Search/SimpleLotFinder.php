<?php
/**
 * SAM-4716: Simple Lot Finder
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           22.12.2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Lot\Search;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;

/**
 * Class SimpleLotFinder
 * @package Sam\Lot\Search
 */
class SimpleLotFinder extends CustomizableClass
{
    use LotItemReadRepositoryCreateTrait;

    private bool $isReadOnlyDb = false;
    /**
     * @var int|int[]
     */
    private int|array|null $auctionId = null;

    /**
     * @var int|int[]
     */
    private int|array $lotStatusId;
    private ?string $searchKeyByName = null;
    private ?string $searchKeyByItemNo = null;
    private ?string $searchKeyByLotNo = null;

    /**
     * Class instantiated method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        parent::initInstance();
        $this->lotStatusId = Constants\Lot::$availableLotStatuses;
        return $this;
    }

    /**
     * @return array
     */
    public function loadRows(): array
    {
        $rows = $this->prepareRepository()
            ->select(
                [
                    'li.id',
                    'li.name',
                    'ali.lot_num',
                    'ali.lot_num_ext',
                    'ali.lot_num_prefix',
                    'li.item_num',
                    'li.item_num_ext',
                ]
            )
            ->loadRows();
        return $rows;
    }

    /**
     * @return LotItemReadRepository
     */
    protected function prepareRepository(): LotItemReadRepository
    {
        $repo = $this->createLotItemReadRepository()
            ->enableReadOnlyDb($this->isReadOnlyDb)
            ->joinAuctionLotItem()
            ->filterActive(true);

        if ($this->auctionId !== null) {
            $repo->joinAuctionLotItemFilterAuctionId($this->auctionId);
        }

        $repo->joinAuctionLotItemFilterLotStatusId($this->lotStatusId);

        if ((string)$this->searchKeyByName !== '') {
            $repo->likeName($this->searchKeyByName);
        }
        if ((string)$this->searchKeyByItemNo !== '') {
            $repo->likeItemNum($this->searchKeyByItemNo);
            $repo->likeItemNumExt($this->searchKeyByItemNo);
        }
        if ((string)$this->searchKeyByLotNo !== '') {
            $repo->joinAuctionLotItemLikeLotNum($this->searchKeyByLotNo);
            $repo->joinAuctionLotItemLikeLotNumExt($this->searchKeyByLotNo);
            $repo->joinAuctionLotItemLikeLotNumPrefix($this->searchKeyByLotNo);
        }
        return $repo;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return SimpleLotFinder
     */
    public function enableReadOnlyDb(bool $isReadOnlyDb): SimpleLotFinder
    {
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }

    /**
     * Filter results by ali.auction_id
     * @param int[] $auctionIds
     * @return SimpleLotFinder
     */
    public function filterAuctionIds(array $auctionIds): SimpleLotFinder
    {
        $this->auctionId = ArrayCast::makeIntArray($auctionIds, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * Filter results by ali.lot_status_id
     * @param int[] $lotStatusIds
     * @return SimpleLotFinder
     * @noinspection PhpUnused
     */
    public function filterLotStatusIds(array $lotStatusIds): SimpleLotFinder
    {
        $this->lotStatusId = ArrayCast::makeIntArray($lotStatusIds, Constants\Lot::$lotStatuses);
        return $this;
    }

    /**
     * Searching by li.name
     * @param string $name
     * @return SimpleLotFinder
     */
    public function searchByName(string $name): SimpleLotFinder
    {
        $this->searchKeyByName = $name;
        return $this;
    }

    /**
     * Searching by li.item_num
     * @param string $itemNo
     * @return SimpleLotFinder
     */
    public function searchByItemNo(string $itemNo): SimpleLotFinder
    {
        $this->searchKeyByItemNo = $itemNo;
        return $this;
    }

    /**
     * Searching by ali.lot_num_prefix, ali.lot_num_ext or ali.lot_num
     * @param string $lotNo
     * @return SimpleLotFinder
     */
    public function searchByLotNo(string $lotNo): SimpleLotFinder
    {
        $this->searchKeyByLotNo = $lotNo;
        return $this;
    }
}
