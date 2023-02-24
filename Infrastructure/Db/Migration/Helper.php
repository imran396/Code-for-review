<?php
/**
 * SAM-11100 Integrate Doctrine DB Migration library
 *
 * Project        sam
 * @author        Georgi Nikolov
 * @version       SVN: $Id: $
 * @since         Aug 30, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Infrastructure\Db\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManager;
use Sam\Log\Support\SupportLoggerAwareTrait;

/**
 * Helper class for migrations versions classes
 */
class Helper extends CustomizableClass
{
    use SupportLoggerAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getMigrationFromDb(Connection $connection, string $version = ''): array
    {
        $where = '';
        if ($version !== '') {
            $where = "WHERE version = '" . addslashes($version) . "' ";
        }
        $select = $connection->executeQuery(
            "SELECT * FROM " . DbMigrationConstants::DB_MIG_VERSIONS_TABLE . " {$where};"
        );
        $versionResult = $select->fetchAllAssociative();
        return $versionResult;
    }

    public function checkIfSkipped(Connection $connection, string $version): string
    {
        $prefix = '';
        $versionInfo = $this->getMigrationFromDb($connection, $version);
        if (count($versionInfo) > 0 && $versionInfo[0]['executed_at'] === null) {
            $prefix = '<question>Skipped.</question> ';
        }
        return $prefix;
    }

    public function skipMigrationVersion(Connection $connection, string $version = ''): Result
    {
        return $connection->transactional(function (Connection $connection) use ($version): Result {
            return $connection->executeQuery(
                "INSERT INTO " . DbMigrationConstants::DB_MIG_VERSIONS_TABLE . "  (version,executed_at,execution_time) VALUES ('" . addslashes(
                    $version
                ) . "', NULL, NULL)
            ON DUPLICATE KEY UPDATE
                executed_at=NULL,
                execution_time=NULL
            ;"
            );
        });
    }

    /**
     * consider empty database if 1 or less table exists in db
     * return null if there was problem identifying table count
     */
    public function isDbEmpty(Connection $connection): ?bool
    {
        try {
            $dbName = $connection->getDatabase();
            $rowCount = $this->getRowCountForTable(
                $connection,
                'INFORMATION_SCHEMA.TABLES',
                'WHERE TABLE_SCHEMA = \'' . $dbName . '\''
            );
            return !($rowCount > 1);
        } catch (\Exception $e) {
            // if any exception happen, consider returning null to tell the caller that we cannot fetch information about db tables.
            return null;
        }
    }

    public function getRowCountForTable(Connection $connection, string $table, string $where = ''): int
    {
        $select = $connection->executeQuery(
            "SELECT count(*) AS ROWCOUNT FROM {$table} {$where};"
        );
        $result = $select->fetchAllAssociative();
        return $result[0]['ROWCOUNT'];
    }

}
