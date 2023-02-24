<?php
/**
 * SAM-4644: Refactor "Custom CSV Export" report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\AuctionLotCustomList\Media\Csv;

use Auction;
use CustomCsvExportConfig;
use DateTime;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\CustomField\Lot\Help\LotCustomFieldHelperCreateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\RowBuilderBase;
use Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportData\AuctionLotCustomListCsvReportDataItem as ReportDataItem;
use Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportField\AuctionLotCustomListCsvReportField as ReportField;
use Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportField\AuctionLotCustomListCsvReportFieldCollection as ReportFieldCollection;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Transform\Number\NextNumberFormatterAwareTrait;

/**
 * This class contains methods for building rows of report
 *
 * Class AuctionLotCustomListCsvRowBuilder
 * @package Sam\Report\Lot\AuctionLotCustomList\Media\Csv
 */
class AuctionLotCustomListCsvRowBuilder extends RowBuilderBase
{
    use AuctionAwareTrait;
    use ConfigRepositoryAwareTrait;
    use DateHelperAwareTrait;
    use LotCustomFieldHelperCreateTrait;
    use LotRendererAwareTrait;
    use NextNumberFormatterAwareTrait;
    use StaggerClosingHelperCreateTrait;
    use TimezoneLoaderAwareTrait;
    use UrlBuilderAwareTrait;

    protected const DATE_FORMAT = 'm/d/Y g:i A';
    protected CustomCsvExportConfig $config;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Auction $auction
     * @param CustomCsvExportConfig $config
     * @return static
     */
    public function construct(Auction $auction, CustomCsvExportConfig $config): static
    {
        $this->setAuction($auction);
        $this->config = $config;
        return $this;
    }

    /**
     * Build report row
     * @param ReportFieldCollection $reportFields
     * @param ReportDataItem $dataItem
     * @return array
     * @internal
     */
    public function buildRow(ReportFieldCollection $reportFields, ReportDataItem $dataItem): array
    {
        $rowCellsValues = [];
        foreach ($reportFields as $reportField) {
            $rowCellsValues[] = $this->makeRowFieldValues($reportField, $dataItem);
        }
        return array_merge([], ...$rowCellsValues);
    }

    /**
     * @param ReportField $field
     * @param ReportDataItem $dataItem
     * @return array
     */
    private function makeRowFieldValues(ReportField $field, ReportDataItem $dataItem): array
    {
        $values = [];
        $fieldData = $dataItem->getReportFieldData($field);
        if ($field->isCategoryField()) {
            $values = $this->makeCategoryFieldValues($fieldData);
        } elseif ($field->isLotImageField()) {
            $values = $this->makeImageFieldValues($fieldData);
        } elseif ($field->isStartDateField()) {
            $values[] = $this->makeTimedStartDateFieldValue($fieldData);
        } elseif ($field->isEndDateField()) {
            $values[] = $this->makeTimedEndDateFieldValue($fieldData);
        } elseif ($field->isLotNameField()) {
            $values[] = $this->makeLotName($fieldData);
        } elseif ($field->isItemNumberConcatenatedField()) {
            $values[] = $this->makeItemNumber($fieldData);
        } elseif ($field->isLotNumberConcatenatedField()) {
            $values[] = $this->makeLotNumber($fieldData);
        } elseif ($field->isCustomField()) {
            $values[] = $this->makeLotCustomFieldValue($fieldData);
        } elseif ($field->isQuantityField()) {
            $values[] = $this->makeQuantityFieldValue($fieldData);
        } elseif (is_numeric($fieldData)) {
            $skipNumberFormatting = $field->isLotNumberField()
                || $field->isItemNumberField()
                || $field->isGroupIdField()
                || $field->isItemIdField()
                || $field->isAuctionIdField();
            $values[] = $skipNumberFormatting
                ? (string)$fieldData
                : $this->getNextNumberFormatter()->formatMoneyNto($fieldData);
        } elseif (is_scalar($fieldData)) {
            $values[] = strip_tags((string)$fieldData);
        } else {
            $values[] = '';
            log_warning('Invalid value for field ' . $field->getName());
        }

        return $values;
    }

