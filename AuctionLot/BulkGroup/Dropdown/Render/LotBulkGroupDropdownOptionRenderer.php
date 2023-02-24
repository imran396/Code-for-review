<?php
/**
 * SAM-6376 : Lot bulk group drop-down rendering
 * https://bidpath.atlassian.net/browse/SAM-6376
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 07, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Dropdown\Render;

use Sam\AuctionLot\BulkGroup\Dropdown\Load\DataLoader;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Core\Constants;

/**
 * Class LotBulkGroupDropdownOptionRenderer
 * @package Sam\AuctionLot\BulkGroup\Dropdown\Render
 */
class LotBulkGroupDropdownOptionRenderer extends CustomizableClass
{
    use AuctionAwareTrait;
    use LotRendererAwareTrait;
    use OptionalsTrait;

    public const OP_AUCTION_LOT_ITEM_ROWS = 'auctionLotRows';

    /** @var array */
    protected array $skipLotItemIds = [];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $auctionId null- means auction id does not exists
     * @param array $skipLotItemIds
     * @param array $optionals
     * @return $this
     */
    public function construct(?int $auctionId, array $skipLotItemIds = [], array $optionals = []): static
    {
        $this->setAuctionId($auctionId);
        $this->skipLotItemIds = $skipLotItemIds;
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Get Bulk Option for dropdown
     * @return array options
     */
    public function makeArray(): array
    {
        /*
         * - none (NOTE: not a bulk master lot and not assigned to a bulk group)
         * - bulk master lot (NOTE: this makes this lot a bulk master lot)
         * - list of bulk master lots ordered by default lot order "lot# - name (item#)" (NOTE: This adds a lot to a bulk group)
         * */
        $bulkOptions = Constants\LotBulkGroup::LBGR_NAMES;
        $auctionLotRows = $this->fetchOptional(self::OP_AUCTION_LOT_ITEM_ROWS);
        foreach ($auctionLotRows as $row) {
            $lotNo = $this->getLotRenderer()->makeLotNo((int)$row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
            $itemNo = $this->getLotRenderer()->makeItemNo((int)$row['item_num'], $row['item_num_ext']);
            $bulkOptions[$lotNo] = sprintf('%s - %s (%s)', $lotNo, $row['lot_name'], $itemNo);
        }
        return $bulkOptions;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $auctionId = $this->getAuctionId();
        $skipLotItemIds = $this->skipLotItemIds;
        $optionals[self::OP_AUCTION_LOT_ITEM_ROWS] = $optionals[self::OP_AUCTION_LOT_ITEM_ROWS]
            ?? static function () use ($auctionId, $skipLotItemIds): array {
                return DataLoader::new()->load($auctionId, $skipLotItemIds);
            };
        $this->setOptionals($optionals);
    }
}
