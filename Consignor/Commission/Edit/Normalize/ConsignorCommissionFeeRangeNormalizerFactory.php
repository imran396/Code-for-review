<?php
/**
 * SAM-11418: Avoid number formatting in API
 * SAM-7974: Multiple Consignor commission rates and unsold commission extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Normalize;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * Class ConsignorCommissionRangeDtoNormalizer
 * @package Sam\Consignor\Commission\Edit
 */
class ConsignorCommissionFeeRangeNormalizerFactory extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function create(Mode $mode): ConsignorCommissionFeeRangeNormalizerInterface
    {
        if ($mode->isSoap()) {
            return ConsignorCommissionFeeRangeNormalizerForSoap::new();
        }

        return ConsignorCommissionFeeRangeNormalizer::new();
    }
}