    /**
     * Build report titles
     * @param ReportFieldCollection $reportFields
     * @return array
     * @internal
     */
    public function buildTitlesRow(ReportFieldCollection $reportFields): array
    {
        $config = $this->getConfig();
        $rowCells = [];
        /** @var ReportField $field */
        foreach ($reportFields as $field) {
            if (
                $field->isLotImageField()
                && $config->ImageSeparateColumns > 0
            ) {
                for ($imgCnt = 1; $imgCnt <= $config->ImageSeparateColumns; $imgCnt++) {
                    $rowCells[] = $field->getName() . $imgCnt;
                }
            } elseif (
                $field->isCategoryField()
                && $config->CategoriesSeparateColumns > 0
            ) {
                for ($catCnt = 1; $catCnt <= $config->CategoriesSeparateColumns; $catCnt++) {
                    $rowCells[] = $field->getName() . $catCnt;
                }
            } else {
                $rowCells[] = $field->getName();
            }
        }
        return $rowCells;
    }

    /**
     * @param string $lotName
     * @return string
     */
    private function makeLotName(string $lotName): string
    {
        return $this->getLotRenderer()->makeName($lotName, $this->getAuction()->TestAuction);
    }

    /**
     * @param array $components
     * @return string
     */
    private function makeItemNumber(array $components): string
    {
        return $this->getLotRenderer()->makeItemNo($components['item_num'], $components['item_num_ext']);
    }

    /**
     * @param array $components
     * @return string
     */
    private function makeLotNumber(array $components): string
    {
        return $this->getLotRenderer()->makeLotNo($components['lot_num'], $components['lot_num_ext'], $components['lot_num_prefix']);
    }

    private function makeQuantityFieldValue(array $components): string
    {
        return $this->getNextNumberFormatter()->formatNto(Cast::toFloat($components['quantity']), (int)$components['quantity_scale']);
    }

    /**
     * @param array|null $lotCustomFieldData
     * @return string
     */
    private function makeLotCustomFieldValue(?array $lotCustomFieldData): string
    {
        if (!$lotCustomFieldData) {
            return '';
        }

        $value = '';
        $lotCustomField = $lotCustomFieldData['customFieldObject'];
        $renderMethod = $this->createLotCustomFieldHelper()->makeCustomMethodName($lotCustomField->Name, 'Render'); // SAM-1570
        if (method_exists($this, $renderMethod)) {
            $value = $this->$renderMethod($lotCustomField, $lotCustomFieldData, $this->getEncoding(), $this->getAuctionId());
        } else {
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $value = $lotCustomFieldData['numeric'];
                    $value = $this->getNextNumberFormatter()->formatInteger($value);
                    break;

                case Constants\CustomField::TYPE_DECIMAL:
                    $dbValue = Cast::toInt($lotCustomFieldData['numeric']);
                    if ($dbValue !== null) {
                        $precision = (int)$lotCustomField->Parameters;
                        $value = CustomDataDecimalPureCalculator::new()->calcRealValue($dbValue, $precision);
                        $value = $this->getNextNumberFormatter()->format($value, $precision);
                    }
                    break;

                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_SELECT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_FILE:
                case Constants\CustomField::TYPE_POSTALCODE:
                    $value = strip_tags($lotCustomFieldData['text']);
                    break;

                case Constants\CustomField::TYPE_DATE:
                    $value = (new DateTime())
                        ->setTimestamp((int)$lotCustomFieldData['numeric'])
                        ->format(Constants\Date::ISO);
                    break;
            }
        }

