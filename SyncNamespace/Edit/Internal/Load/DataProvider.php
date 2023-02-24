<?php
/**
 * SAM-5826: Decouple SyncNamespace Editor to classes and add unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Feb 19, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SyncNamespace\Edit\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\SyncNamespace\SyncNamespaceReadRepositoryCreateTrait;
use Sam\SyncNamespace\Edit\Internal\Exception\CouldNotFindSyncNamespace;
use Sam\SyncNamespace\Validate\SyncNamespaceExistenceCheckerAwareTrait;
use SyncNamespace;

/**
 * Class DataProvider
 * @package Sam\SyncNamespace\Edit
 */
class DataProvider extends CustomizableClass
{
    use SyncNamespaceReadRepositoryCreateTrait;
    use SyncNamespaceExistenceCheckerAwareTrait;

    protected ?SyncNamespace $syncNamespace = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $syncNamespaceId
     * @return SyncNamespace
     * @throws CouldNotFindSyncNamespace
     */
    public function loadSyncNamespaceById(int $syncNamespaceId): SyncNamespace
    {
        if (!$this->syncNamespace) {
            $this->syncNamespace = $this->createSyncNamespaceReadRepository()
                ->filterId($syncNamespaceId)
                ->enableReadOnlyDb(true)
                ->loadEntity();
        }
        if (!$this->syncNamespace) {
            throw CouldNotFindSyncNamespace::withId($syncNamespaceId);
        }
        return $this->syncNamespace;
    }

    /**
     * @param string $name
     * @param int $accountId
     * @return bool
     */
    public function existByNameAndAccountId(string $name, int $accountId): bool
    {
        return $this->getSyncNamespaceExistenceChecker()->existByNameAndAccountId($name, $accountId);
    }

    /**
     * @param string $name
     * @param int $id
     * @param int $accountId
     * @return bool
     */
    public function existByNameAndIdAndAccountId(string $name, int $id, int $accountId): bool
    {
        return $this->getSyncNamespaceExistenceChecker()->existByNameAndIdAndAccountId($name, $id, $accountId);
    }
}
