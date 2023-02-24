<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Provider\Map;

/**
 * Interface LotFieldMapInterface
 * @package Sam\Lot\LotFieldConfig\Provider\Map
 */
interface LotFieldMapInterface
{
    /**
     * @param string $field
     * @return string|null NULL if mapping not found
     */
    public function getFieldConfigIndex(string $field): ?string;

    /**
     * @param string $field
     * @return bool
     */
    public function isAlwaysOptionalField(string $field): bool;
}
