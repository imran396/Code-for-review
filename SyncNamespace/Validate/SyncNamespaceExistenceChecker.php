<?php
/**
 *
 * SAM-4741: SyncNamespace loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-02-09
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SyncNamespace\Validate;

use Sam\Core\Filter\Availability\FilterSyncNamespaceAvailabilityAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\SyncNamespace\SyncNamespaceReadRepository;
use Sam\Storage\ReadRepository\Entity\SyncNamespace\SyncNamespaceReadRepositoryCreateTrait;

/**
 * Class SyncNamespaceExistenceChecker
 * @package Sam\SyncNamespace\Validate
 */
class SyncNamespaceExistenceChecker extends CustomizableClass
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
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterSyncNamespace();
        return $this;
    }

    /**
     * @param string $name
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByNameAndAccountId(string $name, int $accountId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterName($name)
            ->exist();
        return $isFound;
    }

    /**
     * @param string $name
     * @param int $id
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByNameAndIdAndAccountId(string $name, int $id, int $accountId, bool $isReadOnlyDb = false): bool
    {
        $isFound = false;
        $syncNamespace = $this->prepareRepository($isReadOnlyDb)
            ->filterId($id)
            ->loadEntity();
        if ($syncNamespace) {
            if ($syncNamespace->Name !== $name) {
                $isFound = $this->existByNameAndAccountId($name, $accountId, $isReadOnlyDb);
            }
        } else {
            $isFound = $this->existByNameAndAccountId($name, $accountId, $isReadOnlyDb);
        }
        return $isFound;
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
