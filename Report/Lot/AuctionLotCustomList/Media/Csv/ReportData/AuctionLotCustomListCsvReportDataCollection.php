<?php
/**
 * SAM-4644: Refactor "Custom CSV Export" report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug. 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportData;

use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Iterator;
use Sam\Core\Data\ArrayHelper;
use Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportData\AuctionLotCustomListCsvReportDataStorageKeyMapper as StorageKeyMapper;

/**
 * Class AuctionLotCustomListCsvReportDtos
 * @package Sam\Report\Lot\AuctionLotCustomList\Media\Csv\Dto
 */
class AuctionLotCustomListCsvReportDataCollection extends CustomizableClass implements Iterator
{
    protected array $collectionDataStorage = [];
    protected array $lotItemIds = [];
    /** @var AuctionLotCustomListCsvReportDataItem[] */
    protected array $dtoCache = [];
    protected ?StorageKeyMapper $storageKeyMapper = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $rows
     * @return static
     */
    public function buildFromLots(array $rows): static
    {
        $this->collectionDataStorage = ArrayHelper::produceIndexedArray($rows, 'lot_item_id');
        $this->lotItemIds = array_keys($this->collectionDataStorage);
        return $this;
    }

    /**
     * @param array $rows
     * @return static
     */
    public function addLotImages(array $rows): static
    {
        foreach ($rows as $row) {
            $lotItemId = (int)$row['lot_item_id'];
            $this->collectionDataStorage[$lotItemId][StorageKeyMapper::LOT_IMAGES_STORAGE_KEY][] = $row;
        }
        return $this;
    }

    /**
     * @param array $rows
     * @return static
     */
    public function addLotCategories(array $rows): static
    {
        foreach ($rows as $row) {
            $lotItemId = (int)$row['lot_item_id'];
            $this->collectionDataStorage[$lotItemId][StorageKeyMapper::CATEGORIES_STORAGE_KEY][] = $row;
        }
        return $this;
    }

    /**
     * @param array $rows
     * @return static
     */
    public function addCustomFieldsData(array $rows): static
    {
        foreach ($rows as $row) {
            $lotItemId = (int)$row['lot_item_id'];
            $custFieldId = (int)$row['lot_item_cust_field_id'];
            $storageKey = sprintf(StorageKeyMapper::CUSTOM_FIELD_STORAGE_KEY_TEMPLATE, $custFieldId);
            $this->collectionDataStorage[$lotItemId][$storageKey] = $row;
        }
        return $this;
    }

    /**
     * @param array $rows
     * @return static
     */
    public function addConsignors(array $rows): static
    {
        $rows = ArrayHelper::produceIndexedArray($rows, 'id');
        foreach ($this->collectionDataStorage as $id => $row) {
            $consignorUserId = (int)($row['consignor_id'] ?? 0);
            if ($consignorUserId && isset($rows[$consignorUserId])) {
                $this->collectionDataStorage[$id][StorageKeyMapper::CONSIGNOR_STORAGE_KEY] = $rows[$consignorUserId]['username'];
            }
        }
        return $this;
    }

    /**
     * @param array $rows
     * @return static
     */
    public function addLocations(array $rows): static
    {
        $rows = ArrayHelper::produceIndexedArray($rows, 'id');
        foreach ($this->collectionDataStorage as $id => $row) {
            $locationId = (int)($row['location_id'] ?? 0);
            if ($locationId && isset($rows[$locationId])) {
                $this->collectionDataStorage[$id][StorageKeyMapper::LOCATION_STORAGE_KEY] = $rows[$locationId]['name'];
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getLotItemIdList(): array
    {
        return $this->lotItemIds;
    }

    /**
     * @param string $fieldName
     * @return array
     */
    public function extractFieldValues(string $fieldName): array
    {
        $values = array_map(
            static function (array $sourceDataItem) use ($fieldName) {
                return $sourceDataItem[$fieldName];
            },
            $this->collectionDataStorage
        );
        return $values;
    }

    /**
     * @param int $lotItemId
     * @return AuctionLotCustomListCsvReportDataItem
     */
    private function buildDataItem(int $lotItemId): AuctionLotCustomListCsvReportDataItem
    {
        if (!array_key_exists($lotItemId, $this->dtoCache)) {
            $sourceData = $this->getSourceData($lotItemId);
            $this->dtoCache[$lotItemId] = AuctionLotCustomListCsvReportDataItem::new()
                ->construct($sourceData, $this->getStorageKeyMapper());
        }

        return $this->dtoCache[$lotItemId];
    }

    /**
     * @param int $lotItemId
     * @return array
     */
    private function getSourceData(int $lotItemId): array
    {
        if (!array_key_exists($lotItemId, $this->collectionDataStorage)) {
            throw new RuntimeException("Source data for lot with id {$lotItemId} not found.");
        }

        return $this->collectionDataStorage[$lotItemId];
    }

    /**
     * @inheritDoc
     */
    public function current(): mixed
    {
        $lotItemId = $this->key();
        return $this->buildDataItem($lotItemId);
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        next($this->collectionDataStorage);
    }

    /**
     * @inheritDoc
     */
    public function key(): null|int|string
    {
        return key($this->collectionDataStorage);
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return $this->key() !== null;
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        reset($this->collectionDataStorage);
    }

    private function getStorageKeyMapper(): StorageKeyMapper
    {
        if (!$this->storageKeyMapper) {
            $this->storageKeyMapper = StorageKeyMapper::new();
        }
        return $this->storageKeyMapper;
    }
}
