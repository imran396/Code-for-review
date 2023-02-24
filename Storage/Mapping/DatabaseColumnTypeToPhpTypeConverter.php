<?php
/**
 * SAM-9875: Implement a code generator for read repository classes
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Mapping;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Metadata\DatabaseColumnType;

/**
 * Class DatabaseColumnTypeToPhpTypeConverter
 * @package Sam\Storage\Mapping
 */
class DatabaseColumnTypeToPhpTypeConverter extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param DatabaseColumnType $columnType
     * @return string
     */
    public function convert(DatabaseColumnType $columnType): string
    {
        if ($columnType->isBoolean()) {
            return 'bool';
        }
        if ($columnType->isInteger()) {
            return 'int';
        }
        if ($columnType->isFloat()) {
            return 'float';
        }
        return 'string';
    }
}
