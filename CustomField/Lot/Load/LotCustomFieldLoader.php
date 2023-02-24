<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 13, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Load;

use LotItemCustField;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepositoryCreateTrait;
use Sam\User\Access\AuctionAccessCheckerAwareTrait;
use Sam\User\Access\LotAccessCheckerAwareTrait;
use Sam\User\Access\UnknownContextAccessCheckerAwareTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class LotCustomFieldLoader
 * @package Sam\CustomField\Lot\Load
 */
class LotCustomFieldLoader extends CustomizableClass
{
    use AuctionAccessCheckerAwareTrait;
    use AuthIdentityManagerCreateTrait;
    use ConfigRepositoryAwareTrait;
    use EntityMemoryCacheManagerAwareTrait;
    use LotAccessCheckerAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotItemCustFieldReadRepositoryCreateTrait;
    use MemoryCacheManagerAwareTrait;
    use UnknownContextAccessCheckerAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load custom field by ID
     *
     * @param int|null $id
     * @param bool $isReadOnlyDb
     * @return LotItemCustField|null
     */
    public function load(?int $id, bool $isReadOnlyDb = false): ?LotItemCustField
    {
        if (!$id) {
            return null;
        }
        $fn = function () use ($id, $isReadOnlyDb) {
            return $this->prepareRepository($isReadOnlyDb)
                ->filterId($id)
                ->loadEntity();
        };
        $entityKey = $this->getEntityMemoryCacheManager()->makeEntityCacheKey(
            Constants\MemoryCache::LOT_CUSTOM_FIELD_ID,
            [$id]
        );
        return $this->getEntityMemoryCacheManager()->load($entityKey, $fn);
    }

