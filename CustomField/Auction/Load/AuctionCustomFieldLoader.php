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
 * @since           Oct 7, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Load;

use AuctionCustField;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Filter\EntityLoader\AuctionCustFieldAllFilterTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;

/**
 * Class AuctionCustomFieldLoader
 * @package Sam\CustomField\Auction\Load
 */
class AuctionCustomFieldLoader extends CustomizableClass
{
    use AuctionCustFieldAllFilterTrait;
    use MemoryCacheManagerAwareTrait;
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
     * Load array of all auction custom fields
     *
     * @param bool $isReadOnlyDb
     * @return AuctionCustField[]
     */
    public function loadAll(bool $isReadOnlyDb = false): array
    {
        return $this->getMemoryCacheManager()->load(
            Constants\MemoryCache::AUCTION_CUSTOM_FIELD_ALL,
            function () use ($isReadOnlyDb) {
                return $this->createAuctionCustFieldReadRepository()
                    ->enableReadOnlyDb($isReadOnlyDb)
                    ->filterActive(true)
                    ->orderByOrder()
                    ->loadEntities();
            }
        );
    }

    /**
     * Load array of all auction custom fields, which data value is editable
     *
     * @param bool $isReadOnlyDb
     * @return AuctionCustField[]
     */
    public function loadAllEditable(bool $isReadOnlyDb = false): array
    {
        $auctionCustomField = $this->createAuctionCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->skipType(Constants\CustomField::TYPE_LABEL)
            ->orderByOrder()
            ->loadEntities();
        return $auctionCustomField;
    }

    /**
     * Load all custom auction fields checked for admin auction list
     *
     * @param bool $isReadOnlyDb
     * @return AuctionCustField[]
     */
    public function loadForAdminList(bool $isReadOnlyDb = false): array
    {
        $auctionCustomField = $this->createAuctionCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterAdminList(true)
            ->orderByOrder()
            ->loadEntities();
        return $auctionCustomField;
    }

    /**
     * Load all custom auction fields checked for public auction list
     *
     * @param bool $isReadOnlyDb
     * @return AuctionCustField[]
     */
    public function loadForPublicList(bool $isReadOnlyDb = false): array
    {
        $auctionCustomField = $this->createAuctionCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterPublicList(true)
            ->orderByOrder()
            ->loadEntities();
        return $auctionCustomField;
    }

    /**
     * @param int|null $id
     * @param bool $isReadOnlyDb
     * @return AuctionCustField|null
     */
    public function loadById(?int $id, bool $isReadOnlyDb = false): ?AuctionCustField
    {
        if (!$id) {
            return null;
        }

        $fn = function () use ($id, $isReadOnlyDb) {
            $auctionCustomField = $this->prepareRepository($isReadOnlyDb)
                ->filterId($id)
                ->loadEntity();
            return $auctionCustomField;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::AUCTION_CUSTOM_FIELD_ID, $id);
        $filterDescriptors = $this->collectFilterDescriptors();
        $auctionCustomField = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $auctionCustomField;
    }

    /**
     * Load auction custom field by its name
     *
     * @param string $name
     * @param bool $isReadOnlyDb
     * @return AuctionCustField|null
     */
    public function loadByName(string $name, bool $isReadOnlyDb = false): ?AuctionCustField
    {
        $auctionCustomField = $this->createAuctionCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterName($name)
            ->loadEntity();
        return $auctionCustomField;
    }

    /**
     * Fetch an array with the ids of all active custom fields in order
     *
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadAllIds(bool $isReadOnlyDb = false): array
    {
        $auctionCustomFields = $this->createAuctionCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select(['id'])
            ->filterActive(true)
            ->orderByOrder()
            ->loadRows();
        $ids = ArrayCast::arrayColumnInt($auctionCustomFields, 'id');
        return $ids;
    }

    public function loadSelectedAll(array $select, bool $isReadOnlyDb = false): array
    {
        return $this->createAuctionCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select($select)
            ->filterActive(true)
            ->orderByOrder()
            ->loadRows();
    }
}
