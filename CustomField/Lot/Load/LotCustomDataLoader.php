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


use LotItemCustData;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepositoryCreateTrait;

/**
 * Class LotCustomDataLoader
 * @package Sam\CustomField\Lot\Load
 */
class LotCustomDataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use EntityMemoryCacheManagerAwareTrait;
    use FilesystemCacheManagerAwareTrait;
    use LotItemCustDataReadRepositoryCreateTrait;

    private const CACHED_LIFE_TIME = 180;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load lot custom field data entity for custom field and lot item
     *
     * @param int $lotCustomFieldId
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return LotItemCustData|null
     */
    public function load(int $lotCustomFieldId, int $lotItemId, bool $isReadOnlyDb = false): ?LotItemCustData
    {
        if (!$lotCustomFieldId) {
            return null;
        }
        $fn = function () use ($lotCustomFieldId, $lotItemId, $isReadOnlyDb) {
            return $this->prepareRepository($isReadOnlyDb)
                ->filterLotItemId($lotItemId)
                ->filterLotItemCustFieldId($lotCustomFieldId)
                ->loadEntity();
        };
        $entityKey = $this->getEntityMemoryCacheManager()->makeEntityCacheKey(
            Constants\MemoryCache::LOT_CUSTOM_FIELD_ID_LOT_ITEM_ID,
            [$lotCustomFieldId, $lotItemId]
        );
        return $this->getEntityMemoryCacheManager()->load($entityKey, $fn);
    }

    /**
     * Load a list of lot custom field data entities for specific custom fields and lot item
     *
     * @param array $lotCustomFieldIds
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return LotItemCustData[]
     */
    public function loadEntities(array $lotCustomFieldIds, int $lotItemId, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->filterLotItemCustFieldId($lotCustomFieldIds)
            ->loadEntities();
    }

    /**
     * Load a list of lot custom field data rows with specific columns for custom fields and lot item
     *
     * @param array $selected
     * @param array $lotCustomFieldIds
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelectedRows(array $selected, array $lotCustomFieldIds, int $lotItemId, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->select($selected)
            ->filterLotItemId($lotItemId)
            ->filterLotItemCustFieldId($lotCustomFieldIds)
            ->loadRows();
    }

    /**
     * Load lot custom data entity by id
     *
     * @param int $lotCustomDataId
     * @param bool $isReadOnlyDb
     * @return LotItemCustData|null
     */
    public function loadById(int $lotCustomDataId, bool $isReadOnlyDb = false): ?LotItemCustData
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterId($lotCustomDataId)
            ->loadEntity();
    }

    /**
     * Load all lotItem custom data for lot
     *
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return LotItemCustData[]
     */
    public function loadForLot(int $lotItemId, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->loadEntities();
    }

    /**
     * Return lot item id which has passed custom field with passed barcode value
     *
     * @param int $lotCustomFieldId
     * @param string $barcodeText
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function detectLotItemIdByBarcode(int $lotCustomFieldId, string $barcodeText, bool $isReadOnlyDb = false): ?int
    {
        $row = $this->prepareRepository($isReadOnlyDb)
            ->select(['lot_item_id'])
            ->filterLotItemCustFieldId($lotCustomFieldId)
            ->filterText($barcodeText)
            ->loadRow();
        return Cast::toInt($row['lot_item_id'] ?? null);
    }

    /**
     * Load all existing custom fields for the lot
     *
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return array [id, value]
     */
    public function loadValues(int $lotItemId, bool $isReadOnlyDb = false): array
    {
        $customFields = $this->loadForLot($lotItemId, $isReadOnlyDb);
        $result = [];
        foreach ($customFields as $customField) {
            $result[$customField->LotItemCustFieldId] = $customField->Numeric ?: $customField->Text;
        }
        return $result;
    }

    /**
     * Load all lot item data for same custom field
     *
     * @param int $lotCustomFieldId
     * @param string $mixData
     * @param string $customFieldType
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadAllSearched(
        int $lotCustomFieldId,
        string $mixData,
        string $customFieldType,
        bool $isReadOnlyDb = false
    ): array {
        if (
            $customFieldType !== 'text'
            && $customFieldType !== 'numeric'
        ) {
            return [];
        }

        $cacheManager = $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('custom-fields');

        $cacheKey = 'CustomData-' . $lotCustomFieldId . '-' . $mixData;
        $rows = $cacheManager->get($cacheKey);

        if (!$rows) {
            $n = "\n";
            // @formatter:off
            $query =
                'SELECT ' . $n
                . 'licd.id as id, ' . $n
                . 'licd.`' . $customFieldType . '` as res ' . $n
                . 'FROM lot_item_cust_data  licd ' . $n
                . 'INNER JOIN lot_item_cust_field licf '
                . 'ON licf.id=licd.lot_item_cust_field_id '
                . 'AND licf.active ' . $n
                . 'WHERE ' . $n
                . 'licd.lot_item_cust_field_id = ' . $this->escape($lotCustomFieldId) . ' ' . $n
                . 'AND licd.' . $customFieldType . ' LIKE ' . $this->escape($mixData . '%') . ' ' . $n
                . 'GROUP BY res ' . $n
                . 'ORDER BY res DESC LIMIT 20';
            // @formatter:on
            $this->query($query, $isReadOnlyDb);
            $rows = [];
            while ($row = $this->fetchAssoc()) {
                $rows[] = $row;
            }
            if (!empty($rows)) {
                $cacheManager->set($cacheKey, $rows, self::CACHED_LIFE_TIME);
            }
        }
        return $rows;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return LotItemCustDataReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): LotItemCustDataReadRepository
    {
        return $this->createLotItemCustDataReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
    }
}
