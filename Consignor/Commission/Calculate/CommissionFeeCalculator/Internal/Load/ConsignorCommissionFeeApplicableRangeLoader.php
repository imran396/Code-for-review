<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Calculate\CommissionFeeCalculator\Internal\Load;

use ConsignorCommissionFeeRange;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\ConsignorCommissionFeeRange\ConsignorCommissionFeeRangeReadRepository;
use Sam\Storage\ReadRepository\Entity\ConsignorCommissionFeeRange\ConsignorCommissionFeeRangeReadRepositoryCreateTrait;

/**
 * This class contains methods that load applicable commission and fee ranges for sliding or tiered calculation methods.
 *
 * Class ConsignorCommissionFeeApplicableRangeLoader
 * @package Sam\Consignor\Commission\Calculate\Internal\Load
 * @interanl
 */
class ConsignorCommissionFeeApplicableRangeLoader extends CustomizableClass
{
    use ConsignorCommissionFeeRangeReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns the first range of consignor commission fee rule that is less than or equal to the reference amount
     *
     * @param int $consignorCommissionFeeId
     * @param float $referenceAmount
     * @param bool $isReadOnlyDb
     * @return ConsignorCommissionFeeRange|null
     */
    public function loadForSliding(int $consignorCommissionFeeId, float $referenceAmount, bool $isReadOnlyDb = false): ?ConsignorCommissionFeeRange
    {
        $ranges = $this->createRepository($consignorCommissionFeeId, $referenceAmount, $isReadOnlyDb)->loadEntity();
        return $ranges;
    }

    /**
     * Return all ranges of consignor commission fee rule that is less than or equal to the reference amount.
     * Ranges ordered by amount from high to low
     *
     * @param int $consignorCommissionFeeId
     * @param float $referenceAmount
     * @param bool $isReadOnlyDb
     * @return ConsignorCommissionFeeRange[]
     */
    public function loadForTiered(int $consignorCommissionFeeId, float $referenceAmount, bool $isReadOnlyDb = false): array
    {
        $ranges = $this->createRepository($consignorCommissionFeeId, $referenceAmount, $isReadOnlyDb)->loadEntities();
        return $ranges;
    }

    /**
     * @param int $consignorCommissionFeeId
     * @param float $referenceAmount
     * @param bool $isReadOnlyDb
     * @return ConsignorCommissionFeeRangeReadRepository
     */
    protected function createRepository(
        int $consignorCommissionFeeId,
        float $referenceAmount,
        bool $isReadOnlyDb = false
    ): ConsignorCommissionFeeRangeReadRepository {
        $repository = $this->createConsignorCommissionFeeRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterAmountLessOrEqual($referenceAmount)
            ->filterConsignorCommissionFeeId($consignorCommissionFeeId)
            ->orderByAmount(false);
        return $repository;
    }
}
