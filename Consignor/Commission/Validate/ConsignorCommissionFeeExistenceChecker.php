<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\ConsignorCommissionFee\ConsignorCommissionFeeReadRepositoryCreateTrait;

/**
 * This class contains methods for checking consignor commission fee existence in DB
 *
 * Class ConsignorCommissionFeeExistenceChecker
 * @package Sam\Consignor\Commission\Validate
 */
class ConsignorCommissionFeeExistenceChecker extends CustomizableClass
{
    use ConsignorCommissionFeeReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $id
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByIdAndAccountId(int $id, int $accountId, bool $isReadOnlyDb = false): bool
    {
        $isExist = $this->createConsignorCommissionFeeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($id)
            ->filterLevel(Constants\ConsignorCommissionFee::LEVEL_ACCOUNT)
            ->filterRelatedEntityId($accountId)
            ->exist();
        return $isExist;
    }

    /**
     * @param string $name
     * @param int $accountId
     * @param int|null $skipId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByNameAndAccountId(string $name, int $accountId, ?int $skipId = null, bool $isReadOnlyDb = false): bool
    {
        $repository = $this->createConsignorCommissionFeeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterLevel(Constants\ConsignorCommissionFee::LEVEL_ACCOUNT)
            ->filterName($name)
            ->filterRelatedEntityId($accountId);
        if ($skipId) {
            $repository = $repository->skipId($skipId);
        }
        $isExist = $repository->exist();
        return $isExist;
    }
}
