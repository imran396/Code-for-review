<?php
/**
 * SAM-10802: Supply uniqueness of auction lot fields: lot#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Lock\LotNo\Internal\Detect\Internal\Load;

use AuctionLotItem;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\LotNo\Parse\LotNoParserCreateTrait;
use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\EntityMaker\AuctionLot\Lock\LotNo\Internal\Detect\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use LotNoParserCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAuctionLot(?int $auctionLotId, bool $isReadOnlyDb = false): ?AuctionLotItem
    {
        return $this->getAuctionLotLoader()->loadById($auctionLotId, $isReadOnlyDb);
    }

    public function validateLotNo(string $lotFullNum): bool
    {
        return $this->createLotNoParser()
            ->construct()
            ->validate($lotFullNum);
    }

    public function parseLotNo(string $lotFullNum): LotNoParsed
    {
        return $this->createLotNoParser()
            ->construct()
            ->parse($lotFullNum);
    }
}
