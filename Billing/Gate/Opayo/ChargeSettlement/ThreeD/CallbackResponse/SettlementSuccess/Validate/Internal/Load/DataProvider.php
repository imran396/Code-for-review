<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\Validate\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Load\SettlementLoader;
use Sam\User\Validate\UserExistenceChecker;


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

    public function existSettlementById(?int $settlementId, bool $isReadOnlyDb = false): bool
    {
        return SettlementLoader::new()->load($settlementId, $isReadOnlyDb) !== null;
    }

    public function existUserById(?int $userId, bool $isReadOnlyDb = false): bool
    {
        return UserExistenceChecker::new()->existById($userId, $isReadOnlyDb);
    }

    public function existEditorUserById(?int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return UserExistenceChecker::new()->existById($editorUserId, $isReadOnlyDb);
    }
}
