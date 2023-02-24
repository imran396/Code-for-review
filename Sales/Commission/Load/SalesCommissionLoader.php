<?php
/**
 * Load sales commission
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           10/26/20
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sales\Commission\Load;

use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\UserSalesCommission\UserSalesCommissionReadRepositoryCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use UserSalesCommission;

/**
 * Class SalesCommissionLoader
 * @package Sam\Sales\Commission\Load
 */
class SalesCommissionLoader extends EntityLoaderBase
{
    use NumberFormatterAwareTrait;
    use UserSalesCommissionReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @param int $accountId
     * @return UserSalesCommission[]
     */
    public function loadForUser(int $userId, int $accountId): array
    {
        return $this->createUserSalesCommissionReadRepository()
            ->filterAccountId($accountId)
            ->filterUserId($userId)
            ->orderByAmount()
            ->loadEntities();
    }

    /**
     * @param int $userId
     * @param int $accountId
     * @return string
     */
    public function loadForUserAsString(int $userId, int $accountId): string
    {
        $userSalesCommissions = $this->loadForUser($userId, $accountId);
        if (!$userSalesCommissions) {
            return '';
        }

        $pairs = [];
        foreach ($userSalesCommissions as $key => $commission) {
            $pairs[] = ($key === 0 ? '' : $this->getNumberFormatter()->formatMoneyNto($commission->Amount) . ':')
                . $this->getNumberFormatter()->formatPercent($commission->Percent);
        }
        return implode('|', $pairs);
    }
}
