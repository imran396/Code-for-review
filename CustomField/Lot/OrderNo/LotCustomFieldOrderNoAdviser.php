<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\OrderNo;


use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepositoryCreateTrait;

/**
 * Class LotCustomFieldOrderNoAdviser
 * @package Sam\CustomField\Lot\OrderNo
 */
class LotCustomFieldOrderNoAdviser extends CustomizableClass
{
    use LotItemCustFieldReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Determine next available field order value
     *
     * @return int
     */
    public function suggest(): int
    {
        $row = $this->createLotItemCustFieldReadRepository()
            ->select(['MAX(`order`) AS max_order'])
            ->loadRow();
        $maxOrder = $row ? floor($row['max_order'] + 1) : 1;
        return (int)$maxOrder;
    }
}
