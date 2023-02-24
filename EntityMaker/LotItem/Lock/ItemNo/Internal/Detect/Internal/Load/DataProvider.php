<?php
/**
 * SAM-10590: Supply uniqueness of lot item fields: item# - Implement unit test
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

namespace Sam\EntityMaker\LotItem\Lock\ItemNo\Internal\Detect\Internal\Load;

use LotItem;
use Sam\Core\LotItem\ItemNo\Parse\LotItemNoParsed;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\ItemNo\Parse\LotItemNoParserCreateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;

/**
 * Class DataProvider
 * @package Sam\EntityMaker\LotItem\Lock\ItemNo\Internal\Detect\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use LotItemLoaderAwareTrait;
    use LotItemNoParserCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadLotItem(?int $lotItemId, bool $isReadOnlyDb = false): ?LotItem
    {
        return $this->getLotItemLoader()->load($lotItemId, $isReadOnlyDb);
    }

    public function validateItemNo(string $itemFullNum): bool
    {
        return $this->createLotItemNoParser()
            ->construct()
            ->validate($itemFullNum);
    }

    public function parseItemNo(string $itemFullNum): LotItemNoParsed
    {
        return $this->createLotItemNoParser()
            ->construct()
            ->parse($itemFullNum);
    }
}
