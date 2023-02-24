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

namespace Sam\Consignor\Commission\Delete;

use Sam\Consignor\Commission\Load\ConsignorCommissionFeeRangeLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\ConsignorCommissionFeeRange\ConsignorCommissionFeeRangeWriteRepositoryAwareTrait;

/**
 * Class ConsignorCommissionFeeRangeDeleter
 * @package Sam\Consignor\Commission\Delete
 */
class ConsignorCommissionFeeRangeDeleter extends CustomizableClass
{
    use ConsignorCommissionFeeRangeLoaderCreateTrait;
    use ConsignorCommissionFeeRangeWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Mark ConsignorCommissionFeeRange as deleted
     *
     * @param int $id
     * @param int $editorUserId
     */
    public function delete(int $id, int $editorUserId): void
    {
        $range = $this->createConsignorCommissionFeeRangeLoader()->load($id, true);
        if (!$range) {
            log_error("Available Consignor commission range not found" . composeSuffix(['conscid' => $id]));
            return;
        }
        $range->toSoftDeleted();
        $this->getConsignorCommissionFeeRangeWriteRepository()->saveWithModifier($range, $editorUserId);
    }
}
