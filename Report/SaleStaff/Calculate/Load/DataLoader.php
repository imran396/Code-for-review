<?php
/**
 * SAM-4633: Refactor sales staff report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 17, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SaleStaff\Calculate\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\UserSalesCommission\UserSalesCommissionReadRepositoryCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class DataLoader
 * @package Sam\Report\SaleStaff\Calculate\Load
 */
class DataLoader extends CustomizableClass
{
    use UserLoaderAwareTrait;
    use UserSalesCommissionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $accountId
     * @param int $userId
     * @param float|null $amount
     * @return array
     */
    public function loadSalesCommissions(?int $accountId, int $userId, ?float $amount): array
    {
        $salesCommissions = $this->createUserSalesCommissionReadRepository()
            ->filterAccountId($accountId)
            ->filterUserId($userId)
            ->filterAmountLessOrEqual($amount)
            ->orderByAmount(false)
            ->select(
                [
                    'amount',
                    'percent',
                ]
            )
            ->loadRows();
        return $salesCommissions;
    }
}
