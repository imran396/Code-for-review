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
 * Trait DatabaseColumnMetadataProviderCreateTrait
 * @package Sam\Storage\Metadata
 */
trait DatabaseColumnMetadataProviderCreateTrait
{
    protected ?DatabaseColumnMetadataProvider $databaseColumnMetadataProvider = null;

    /**
     * @return DatabaseColumnMetadataProvider
     */
    protected function createDatabaseColumnMetadataProvider(): DatabaseColumnMetadataProvider
    {
        return $this->databaseColumnMetadataProvider ?: DatabaseColumnMetadataProvider::new();
    }

    /**
     * @param DatabaseColumnMetadataProvider $databaseColumnMetadataProvider
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setDatabaseColumnMetadataProvider(DatabaseColumnMetadataProvider $databaseColumnMetadataProvider): static
    {
        $this->databaseColumnMetadataProvider = $databaseColumnMetadataProvider;
        return $this;
    }
}
