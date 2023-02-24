<?php
/**
 * SAM-7974: Multiple Consignor commission rates and unsold commission extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Load;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\UserConsignorCommissionFee\UserConsignorCommissionFeeReadRepositoryCreateTrait;
use UserConsignorCommissionFee;

/**
 * This class contains methods for fetching user consignor commission fee from DB
 *
 * Class UserConsignorCommissionFeeLoader
 * @package Sam\User\Load
 */
class UserConsignorCommissionFeeLoader extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use UserConsignorCommissionFeeReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Fetch user consignor commission fee by user id and account id
     *
     * @param int|null $userId
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return UserConsignorCommissionFee|null
     */
    public function load(?int $userId, ?int $accountId, bool $isReadOnlyDb = false): ?UserConsignorCommissionFee
    {
        if (!$userId || !$accountId) {
            return null;
        }

        $userConsignorCommissionFee = $this->createUserConsignorCommissionFeeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->filterAccountId($accountId)
            ->loadEntity();
        return $userConsignorCommissionFee;
    }

    /**
     * Fetch user consignor commission fee by user id and account id or create if it is not exist
     *
     * @param int $userId
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return UserConsignorCommissionFee
     */
    public function loadOrCreate(int $userId, int $accountId, bool $isReadOnlyDb = false): UserConsignorCommissionFee
    {
        $userConsignorCommissionFee = $this->load($userId, $accountId, $isReadOnlyDb);
        if (!$userConsignorCommissionFee) {
            $userConsignorCommissionFee = $this->createEntityFactory()->userConsignorCommissionFee();
            $userConsignorCommissionFee->UserId = $userId;
            $userConsignorCommissionFee->AccountId = $accountId;
        }
        return $userConsignorCommissionFee;
    }
}
