<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationFailed\Validate\Internal\Load;

use Sam\Auction\Validate\AuctionExistenceChecker;
use Sam\Core\Service\CustomizableClass;
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

    public function existUserById(?int $userId, bool $isReadOnlyDb = false): bool
    {
        return UserExistenceChecker::new()->existById($userId, $isReadOnlyDb);
    }

    public function existAuctionById(?int $auctionId, bool $isReadOnlyDb = false): bool
    {
        return AuctionExistenceChecker::new()->existById($auctionId, $isReadOnlyDb);
    }
}
