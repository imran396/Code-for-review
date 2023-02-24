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

use Sam\Core\Service\CustomizableClass;
use Sam\Core\DataSource\DbConnectionTrait;

/**
 * Provider for database table column structure as database column metadata
 *
 * Class DbColumnMetadataProvider
 * @package Sam\Storage\Metadata
 */
class DatabaseColumnMetadataProvider extends CustomizableClass
{
    use DbConnectionTrait;

    private DatabaseColumnTypeBuilder $columnTypeBuilder;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initInstance(): static
    {
        $this->columnTypeBuilder = DatabaseColumnTypeBuilder::new();
        return $this;
    }

    /**
     * @param string $table
     * @return array|DatabaseColumnMetadata[]
     */
    public function getForTable(string $table): array
    {
        $columns = $this->getTableColumns($table);
        return array_map(
            function (array $columnInfo) use ($table) {
                return $this->buildMetadataForColumn($table, $columnInfo);
            },
            $columns
        );
    }

    /**
     * @param string $table
     * @param array $columnInfo
     * @return DatabaseColumnMetadata
     */
    private function buildMetadataForColumn(string $table, array $columnInfo): DatabaseColumnMetadata
    {
        return new DatabaseColumnMetadata(
            $table,
            $columnInfo['Field'],
            $this->detectColumnType($columnInfo),
            $this->detectIsNullableColumn($columnInfo),
            $this->detectIsPrimaryKeyColumn($columnInfo)
        );
    }

    /**
     * @param array $columnInfo
     * @return DatabaseColumnType
     */
    private function detectColumnType(array $columnInfo): DatabaseColumnType
    {
        return $this->columnTypeBuilder->buildFromDbColumnTypeRepresentation($columnInfo['Type']);
    }

    /**
     * @param array $columnInfo
     * @return bool
     */
    private function detectIsNullableColumn(array $columnInfo): bool
    {
        return $columnInfo['Null'] === 'YES';
    }

    /**
     * @param array $columnInfo
     * @return bool
     */
    private function detectIsPrimaryKeyColumn(array $columnInfo): bool
    {
        return $columnInfo['Key'] === 'PRI';
    }

    private function getTableColumns(string $table): array
    {
        $this->query("DESCRIBE `$table`");
        return $this->fetchAllAssoc();
    }
}
