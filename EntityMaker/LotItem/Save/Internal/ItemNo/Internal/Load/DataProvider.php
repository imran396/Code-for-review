<?php
/**
 * SAM-10599: Supply uniqueness of lot item fields: item# - Adjust item# auto-assignment with internal locking
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Save\Internal\ItemNo\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Save\Internal\ItemNo\Internal\Suggest\LotItemNoAdviserCreateTrait;
use Sam\Lot\ItemNo\Parse\LotItemNoParserCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class DataProvider
 * @package Sam\EntityMaker\LotItem\Save\Internal\ItemNo\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use LotItemNoAdviserCreateTrait;
    use LotItemNoParserCreateTrait;
    use SettingsManagerAwareTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function suggestItemNo(
        int $lotAccountId,
        array $skipItemNums = [],
        bool $isAvoidDeleted = false,
        bool $isReadOnlyDb = false
    ): ?int {
        return $this->createLotItemNoAdviser()->suggest($lotAccountId, $skipItemNums, $isAvoidDeleted, $isReadOnlyDb);
    }

    public function parseItemNo(string $itemFullNum): array
    {
        return $this->createLotItemNoParser()
            ->construct()
            ->parse($itemFullNum)
            ->toArray();
    }

    public function isItemNumLock(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::ITEM_NUM_LOCK, $accountId);
    }
}
