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

namespace Sam\Settlement\Check\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\SettlementCheck\SettlementCheckReadRepositoryCreateTrait;
use SettlementCheck;

/**
 * Class SettlementCheckLoader
 * @package Sam\Settlement\Check
 */
class SettlementCheckLoader extends CustomizableClass
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

    public function load(?int $settlementCheckId, bool $isReadOnlyDb = false): ?SettlementCheck
    {
        if (!$settlementCheckId) {
            return null;
        }

        return $this->createSettlementCheckReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($settlementCheckId)
            ->loadEntity();
    }

    /**
     * @param array $settlementCheckIds
     * @param bool $isReadOnlyDb
     * @return SettlementCheck[]
     */
    public function loadByIds(array $settlementCheckIds, bool $isReadOnlyDb = false): array
    {
        if (!$settlementCheckIds) {
            return [];
        }

        return $this->createSettlementCheckReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($settlementCheckIds)
            ->loadEntities();
    }

    /**
     * @param array $settlementCheckIds
     * @param bool $isReadOnlyDb
     * @return SettlementCheck[]
     */
    public function yieldByIds(array $settlementCheckIds, bool $isReadOnlyDb = false): iterable
    {
        if (!$settlementCheckIds) {
            return [];
        }

        return $this->createSettlementCheckReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($settlementCheckIds)
            ->yieldEntities();
    }

    /**
     * @param int|null $settlementId
     * @param bool $isReadOnlyDb
     * @return SettlementCheck[]
     */
    public function loadBySettlementId(?int $settlementId, bool $isReadOnlyDb = false): array
    {
        if (!$settlementId) {
            return [];
        }

        return $this->createSettlementCheckReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterSettlementId($settlementId)
            ->loadEntities();
    }

    /**
     * @param array $select
     * @param int|null $settlementId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelectedBySettlementId(array $select, ?int $settlementId, bool $isReadOnlyDb = false): array
    {
        if (!$settlementId) {
            return [];
        }

        return $this->createSettlementCheckReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterSettlementId($settlementId)
            ->select($select)
            ->loadRows();
    }

    /**
     * @param int $paymentId
     * @param bool $isReadOnlyDb
     * @return SettlementCheck[]
     */
    public function loadByPaymentId(int $paymentId, bool $isReadOnlyDb = false): array
    {
        if (!$paymentId) {
            return [];
        }

        return $this->createSettlementCheckReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterPaymentId($paymentId)
            ->loadEntities();
    }
}
