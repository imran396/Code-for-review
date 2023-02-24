<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ConsignorCommissionFeeEditForm\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\ConsignorCommissionFeeRange\ConsignorCommissionFeeRangeReadRepositoryCreateTrait;

/**
 * Class ConsignorCommissionFeePanelLoader
 * @package Sam\View\Admin\Form\ConsignorCommissionFeeEditForm\Load
 * @internal
 */
class ConsignorCommissionFeePanelLoader extends CustomizableClass
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
     * Fetch consignor commission fee ranges data for edit form table
     *
     * @param int|null $consignorCommissionFeeId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadConsignorCommissionFeeRangeRows(?int $consignorCommissionFeeId, bool $isReadOnlyDb = false): array
    {
        if (!$consignorCommissionFeeId) {
            return [];
        }
        $ranges = $this->createConsignorCommissionFeeRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterConsignorCommissionFeeId($consignorCommissionFeeId)
            ->orderByAmount()
            ->select(['amount', 'fixed', 'percent', 'mode'])
            ->loadRows();
        return $ranges;
    }
}
