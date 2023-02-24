<?php
/**
 * Help methods for auction custom field data loading
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 9, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Load;

use Sam\Core\Constants;
use Sam\Core\Filter\EntityLoader\UserCustFieldAllFilterTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\UserCustField\UserCustFieldReadRepository;
use UserCustField;

/**
 * Class UserCustomFieldLoader
 * @package Sam\CustomField\User\Load
 */
class UserCustomFieldLoader extends CustomizableClass
{
    use UserCustFieldAllFilterTrait;
    use EntityMemoryCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static or customized class extending it
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
     * @param int|null $userCustomFieldId null/0 lead to null result
     * @param bool $isReadOnlyDb
     * @return UserCustField|null
     */
    public function load(?int $userCustomFieldId, bool $isReadOnlyDb = false): ?UserCustField
    {
        if (!$userCustomFieldId) {
            return null;
        }

        $fn = function () use ($userCustomFieldId, $isReadOnlyDb) {
            $userCustomField = $this->prepareRepository($isReadOnlyDb)
                ->filterId($userCustomFieldId)
                ->loadEntity();
            return $userCustomField;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::USER_CUSTOM_FIELD_ID, $userCustomFieldId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $userCustomField = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $userCustomField;
    }

    /**
     * Load array of all user custom fields
     *
     * @param bool $isReadOnlyDb
     * @return UserCustField[]
     */
    public function loadAll(bool $isReadOnlyDb = false): array
    {
        $userCustomFields = $this->prepareRepository($isReadOnlyDb)
            ->orderByOrder()
            ->loadEntities();
        return $userCustomFields;
    }

    /**
     * Load array of all user custom fields, which data value may is editable by user
     *
     * @param int[] $panels [] for all panels
     * @param bool $isReadOnlyDb
     * @return UserCustField[]
     */
    public function loadAllEditable(array $panels = [], bool $isReadOnlyDb = false): array
    {
        $repo = $this->prepareRepository($isReadOnlyDb)
            ->skipType(Constants\CustomField::TYPE_LABEL)
            ->orderByOrder();
        if ($panels) {
            $repo->filterPanel($panels);
        }
        $userCustomFields = $repo->loadEntities();
        return $userCustomFields;
    }

    /**
     * Load array of user custom fields,
     * which supposed to be used at different editing pages (registration, profile or admin edit)
     *
     * @param int[] $panels Null for all panels
     * @param bool|null $isOnRegistration
     * @param bool|null $isOnProfile
     * @param bool|null $isOnAddNewBidder
     * @param bool $isReadOnlyDb
     * @return UserCustField[]
     */
    public function loadEntities(
        array $panels = [],
        ?bool $isOnRegistration = null,
        ?bool $isOnProfile = null,
        ?bool $isOnAddNewBidder = null,
        bool $isReadOnlyDb = false
    ): array {
        $repo = $this->prepareRepository($isReadOnlyDb);
        if ($panels) {
            $repo->filterPanel($panels);
        }
        if ($isOnRegistration !== null) {
            $repo->filterOnRegistration($isOnRegistration);
        }
        if ($isOnProfile !== null) {
            $repo->filterOnProfile($isOnProfile);
        }
        if ($isOnAddNewBidder !== null) {
            $repo->filterOnAddNewBidder($isOnAddNewBidder);
        }
        $repo->orderByOrder();
        $userCustomFields = $repo->loadEntities();
        return $userCustomFields;
    }

    /**
     * Load all custom user fields checked for admin search and not encrypted
     *
     * @param bool $isReadOnlyDb
     * @return UserCustField[]
     */
    public function loadInAdminSearch(bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterEncrypted(false)
            ->filterInAdminSearch(true)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load active custom user fields checked to be shown in invoices
     *
     * @param bool $isReadOnlyDb
     * @return UserCustField[]
     */
    public function loadInInvoices(bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterInInvoices(true)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load active custom user fields checked to be shown in settlements
     *
     * @param bool $isReadOnlyDb
     * @return UserCustField[]
     */
    public function loadInSettlements(bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterInSettlements(true)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load active custom user fields checked to be shown on "Add new bidder" page
     *
     * @param bool $isReadOnlyDb
     * @return UserCustField[]
     */
    public function loadOnAddNewBidder(bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterOnAddNewBidder(true)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load user custom field by its name
     *
     * @param string $name
     * @param bool $isReadOnlyDb
     * @return UserCustField|null
     */
    public function loadByName(string $name, bool $isReadOnlyDb = false): ?UserCustField
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterName($name)
            ->loadEntity();
    }

    public function loadSelectedAll(array $select, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->select($select)
            ->orderByOrder()
            ->loadRows();
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserCustFieldReadRepository
     */
    public function prepareRepository(bool $isReadOnlyDb): UserCustFieldReadRepository
    {
        return $this->createUserCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
    }
}
