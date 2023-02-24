<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Oct 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementFailed\Handle\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Load\SettlementLoader;
use Sam\User\Load\UserLoader;
use Settlement;
use User;

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

    public function loadSettlement(int $settlementId, bool $isReadOnlyDb = false): ?Settlement
    {
        return SettlementLoader::new()->load($settlementId, $isReadOnlyDb);
    }

    public function loadUser(int $userId, bool $isReadOnlyDb = false): ?User
    {
        return UserLoader::new()->load($userId, $isReadOnlyDb);
    }
}