    /**
     * Load custom field by name
     *
     * @param string $name
     * @param bool $isReadOnlyDb
     * @return LotItemCustField|null
     */
    public function loadByName(string $name, bool $isReadOnlyDb = false): ?LotItemCustField
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterName($name)
            ->loadEntity();
    }

    /**
     * Load all custom fields where type in $types
     *
     * @param array $types lot_item_cust_field.type
     * @param bool|null $isUnique
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadForLotByType(
        array $types,
        ?bool $isUnique = null,
        bool $isReadOnlyDb = false
    ): array {
        $editorUserId = $this->detectEditorUserId();
        $accessRoles = $this->detectRolesForUnknownContext($editorUserId, true, $isReadOnlyDb);
        $repo = $this->prepareRepository($isReadOnlyDb)
            ->filterType($types)
            ->filterAccess($accessRoles);
        if ($isUnique !== null) {
            $repo->filterUnique($isUnique);
        }
        return $repo
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Return ordered array of lot item custom fields.
     * Don't check access rights.
     *
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadAll(bool $isReadOnlyDb = false): array
    {
        return $this->getMemoryCacheManager()->load(
            Constants\MemoryCache::LOT_CUSTOM_FIELD_ALL,
            function () use ($isReadOnlyDb) {
                $types = $this->detectAvailableTypes();
                return $this->prepareRepository($isReadOnlyDb)
                    ->filterType($types)
                    ->orderByOrder()
                    ->loadEntities();
            }
        );
    }

    /**
     * Load all lot custom fields ids
     *
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadAllIds(bool $isReadOnlyDb = false): array
    {
        return ArrayHelper::toArrayByProperty($this->loadAll($isReadOnlyDb), 'Id');
    }

    /**
     * Load all custom fields with respective access role
     *
     * @param array $accessRoles array with access rights, default visitor
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadByRole(array $accessRoles = [Constants\Role::VISITOR], bool $isReadOnlyDb = false): array
    {
        $types = $this->detectAvailableTypes();
        return $this->prepareRepository($isReadOnlyDb)
            ->filterType($types)
            ->filterAccess($accessRoles)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load all custom fields checked for catalog with respective access rights
     *
     * @param int|null $auctionId optional if auction.id is available
     * @param int|null $editorUserId null for anonymous user
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadInCatalog(?int $auctionId = null, ?int $editorUserId = null, bool $isReadOnlyDb = false): array
    {
        $accessRoles = $this->detectRoles(null, $auctionId, $editorUserId, $isReadOnlyDb);
        $types = $this->detectAvailableTypes();
        return $this->prepareRepository($isReadOnlyDb)
            ->filterType($types)
            ->filterInCatalog(true)
            ->filterAccess($accessRoles)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load all custom fields checked for catalog with respective access rights
     *
     * @param int|null $editorUserId
     * @param bool $needDefiniteRoles
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadInCatalogForUnknownContext(?int $editorUserId = null, bool $needDefiniteRoles = true, bool $isReadOnlyDb = false): array
    {
        $accessRoles = $this->detectRolesForUnknownContext($editorUserId, $needDefiniteRoles, $isReadOnlyDb);
        $types = $this->detectAvailableTypes();
        return $this->prepareRepository($isReadOnlyDb)
            ->filterType($types)
            ->filterInCatalog(true)
            ->filterAccess($accessRoles)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load all custom fields checked for using in search with respective access rights
     *
     * @param int|null $editorUserId current user (checking access)
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadInSearch(?int $editorUserId = null, bool $isReadOnlyDb = false): array
    {
        $editorUserId = $this->detectEditorUserId($editorUserId);
        $accessRoles = $this->detectRolesForUnknownContext($editorUserId, true, $isReadOnlyDb);
        $types = $this->detectAvailableTypes();
        return $this->prepareRepository($isReadOnlyDb)
            ->filterType($types)
            ->filterSearchField(true)
            ->filterAccess($accessRoles)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load barcode fields
     *
     * @param array|null $barcodeTypes null - for all types
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadBarcodeFields(?array $barcodeTypes = null, bool $isReadOnlyDb = false): array
    {
        $repo = $this->prepareRepository($isReadOnlyDb)
            ->filterType(Constants\CustomField::TYPE_TEXT)
            ->filterBarcode(true);

        if ($barcodeTypes !== null) {
            $repo->filterBarcodeType($barcodeTypes);
        }
        return $repo->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load youtubelink fields
     *
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadYoutubeLinkFields(bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterType(Constants\CustomField::TYPE_YOUTUBELINK)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load Numeric fields
     *
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadNumericFields(bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterType([Constants\CustomField::TYPE_INTEGER, Constants\CustomField::TYPE_DECIMAL])
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Return custom fields loaded by categories assigned to lot
     *
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadByLotCategories(int $lotItemId, bool $isReadOnlyDb = false): array
    {
        $lotCategoryIds = $this->getLotCategoryLoader()->loadCategoryWithAncestorIdsForLot($lotItemId, $isReadOnlyDb);
        $customFields = $this->prepareRepositoryForCustomFieldByCategory($lotCategoryIds, [], $isReadOnlyDb)->loadEntities();
        return $customFields;
    }

    /**
     * Load lot custom field ids that was assigned to the categories
     *
     * @param int[] $lotCategoryIds
     * @param string[] $accesses
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadCustomFieldIdsByCategoryIds(
        array $lotCategoryIds,
        array $accesses = [],
        bool $isReadOnlyDb = false
    ): array {
        $fn = function () use ($lotCategoryIds, $accesses, $isReadOnlyDb) {
            return $this->prepareRepositoryForCustomFieldByCategory($lotCategoryIds, $accesses, $isReadOnlyDb)
                ->select(['licf.id'])
                ->loadRows();
        };

        $cacheKey = sprintf(
            Constants\MemoryCache::CUSTOM_FIELDS_IDS_CATEGORY_IDS,
            implode('-', $lotCategoryIds),
            implode('-', $accesses)
        );
        $rows = $this->getMemoryCacheManager()->load($cacheKey, $fn);
        $lotCustomFieldIds = ArrayCast::arrayColumnInt($rows, 'id');
        return $lotCustomFieldIds;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadInInvoices(bool $isReadOnlyDb = false): array
    {
        $editorUserId = $this->detectEditorUserId();
        $accessRoles = $this->detectRolesForUnknownContext($editorUserId, true, $isReadOnlyDb);
        $types = $this->detectAvailableTypes();
        return $this->prepareRepository($isReadOnlyDb)
            ->filterType($types)
            ->filterInInvoices(true)
            ->filterAccess($accessRoles)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load all custom fields checked for settlements page
     *
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadInSettlements(bool $isReadOnlyDb = false): array
    {
        $editorUserId = $this->detectEditorUserId();
        $accessRoles = $this->detectRolesForUnknownContext($editorUserId, true, $isReadOnlyDb);
        $types = $this->detectAvailableTypes();
        return $this->prepareRepository($isReadOnlyDb)
            ->filterType($types)
            ->filterInSettlements(true)
            ->filterAccess($accessRoles)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Return array with custom field names
     *
     * @param bool $isReadOnlyDb
     * @return array of custom field names
     */
    public function loadAllNames(bool $isReadOnlyDb = false): array
    {
        $types = $this->detectAvailableTypes();
        $row = $this->prepareRepository($isReadOnlyDb)
            ->filterType($types)
            ->select(['name'])
            ->loadRows();
        $names = ArrayCast::arrayColumnString($row, 'name');
        return $names;
    }

    /**
     * Load all custom fields checked for admin catalog on inventory and auction lots
     *
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadInAdminCatalog(bool $isReadOnlyDb = false): array
    {
        $types = $this->detectAvailableTypes();
        return $this->prepareRepository($isReadOnlyDb)
            ->filterType($types)
            ->filterInAdminCatalog(true)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load all custom fields checked for admin search on inventory and auction lots
     *
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadInAdminSearch(bool $isReadOnlyDb = false): array
    {
        $types = $this->detectAvailableTypes();
        return $this->prepareRepository($isReadOnlyDb)
            ->filterType($types)
            ->filterInAdminSearch(true)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load array of all lotItem custom fields, which data value  is editable by user
     *
     * @param bool $isReadOnlyDb
     * @return LotItemCustField[]
     */
    public function loadAllEditable(bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepositoryForLoadAllEditable($isReadOnlyDb)->loadEntities();
    }

    public function loadSelectedAllEditable(array $select, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepositoryForLoadAllEditable($isReadOnlyDb)
            ->select($select)
            ->loadRows();
    }

    /**
     * @param bool $isReadOnlyDb
     * @return LotItemCustFieldReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): LotItemCustFieldReadRepository
    {
        return $this->createLotItemCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
    }

    /**
     * @param int[] $lotCategoryIds
     * @param array $accesses
     * @param bool $isReadOnlyDb
     * @return LotItemCustFieldReadRepository
     */
    protected function prepareRepositoryForCustomFieldByCategory(
        array $lotCategoryIds,
        array $accesses = [],
        bool $isReadOnlyDb = false
    ): LotItemCustFieldReadRepository {
        $repo = $this->createLotItemCustFieldReadRepository();
        if ($accesses) {
            $repo->filterAccess($accesses);
        }
        $types = $this->detectAvailableTypes();
        $repo->filterType($types);
        $lotCategoryIds = ArrayCast::makeIntArray($lotCategoryIds);
        if ($lotCategoryIds) {
            // check among categories
            $lotCategoryIdList = implode(',', $repo->escapeArray($lotCategoryIds));
            $categoryCondition =
                "(SELECT COUNT(1) "
                . "FROM lot_item_cust_field_lot_category "
                . "WHERE lot_item_cust_field_id = licf.id "
                . "AND lot_category_id IN (" . $lotCategoryIdList . ") "
                . ")";
        } else {
            $categoryCondition = 0;
        }
        $repo
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->inlineCondition(
            // @formatter:off
                "IF ( "
                    // custom field has not any assigned category
                    . "("
                        . "SELECT COUNT(1) "
                        . "FROM lot_item_cust_field_lot_category "
                        . "WHERE lot_item_cust_field_id = licf.id "
                    . ") = 0, "
                    . "1, "
                    . $categoryCondition
                . ") > 0"
                // @formatter:on
            )
            ->orderByOrder();
        return $repo;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return LotItemCustFieldReadRepository
     */
    protected function prepareRepositoryForLoadAllEditable(bool $isReadOnlyDb = false): LotItemCustFieldReadRepository
    {
        $types = $this->detectAvailableTypes();
        return $this->prepareRepository($isReadOnlyDb)
            ->filterType($types)
            ->skipType(Constants\CustomField::TYPE_LABEL)
            ->orderByOrder();
    }

    /**
     * @return int[]
     */
    protected function detectAvailableTypes(): array
    {
        $availableTypes = [
            Constants\CustomField::TYPE_INTEGER,
            Constants\CustomField::TYPE_DECIMAL,
            Constants\CustomField::TYPE_TEXT,
            Constants\CustomField::TYPE_FULLTEXT,
            Constants\CustomField::TYPE_SELECT,
            Constants\CustomField::TYPE_DATE,
            Constants\CustomField::TYPE_FILE,
            Constants\CustomField::TYPE_POSTALCODE,
        ];
        if ($this->cfg()->get('core->lot->video->enabled')) {
            $availableTypes[] = Constants\CustomField::TYPE_YOUTUBELINK;
        }
        return $availableTypes;
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param int|null $editorUserId
     * @param bool $isReadOnlyDb
     * @return array
     */
    protected function detectRoles(
        ?int $lotItemId = null,
        ?int $auctionId = null,
        ?int $editorUserId = null,
        bool $isReadOnlyDb = false
    ): array {
        if (
            $auctionId
            && $lotItemId
        ) {
            // when auction.id and lot_item.id are provided
            return $this->getLotAccessChecker()->detectRoles($lotItemId, $auctionId, $editorUserId, $isReadOnlyDb);
        }

        if ($auctionId) {
            // when only auction.id is provided
            return $this->getAuctionAccessChecker()->detectRoles($auctionId, $editorUserId, $isReadOnlyDb);
        }

        if ($lotItemId) {
            // when only lot_item.id is provided
            return $this->getLotAccessChecker()->detectRoles($lotItemId, null, $editorUserId, $isReadOnlyDb);
        }

        return $this->detectRolesForUnknownContext($editorUserId, true, $isReadOnlyDb);
    }

    /**
     * Detect roles, when neither auction.id nor lot_item.id are provided
     * @param int|null $editorUserId
     * @param bool $needDefiniteRoles
     * @param bool $isReadOnlyDb
     * @return array
     */
    protected function detectRolesForUnknownContext(
        ?int $editorUserId = null,
        bool $needDefiniteRoles = true,
        bool $isReadOnlyDb = false
    ): array {
        $unknownContextRoles = $this->getUnknownContextAccessChecker()->detectRoles($editorUserId, $isReadOnlyDb);
        $accessRoles = $needDefiniteRoles
            ? $unknownContextRoles[0]
            : array_unique(array_merge($unknownContextRoles[0], $unknownContextRoles[1]));
        return $accessRoles;
    }

    /**
     * TODO: This logic should be extracted to caller to get rid of AuthIdentityManager dependency related to user session storage
     * @param int|null $userId
     * @return int|null
     */
    protected function detectEditorUserId(?int $userId = null): ?int
    {
        if (
            $userId === null
            && $this->createAuthIdentityManager()->isAuthorized()
        ) {
            $userId = $this->createAuthIdentityManager()->getUserId();
        }
        return $userId;
    }
}
