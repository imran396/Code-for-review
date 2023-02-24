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
 * Class DatabaseColumnTypeName
 * @package Sam\Storage\Metadata
 */
final class DatabaseColumnTypeName
{
    public const BIGINT = 'bigint';
    public const BIT = 'bit';
    public const DECIMAL = 'decimal';
    public const DOUBLE = 'double';
    public const FLOAT = 'float';
    public const INT = 'int';
    public const MEDIUMINT = 'mediumint';
    public const SMALLINT = 'smallint';
    public const TINYINT = 'tinyint';

    public const BLOB = 'blob';
    public const CHAR = 'char';
    public const ENUM = 'enum';
    public const LONGBLOB = 'longblob';
    public const LONGTEXT = 'longtext';
    public const MEDIUMBLOB = 'mediumblob';
    public const MEDIUMTEXT = 'mediumtext';
    public const TEXT = 'text';
    public const TINYBLOB = 'TINYBLOB';
    public const TINYTEXT = 'tinytext';
    public const VARCHAR = 'varchar';

    public const DATE = 'date';
    public const DATETIME = 'datetime';
    public const TIME = 'time';
    public const TIMESTAMP = 'timestamp';
    public const YEAR = 'year';
}
