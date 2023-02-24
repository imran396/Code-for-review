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

class DbMigrationConstants
{
    public const DB_MIG_NAMESPACE = 'SAM\Migrations';
    public const DB_MIG_DB_FOR_DIFF = 'tmp_migration_diff';
    public const DB_MIG_PATH_FROM_SYS_ROOT = 'src/db/migration';
    public const DB_SCHEMA_PATH_FROM_SYS_ROOT = 'bin/migrate/db/schema';
    public const DB_MIG_VERSIONS_TABLE = 'doctrine_migration_versions';
}
