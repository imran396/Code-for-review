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

/**
 * Trait DatabaseColumnTypeToApplicationTypeConverterAwareTrait
 * @package Sam\Storage\Mapping
 */
trait DatabaseColumnTypeToApplicationTypeConverterAwareTrait
{
    protected ?DatabaseColumnTypeToApplicationTypeConverter $databaseColumnTypeToApplicationTypeConverter = null;

    /**
     * @return DatabaseColumnTypeToApplicationTypeConverter
     */
    protected function getDatabaseColumnTypeToApplicationTypeConverter(): DatabaseColumnTypeToApplicationTypeConverter
    {
        if ($this->databaseColumnTypeToApplicationTypeConverter === null) {
            $this->databaseColumnTypeToApplicationTypeConverter = DatabaseColumnTypeToApplicationTypeConverter::new();
        }
        return $this->databaseColumnTypeToApplicationTypeConverter;
    }

    /**
     * @param DatabaseColumnTypeToApplicationTypeConverter $databaseColumnTypeToApplicationTypeConverter
     * @return static
     * @internal
     */
    public function setDatabaseColumnTypeToApplicationTypeConverter(
        DatabaseColumnTypeToApplicationTypeConverter $databaseColumnTypeToApplicationTypeConverter
    ): static {
        $this->databaseColumnTypeToApplicationTypeConverter = $databaseColumnTypeToApplicationTypeConverter;
        return $this;
    }
}
