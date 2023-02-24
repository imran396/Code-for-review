<?php
/**
 * SAM-7974: Multiple Consignor commission rates and unsold commission extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Normalize;

/**
 * This interface contains methods for normalizing consignor commission fee range input data to be validated and persisted
 *
 * Interface ConsignorCommissionFeeRangeNormalizerInterface
 * @package Sam\Consignor\Commission\Edit\Normalize
 */
interface ConsignorCommissionFeeRangeNormalizerInterface
{
    /**
     * @param string $value
     * @return bool
     */
    public function isFloat(string $value): bool;

    /**
     * @param string $value
     * @return float
     */
    public function toFloat(string $value): float;

    /**
     * @param string|int $value
     * @return int
     */
    public function normalizeMode(string|int $value): int;
}
