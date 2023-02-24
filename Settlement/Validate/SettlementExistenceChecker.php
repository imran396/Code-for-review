<?php
/**
 *
 * SAM-4557: Settlement management modules
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

namespace Sam\Settlement\Validate;

use Sam\Core\Filter\EntityLoader\SettlementAllFilterTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\SettlementItem\SettlementItemReadRepositoryCreateTrait;

/**
 * Class SettlementExistenceChecker
 * @package Sam\Settlement\Validate
 */
class SettlementExistenceChecker extends CustomizableClass
{
    use SettlementAllFilterTrait;
    use SettlementItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->initFilter();
        return $this;
    }

    /**
     * Return if settlement_no exists
     *
     * @param int $settlementNo
     * @param int $accountId Settlement.account_id
     * @param int[] $skipSettlementIds array of settlement ids that should be skipped in search
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existBySettlementNo(
        int $settlementNo,
        int $accountId,
        array $skipSettlementIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        $settlementRepository = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterSettlementNo($settlementNo);
        if ($skipSettlementIds) {
            $settlementRepository->skipId($skipSettlementIds);
        }
        return $settlementRepository->exist();
    }

    /**
     * @param int|null $lotItemId - null means lot item id is absent
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByLotItemId(?int $lotItemId, bool $isReadOnlyDb = false): bool
    {
        $repo = $this->createSettlementItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterLotItemId($lotItemId);
        if ($this->hasFilterAccountActive()) {
            $repo->joinAccountFilterActive($this->getFilterAccountActive());
        }
        if ($this->hasFilterSettlementStatusId()) {
            $repo->joinSettlementFilterSettlementStatusId($this->getFilterSettlementStatusId());
        }
        $isFound = $repo->exist();
        return $isFound;
    }
}
