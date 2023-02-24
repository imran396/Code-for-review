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

use Sam\Core\Service\CustomizableClass;
use Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportData\AuctionLotCustomListCsvReportDataStorageKeyMapper as StorageKeyMapper;
use Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportField\AuctionLotCustomListCsvReportField as ReportField;

/**
 * Class AuctionLotCustomListCsvReportDto
 * @package Sam\Report\Lot\AuctionLotCustomList\Media\Csv
 */
class AuctionLotCustomListCsvReportDataItem extends CustomizableClass
{
    protected array $data;
    protected StorageKeyMapper $storageKeyMapper;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $data
     * @param StorageKeyMapper $storageKeyMapper
     * @return static
     */
    public function construct(array $data, StorageKeyMapper $storageKeyMapper): static
    {
        $this->data = $data;
        $this->storageKeyMapper = $storageKeyMapper;
        return $this;
    }

    /**
     * @param ReportField $reportField
     * @return array|mixed
     */
    public function getReportFieldData(ReportField $reportField): mixed
    {
        $storageKeys = $this->storageKeyMapper->getStorageKeys($reportField);
        $data = [];
        foreach ($storageKeys as $key) {
            $data[$key] = $this->data[$key] ?? null;
        }
        if (count($storageKeys) === 1) {
            $data = reset($data);
        }
        return $data;
    }
}
