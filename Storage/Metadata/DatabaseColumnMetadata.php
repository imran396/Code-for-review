<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Metadata;

/**
 * Database column metadata model
 *
 * Class ColumnMetadata
 * @package Sam\Storage\Metadata
 */
class DatabaseColumnMetadata
{
    private readonly string $tableName;
    private readonly string $columnName;
    private readonly DatabaseColumnType $type;
    private readonly bool $nullable;
    private readonly bool $primaryKey;

    /**
     * DatabaseColumnMetadata constructor.
     * @param string $table
     * @param string $name
     * @param DatabaseColumnType $type
     * @param bool $nullable
     * @param bool $primaryKey
     */
    public function __construct(
        string $table,
        string $name,
        DatabaseColumnType $type,
        bool $nullable = false,
        bool $primaryKey = false
    ) {
        $this->tableName = $table;
        $this->columnName = $name;
        $this->type = $type;
        $this->nullable = $nullable;
        $this->primaryKey = $primaryKey;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * @return DatabaseColumnType
     */
    public function getType(): DatabaseColumnType
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return bool
     */
    public function isPrimaryKey(): bool
    {
        return $this->primaryKey;
    }
}
