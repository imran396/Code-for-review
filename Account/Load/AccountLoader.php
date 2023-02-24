<?php
/**
 * Help methods for account loading
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 7, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Load;

use Account;
use Sam\Account\Load\Exception\CouldNotFindMainAccount;
use Sam\Account\Main\MainAccountDetectorCreateTrait;
use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Filter\EntityLoader\AccountAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\Account\AccountReadRepository;

/**
 * Class AccountLoader
 * @package Sam\Account\Load
 */
class AccountLoader extends EntityLoaderBase
{
    use AccountAllFilterTrait;
    use ApplicationAccessCheckerCreateTrait;
    use ConfigRepositoryAwareTrait;
    use EntityMemoryCacheManagerAwareTrait;
    use MainAccountDetectorCreateTrait;

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
        $this->initFilter();
        return $this;
    }

    /**
     * Load account by id with memory caching
     * @param int|null $accountId account.id;
     *      null - when we create new account at admin Manage accounts page (/admin/manage-account/create)
     *      or just open a admin Manage account page with accounts list (without <id> route param in /admin/manage-account/edit/id/%s)
     *      see url templates @param bool $isReadOnlyDb
     * @return Account|null
     * @see Constants\Url::A_MANAGE_ACCOUNT_EDIT
     * @see Constants\Url::$urlTemplates for url indexes
     * @see Constants\Url::A_MANAGE_ACCOUNT_CREATE
     */
    public function load(?int $accountId, bool $isReadOnlyDb = false): ?Account
    {
        if (!$accountId) {
            return null;
        }

        $fn = function () use ($accountId, $isReadOnlyDb) {
            $account = $this->prepareRepository($isReadOnlyDb)
                ->filterId($accountId)
                ->loadEntity();
            return $account;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::ACCOUNT_ID, $accountId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $account = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $account;
    }

    /**
     * Load main account or throw exception, when main account cannot be load,
     * because main account absence is exceptional situation, possibly configuration problem
     * @param bool $isReadOnlyDb
     * @return Account
     */
    public function loadMain(bool $isReadOnlyDb = false): Account
    {
        $account = $this->load($this->mainAccountId(), $isReadOnlyDb);
        if (!$account) {
            throw CouldNotFindMainAccount::withDefaultMessage();
        }
        return $account;
    }

    /**
     * Load active account by its name
     * @param string $name
     * @param bool $isReadOnlyDb
     * @return Account|null
     */
    public function loadByName(string $name, bool $isReadOnlyDb = false): ?Account
    {
        $account = $this->prepareRepository($isReadOnlyDb)
            ->filterName($name)
            ->loadEntity();
        return $account;
    }

    /**
     * Load active account by its url domain
     * @param string $urlDomain
     * @param bool $isReadOnlyDb
     * @return Account|null
     */
    public function loadByUrlDomain(string $urlDomain, bool $isReadOnlyDb = false): ?Account
    {
        if (strcasecmp($urlDomain, $this->cfg()->get('core->app->httpHost')) === 0) {
            return $this->loadMain($isReadOnlyDb);
        }

        $account = $this->prepareRepository($isReadOnlyDb)
            ->filterUrlDomain($urlDomain)
            ->loadEntity();
        return $account;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return Account[]
     */
    public function loadAll(bool $isReadOnlyDb = false): array
    {
        $accountRepository = $this->prepareForLoadAll($isReadOnlyDb);
        $accounts = $accountRepository->loadEntities();
        return $accounts;
    }

    /**
     * @param string[] $select - define fetched columns
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadAllSelected(array $select, bool $isReadOnlyDb = false): array
    {
        $accountRepository = $this->prepareForLoadAll($isReadOnlyDb);
        $rows = $accountRepository
            ->select($select)
            ->loadRows();
        return $rows;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadAllIds(bool $isReadOnlyDb = false): array
    {
        $rows = $this->loadAllSelected(['id'], $isReadOnlyDb);
        $accountIds = ArrayCast::arrayColumnInt($rows, 'id');
        return $accountIds;
    }

    /**
     * Load Account by sync key from namespace
     * @param string $key entity_sync.key
     * @param int $namespaceId entity_sync.namespace_id
     * @param bool $isReadOnlyDb
     * @return Account|null
     */
    public function loadBySyncKey(string $key, int $namespaceId, bool $isReadOnlyDb = false): ?Account
    {
        $account = $this->prepareForLoadAll($isReadOnlyDb)
            ->joinAccountSyncFilterSyncNamespaceId($namespaceId)
            ->joinAccountSyncFilterKey($key)
            ->loadEntity();
        return $account;
    }

    public function loadSelectedByIds(array $select, array $accountIds, bool $isReadOnlyDb = false): array
    {
        $rows = $this->prepareRepository($isReadOnlyDb)
            ->select($select)
            ->filterId($accountIds)
            ->loadRows();
        return $rows;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AccountReadRepository
     */
    protected function prepareForLoadAll(bool $isReadOnlyDb = false): AccountReadRepository
    {
        $accountRepository = $this->prepareRepository($isReadOnlyDb)
            ->orderById();
        $isSingleTenant = $this->createApplicationAccessChecker()->isSingleTenant();
        if ($isSingleTenant) {
            $accountRepository->filterId($this->mainAccountId());
        }
        return $accountRepository;
    }

    /**
     * Results with id of main account fetched from installation configuration.
     * @return int
     */
    protected function mainAccountId(): int
    {
        return $this->createMainAccountDetector()->id();
    }
}
