<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Delete;

use Sam\Consignor\Commission\Load\ConsignorCommissionFeeLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\ConsignorCommissionFee\ConsignorCommissionFeeWriteRepositoryAwareTrait;

/**
 * Class ConsignorCommissionFeeDeleter
 * @package Sam\Consignor\Commission\Delete
 */
class ConsignorCommissionFeeDeleter extends CustomizableClass
{
    use ConsignorCommissionFeeLoaderCreateTrait;
    use ConsignorCommissionFeeWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Mark ConsignorCommissionFee as deleted
     *
     * @param int $id
     * @param int $editorUserId
     */
    public function delete(int $id, int $editorUserId): void
    {
        $consignorCommissionFee = $this->createConsignorCommissionFeeLoader()->load($id);
        if (!$consignorCommissionFee) {
            log_error("Available Consignor commission fee not found" . composeSuffix(['id' => $id]));
            return;
        }
        $consignorCommissionFee->toSoftDeleted();
        $this->getConsignorCommissionFeeWriteRepository()->saveWithModifier($consignorCommissionFee, $editorUserId);
    }
}
