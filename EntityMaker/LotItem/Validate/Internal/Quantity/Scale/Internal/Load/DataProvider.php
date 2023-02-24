<?php
/**
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\Quantity\Scale\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class DataProvider
 * @package Sam\EntityMaker\LotItem
 */
class DataProvider extends CustomizableClass
{
    use LotCategoryLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function detectQuantityScaleForLotItem(int $lotItemId, bool $isReadOnlyDb = false): ?int
    {
        return $this->getLotItemLoader()->load($lotItemId, $isReadOnlyDb)->QuantityDigits ?? null;
    }

    public function detectQuantityScaleForAccount(int $accountId): int
    {
        return (int)$this->getSettingsManager()->get(Constants\Setting::QUANTITY_DIGITS, $accountId);
    }

    public function detectQuantityScaleForCategoryByName(string $categoryName, bool $isReadOnlyDb = false): ?int
    {
        return $this->getLotCategoryLoader()->loadByName(trim($categoryName), $isReadOnlyDb)->QuantityDigits ?? null;
    }

    public function detectQuantityScaleForCategoryById(int $categoryId, bool $isReadOnlyDb = false): ?int
    {
        return $this->getLotCategoryLoader()->load($categoryId, $isReadOnlyDb)->QuantityDigits ?? null;
    }

    public function detectQuantityScaleForLotItemMainCategory(int $lotItemId, bool $isReadOnlyDb = false): ?int
    {
        return $this->getLotCategoryLoader()->loadFirstForLot($lotItemId, $isReadOnlyDb)->QuantityDigits ?? null;
    }
}
