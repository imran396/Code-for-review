<?php
/**
 * SAM-4741: SyncNamespace loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-02-10
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SyncNamespace\Load;

use Sam\Core\Filter\Availability\FilterSyncNamespaceAvailabilityAwareTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\SyncNamespace\SyncNamespaceReadRepository;
use Sam\Storage\ReadRepository\Entity\SyncNamespace\SyncNamespaceReadRepositoryCreateTrait;
use SyncNamespace;

/**
 * Class SyncNamespaceLoader
 * @package Sam\SyncNamespace\Load
 */
class SyncNamespaceLoader extends EntityLoaderBase
{
    use FilterSyncNamespaceAvailabilityAwareTrait;
    use SyncNamespaceReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->filterSyncNamespaceActive(true);
        return $this;
    }

    /**
     * Drop filtering by `active` column
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterSyncNamespace();
        return $this;
    }

    /**
     * @param int|null $id
     * @param bool $isReadOnlyDb
     * @return SyncNamespace|null
     */
    public function load(?int $id, bool $isReadOnlyDb = false): ?SyncNamespace
    {
        if (!$id) {
            return null;
        }
        $syncNamespace = $this->prepareRepository($isReadOnlyDb)
            ->filterId($id)
            ->loadEntity();
        return $syncNamespace;
    }

    /**
     * Load an array of SyncNamespace objects,
     * by Name Index(es)
     * @param string $name
     * @param bool $active
     * @return SyncNamespace[]
     */
    public function loadByName(string $name, bool $active = true): array
    {
        $syncNamespace = $this->prepareRepository()
            ->filterName($name)
            ->filterActive($active)
            ->loadEntities();
        return $syncNamespace;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return SyncNamespaceReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): SyncNamespaceReadRepository
    {
        $repo = $this->createSyncNamespaceReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterSyncNamespaceActive()) {
            $repo->filterActive($this->getFilterSyncNamespaceActive());
        }
        return $repo;
    }
}
