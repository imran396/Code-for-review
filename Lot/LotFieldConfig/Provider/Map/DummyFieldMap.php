<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Provider\Map;

use Sam\Core\Service\CustomizableClass;

/**
 * Class DummyFieldMap
 * @package Sam\Lot\LotFieldConfig\Provider\Map
 */
class DummyFieldMap extends CustomizableClass implements LotFieldMapInterface
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getFieldConfigIndex(string $field): ?string
    {
        return $field;
    }

    public function isAlwaysOptionalField(string $field): bool
    {
        return false;
    }
}
