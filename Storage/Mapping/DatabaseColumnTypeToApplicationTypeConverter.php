<?php
/**
 *  SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Mapping;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Metadata\DatabaseColumnType;

/**
 * Guesses application data type based on database column type
 *
 * Class DatabaseColumnTypeToApplicationTypeConverter
 * @package Sam\Storage\Mapping
 */
class DatabaseColumnTypeToApplicationTypeConverter extends CustomizableClass
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
            return 'T_BOOL';
        }
        if ($columnType->isInteger()) {
            return 'T_INTEGER';
        }
        if ($columnType->isFloat()) {
            return 'T_FLOAT';
        }
        if ($columnType->isBlob()) {
            return 'T_OBJECT';
        }
        return 'T_STRING';
    }
}
