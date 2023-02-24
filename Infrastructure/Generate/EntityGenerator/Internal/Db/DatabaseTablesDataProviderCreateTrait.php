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

/**
 * Trait DatabaseTablesDataProviderCreateTrait
 * @package Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code\Internal
 * @internal
 */
trait DatabaseTablesDataProviderCreateTrait
{
    /**
     * @var DatabaseTablesDataProvider|null
     */
    protected ?DatabaseTablesDataProvider $databaseTablesDataProvider = null;

    /**
     * @return DatabaseTablesDataProvider
     */
    protected function createDatabaseTablesDataProvider(): DatabaseTablesDataProvider
    {
        return $this->databaseTablesDataProvider ?: DatabaseTablesDataProvider::new();
    }

    /**
     * @param DatabaseTablesDataProvider $databaseTablesDataProvider
     * @return static
     * @internal
     */
    public function setDatabaseTablesDataProvider(DatabaseTablesDataProvider $databaseTablesDataProvider): static
    {
        $this->databaseTablesDataProvider = $databaseTablesDataProvider;
        return $this;
    }
}
