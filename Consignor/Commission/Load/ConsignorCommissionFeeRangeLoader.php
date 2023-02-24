<?php
/**
 * SAM-7974: Multiple Consignor commission rates and unsold commission extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Load;

use ConsignorCommissionFeeRange;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\ConsignorCommissionFeeRange\ConsignorCommissionFeeRangeReadRepositoryCreateTrait;

/**
 * This class contains methods for fetching consignor commission fee range from DB
 *
 * Class ConsignorCommissionFeeRangeLoader
 * @package Sam\Consignor\Commission\Load
 */
class ConsignorCommissionFeeRangeLoader extends CustomizableClass
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
     * Fetch consignor commission fee range by id
     *
     * @param int|null $id
     * @param bool $isReadOnlyDb
     * @return ConsignorCommissionFeeRange|null
     */
    public function load(?int $id, bool $isReadOnlyDb = false): ?ConsignorCommissionFeeRange
    {
        if (!$id) {
            return null;
        }
        $range = $this->createConsignorCommissionFeeRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($id)
            ->filterActive(true)
            ->loadEntity();
        return $range;
    }

    /**
     * Fetch ranges for certain consignor commission fee rule
     *
     * @param int|null $consignorCommissionFeeId
     * @param bool $isReadOnlyDb
     * @return ConsignorCommissionFeeRange[]
     */
    public function loadForConsignorCommissionFee(?int $consignorCommissionFeeId, bool $isReadOnlyDb = false): array
    {
        if (!$consignorCommissionFeeId) {
            return [];
        }
        $ranges = $this->createConsignorCommissionFeeRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterConsignorCommissionFeeId($consignorCommissionFeeId)
            ->filterActive(true)
            ->orderByAmount()
            ->loadEntities();
        return $ranges;
    }
}
