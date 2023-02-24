<?php
/**
 * SAM-8833: Lot item entity maker - extract item# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\ItemNo\Internal\Validate\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;
use Sam\Lot\Validate\LotItemExistenceChecker;

/**
 * Class DataProvider
 * @package Sam\EntityMaker\LotItem
 */
class DataProvider extends CustomizableClass
{
    use LotFieldConfigProviderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $itemNum
     * @param string $itemNumExt
     * @param int $accountId
     * @param array $skipLotItemIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByItemNum(
        int $itemNum,
        string $itemNumExt,
        int $accountId,
        array $skipLotItemIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        return LotItemExistenceChecker::new()->existByItemNum(
            $itemNum,
            $itemNumExt,
            $accountId,
            $skipLotItemIds,
            $isReadOnlyDb
        );
    }

    /**
     * Check if item number is marked as required
     *
     * @param int $accountId
     * @return bool
     */
    public function isRequiredByLotFieldConfig(int $accountId): bool
    {
        $isRequired = $this->getLotFieldConfigProvider()->isRequired(
            Constants\LotFieldConfig::ITEM_NUMBER,
            $accountId
        );
        return $isRequired;
    }
}
