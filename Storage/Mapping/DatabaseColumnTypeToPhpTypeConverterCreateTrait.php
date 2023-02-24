<?php
/**
 * SAM-9875: Implement a code generator for parent repository classes
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

/**
 * Trait DatabaseColumnTypeToPhpTypeConverterCreateTrait
 * @package Sam\Storage\Mapping
 */
trait DatabaseColumnTypeToPhpTypeConverterCreateTrait
{
    protected ?DatabaseColumnTypeToPhpTypeConverter $databaseColumnTypeToPhpTypeConverter = null;

    /**
     * @return DatabaseColumnTypeToPhpTypeConverter
     */
    protected function createDatabaseColumnTypeToPhpTypeConverter(): DatabaseColumnTypeToPhpTypeConverter
    {
        return $this->databaseColumnTypeToPhpTypeConverter ?: DatabaseColumnTypeToPhpTypeConverter::new();
    }

    /**
     * @param DatabaseColumnTypeToPhpTypeConverter $databaseColumnTypeToPhpTypeConverter
     * @return static
     * @internal
     */
    public function setDatabaseColumnTypeToPhpTypeConverter(DatabaseColumnTypeToPhpTypeConverter $databaseColumnTypeToPhpTypeConverter): static
    {
        $this->databaseColumnTypeToPhpTypeConverter = $databaseColumnTypeToPhpTypeConverter;
        return $this;
    }
}
