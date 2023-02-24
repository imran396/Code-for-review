<?php
/**
 * SAM-4366: Settlement No Adviser class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/12/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\SettlementNo;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Settlement\SettlementReadRepositoryCreateTrait;

/**
 * Class SettlementNoAdviser
 * @package Sam\Settlement\SettlementNo
 */
class SettlementNoAdviser extends CustomizableClass
{
    use SettlementReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get next available Settlement number
     *
     * @param int $accountId account_id
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function suggest(int $accountId, bool $isReadOnlyDb = false): int
    {
        $row = $this->createSettlementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterSettlementStatusId(Constants\Settlement::$availableSettlementStatuses)
            ->select(['MAX(settlement_no)+1 AS `next_set`'])
            ->loadRow();
        return (int)($row['next_set'] ?? 1);
    }
}
