<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Validate\Existence;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\SettlementCheck\SettlementCheckReadRepositoryCreateTrait;

/**
 * Class SettlementCheckExistenceChecker
 * @package Sam\Settlement\Check
 */
class SettlementCheckExistenceChecker extends CustomizableClass
{
    use SettlementCheckReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function countForSettlement(?int $settlementId, bool $isReadOnlyDb = false): int
    {
        if (!$settlementId) {
            return 0;
        }

        return $this->createSettlementCheckReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterSettlementId($settlementId)
            ->count();
    }

    public function existAppliedPayment(?int $settlementCheckId, bool $isReadOnlyDb = false): bool
    {
        if (!$settlementCheckId) {
            return false;
        }

        return $this->createSettlementCheckReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($settlementCheckId)
            ->skipPaymentId(null)
            ->exist();
    }

}
