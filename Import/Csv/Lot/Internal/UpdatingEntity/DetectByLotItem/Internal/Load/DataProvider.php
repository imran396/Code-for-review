<?php
/**
 * SAM-9462: Lot CSV import - fix item# and lot# uniqueness check
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

namespace Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByLotItem\Internal\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\LotItem\ItemNo\Parse\LotItemNoParsed;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\ItemNo\Parse\LotItemNoParser;
use Sam\Lot\Load\LotItemLoader;

/**
 * Class DataProvider
 * @package Sam\Import\Csv\Lot
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadLotItemIdByItemNoParsed(
        LotItemNoParsed $itemNoParsed,
        int $accountId,
        bool $isReadOnlyDb = false
    ): ?int {
        $row = LotItemLoader::new()->loadSelectedByItemNoParsed(
            ['li.id'],
            $itemNoParsed,
            $accountId,
            $isReadOnlyDb
        );
        return Cast::toInt($row['id'] ?? null);
    }

    public function parseItemNo(string $itemNo): LotItemNoParsed
    {
        return LotItemNoParser::new()->construct()->parse($itemNo);
    }
}
