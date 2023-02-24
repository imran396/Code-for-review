<?php
/**
 * SAM-9486: Entity factory class generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\Internal\Db;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DatabaseTablesDataProvider
 * @package Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code\Internal
 * @internal
 */
class DatabaseTablesDataProvider extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function load(): array
    {
        $dbName = $this->getDbName();
        $sql = <<<SQL
SELECT `table_name`
FROM information_schema.tables
WHERE table_type <> 'VIEW'
  AND table_schema = '{$dbName}'
SQL;
        $result = $this->query($sql);
        $tables = [];
        while ($row = $result->FetchRow()) {
            if (!in_array($row[0], Constants\Db::EXCLUDED_TABLES, true)) {
                $tables[] = $row[0];
            }
        }
        return $tables;
    }
}
