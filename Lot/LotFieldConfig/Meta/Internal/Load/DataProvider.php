<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Meta\Internal\Load;

use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\Lot\LotFieldConfig\Meta\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AccountLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isBuyNowSelectQuantityEnabledForAccount(int $accountId, bool $isReadOnlyDb = false): bool
    {
        return $this->getAccountLoader()->load($accountId, $isReadOnlyDb)->BuyNowSelectQuantityEnabled ?? false;
    }
}
