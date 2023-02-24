<?php
/**
 * SAM-10295: Enrich Settlement entity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 08, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\Settlement\Status;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementStatusPureChecker
 * @package Sam\Core\Entity\Model\Settlement\Status
 */
class SettlementStatusPureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if settlement status is "Pending"
     * @param int|null $settlementStatusId
     * @return bool
     */
    public function isPending(?int $settlementStatusId): bool
    {
        return $settlementStatusId === Constants\Settlement::SS_PENDING;
    }

    /**
     * Check if settlement status is "Paid"
     * @param int|null $settlementStatusId
     * @return bool
     */
    public function isPaid(?int $settlementStatusId): bool
    {
        return $settlementStatusId === Constants\Settlement::SS_PAID;
    }

    /**
     * Check if settlement status is "Deleted"
     * @param int|null $settlementStatusId
     * @return bool
     */
    public function isDeleted(?int $settlementStatusId): bool
    {
        return $settlementStatusId === Constants\Settlement::SS_DELETED;
    }

    /**
     * Check if settlement status is "Open"
     * @param int|null $settlementStatusId
     * @return bool
     */
    public function isOpen(?int $settlementStatusId): bool
    {
        return $settlementStatusId === Constants\Settlement::SS_OPEN;
    }

    public function isConsignorTaxHpExclusive(?int $consignorTaxHpType): bool
    {
        return $consignorTaxHpType === Constants\Consignor::TAX_HP_EXCLUSIVE;
    }

    public function isConsignorTaxHpInclusive(?int $consignorTaxHpType): bool
    {
        return $consignorTaxHpType === Constants\Consignor::TAX_HP_INCLUSIVE;
    }
}
