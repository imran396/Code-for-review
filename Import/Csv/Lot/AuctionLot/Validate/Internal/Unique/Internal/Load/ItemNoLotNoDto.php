<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Unique\Internal\Load;

use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\LotItem\ItemNo\Parse\LotItemNoParsed;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ItemNoLotNoDto
 * @package Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Unique\Internal\Load
 */
class ItemNoLotNoDto extends CustomizableClass
{
    /** @var LotItemNoParsed */
    public LotItemNoParsed $itemNoParsed;
    /** @var LotNoParsed */
    public LotNoParsed $lotNoParsed;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItemNoParsed $itemNoParsed
     * @param LotNoParsed $lotNoParsed
     * @return $this
     */
    public function construct(LotItemNoParsed $itemNoParsed, LotNoParsed $lotNoParsed): static
    {
        $this->itemNoParsed = $itemNoParsed;
        $this->lotNoParsed = $lotNoParsed;
        return $this;
    }

    public function fromDbRow(array $row): static
    {
        return $this->construct(
            LotItemNoParsed::new()->construct($row['item_num'], $row['item_num_ext']),
            LotNoParsed::new()->construct($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix'])
        );
    }
}