        return $value;
    }

    /**
     * @param array $row
     * @return string
     */
    private function makeTimedStartDateFieldValue(array $row): string
    {
        $auction = $this->getAuction();
        if (!$auction) {
            log_error("Available auction not found" . composeSuffix(['a' => $this->getAuctionId()]));
            return '';
        }

        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        if (
            $auctionLotStatusPureChecker->isActive((int)$row['lot_status_id'])
            && $auction->ExtendAll
        ) {
            $tzLocation = $this->getTimezoneLocation($auction->TimezoneId);
            $lotStartDate = $auction->StartBiddingDate;
        } else {
            $tzLocation = $this->getTimezoneLocation((int)$row['timezone_id']);
            $lotStartDate = new DateTime($row['start_date']);
        }

        return $this->getDateHelper()->formatUtcDate($lotStartDate, null, $tzLocation, null, static::DATE_FORMAT);
    }

    /**
     * @param array $row
     * @return string
     */
    private function makeTimedEndDateFieldValue(array $row): string
    {
        $auction = $this->getAuction();
        if (!$auction) {
            log_error("Available auction not found" . composeSuffix(['a' => $this->getAuctionId()]));
            return '';
        }

        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        if (
            $auctionLotStatusPureChecker->isActive((int)$row['lot_status_id'])
            && $auction->ExtendAll
        ) {
            $tzLocation = $this->getTimezoneLocation($this->getAuction()->TimezoneId);
            $lotEndDate = $auction->EndDate;
            if ($auction->StaggerClosing) {
                $lotEndDate = $this->createStaggerClosingHelper()
                    ->calcEndDate(
                        $this->getAuctionDynamicOrCreate()->ExtendAllStartClosingDate,
                        $auction->LotsPerInterval,
                        $auction->StaggerClosing,
                        (int)$row['order']
                    );
            }
        } else {
            $tzLocation = $this->getTimezoneLocation((int)$row['timezone_id']);
            $lotEndDate = new DateTime((string)$row['end_date']);
        }

        return $this->getDateHelper()->formatUtcDate($lotEndDate, null, $tzLocation, null, self::DATE_FORMAT);
    }

    /**
     * @param array|null $categories
     * @return array
     */
    private function makeCategoryFieldValues(?array $categories): array
    {
        if ($categories === null) {
            $categories = [];
        }
        $config = $this->getConfig();
        $values = [];
        if ($config->CategoriesSeparateColumns > 0) {
            for ($index = 0; $index < $config->CategoriesSeparateColumns; $index++) {
                $values[] = $categories[$index]['name'] ?? '';
            }
        } else {
            $categoryNames = array_map(
                static function (array $category) {
                    return $category['name'];
                },
                $categories
            );
            $values[] = implode(';', $categoryNames);
        }
        return $values;
    }

    /**
     * @param array|null $lotImages
     * @return array
     */
    private function makeImageFieldValues(?array $lotImages): array
    {
        if ($lotImages === null) {
            $lotImages = [];
        }
        $values = [];
        $config = $this->getConfig();
        if ($config->ImageSeparateColumns > 0) {
            for ($index = 0; $index < $config->ImageSeparateColumns; $index++) {
                if (!isset($lotImages[$index])) {
                    $values[] = '';
                    continue;
                }
                $values[] = $this->makeImageUrl(
                    $lotImages[$index]['id'],
                    $lotImages[$index]['image_link'],
                    $config->AccountId,
                    $config->ImageWebLinks
                );
            }
        } else {
            $accountId = $config->AccountId;
            $images = array_map(
                function (array $image) use ($accountId) {
                    return $this->makeImageUrl($image['id'], $image['image_link'], $accountId);
                },
                $lotImages
            );
            $values[] = implode('|', $images);
        }

        return $values;
    }

    /**
     * @param int $imageId
     * @param string $imageUrl
     * @param int|null $accountId null for main account
     * @param bool $isImageWebLink
     * @return string
     */
    private function makeImageUrl(int $imageId, string $imageUrl, ?int $accountId = null, bool $isImageWebLink = false): string
    {
        if ($isImageWebLink) {
            $accountId = $accountId ?? $this->cfg()->get('core->portal->mainAccountId');
            $defaultSize = ImageHelper::new()->detectSizeFromMapping();
            $lotImageUrl = $this->getUrlBuilder()->build(
                LotImageUrlConfig::new()->construct($imageId, $defaultSize, $accountId)
            );
            return $lotImageUrl;
        }
        return $imageUrl;
    }

    /**
     * @param int $timezoneId
     * @return string
     */
    private function getTimezoneLocation(int $timezoneId): string
    {
        $timezone = $this->getTimezoneLoader()->load($timezoneId, true);
        if ($timezone === null) {
            log_warning(sprintf('Timezone with id "%d" not found', $timezoneId));
            return '';
        }
        return $timezone->Location;
    }

    /**
     * @return CustomCsvExportConfig
     */
    private function getConfig(): CustomCsvExportConfig
    {
        return $this->config;
    }
}
