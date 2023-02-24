<?php
/**
 * SAM-4643: Refactor "CSV import-ready export" report
 * https://bidpath.atlassian.net/browse/SAM-4643
 *
 * @author        Oleg Kovalov
 * @since         Jan 18, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
 * This might include adding, changing, or moving columns, modifying header names, modifying data or data format(s)
 */

namespace Sam\Report\Lot\ImportReady;

use Auction;
use AuctionLotItem;
use DateTime;
use Exception;
use LotItem;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotCacheLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\BuyersPremium\Csv\Render\BuyersPremiumCsvRendererCreateTrait;
use Sam\BuyersPremium\Load\BuyersPremiumLoaderCreateTrait;
use Sam\BuyersPremium\Load\BuyersPremiumRangeLoaderCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Transform\Html\HtmlRenderer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\CustomField\Lot\Help\LotCustomFieldHelperCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\Map\LotImportCsvFieldMap;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\HtmlDecodeAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Report\Base\ReportRendererAwareTrait;
use Sam\Report\Lot\ImportReady\Internal\ConsignorCommissionFeeReportDataMakerCreateTrait;
use Sam\Storage\Entity\AwareTrait\LotCustomFieldsAwareTrait;
use Sam\Tax\SamTaxCountryState\Load\SamTaxCountryStateLoaderCreateTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use User;

/**
 * Class LotImportReadyReporter
 * @package Sam\Report\Lot\ImportReady
 * @phpstan-type FieldMap array<string, array{
 *     dbFieldName: string,
 *     format: self::REGULAR|self::BOOLEAN|self::DATE|self::MONEY|self::PERCENT
 * }>
 */
class LotImportReadyReporter extends ReporterBase
{
    use AuctionCacheLoaderAwareTrait;
    use AuctionLotCacheLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use BuyersPremiumCsvRendererCreateTrait;
    use BuyersPremiumLoaderCreateTrait;
    use BuyersPremiumRangeLoaderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use ConsignorCommissionFeeReportDataMakerCreateTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use FilePathHelperAwareTrait;
    use FilterAuctionAwareTrait;
    use HtmlDecodeAwareTrait;
    use LocationLoaderAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldHelperCreateTrait;
    use LotCustomFieldsAwareTrait;
    use LotFieldConfigProviderAwareTrait;
    use LotImageLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use ReportRendererAwareTrait;
    use SamTaxCountryStateLoaderCreateTrait;
    use StaggerClosingHelperCreateTrait;
    use TimedItemLoaderAwareTrait;
    use TimezoneLoaderAwareTrait;

    public const REGULAR = 'regular';
    public const BOOLEAN = 'boolean';
    public const DATE = 'date';
    public const MONEY = 'money';
    public const PERCENT = 'percent';

    /** @var bool */
    protected bool $isStripHtmlTag = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y-His');
            $filename = "auction_lots_{$dateIso}.csv";
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->setOutputFileName($filename);
        }
        return $this->outputFileName;
    }

    protected function outputBody(): string
    {
        $output = '';
        $auctionLotSimpleFieldMap = $this->prepareAuctionLotSimpleFieldMap();
        $itemSimpleFieldMap = $this->prepareItemSimpleFieldMap();
        $auction = $this->getFilterAuction();
        $lotCustomFields = $this->getLotCustomFields();
        $auctionLotGenerator = $this->getAuctionLotLoader()
            ->orderByLotNum(true)
            ->yieldByAuctionId($auction->Id);

        $headerTitles = $this->prepareHeaderTitles();
        $consignorCommissionFeeReportDataMaker = $this->createConsignorCommissionFeeReportDataMaker();
        $commissionHeadersKey = $consignorCommissionFeeReportDataMaker::headerKey();

        foreach ($auctionLotGenerator as $auctionLot) {
            $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
            if (!$lotItem) {
                $logInfo = composeSuffix(['li' => $auctionLot->LotItemId, 'ali' => $auctionLot->Id]);
                log_error("Available lot item not found for Lot Import-Ready CSV export" . $logInfo);
                continue;
            }
            $commissionData = $consignorCommissionFeeReportDataMaker->makeData($lotItem, $auctionLot);
            $bodyRow = [];
            foreach ($headerTitles as $headerKey => $headerTitle) {
                if (array_key_exists($headerKey, $auctionLotSimpleFieldMap)) {
                    $bodyRow[$headerKey] = $this->makeSimpleFieldRowData($auctionLotSimpleFieldMap, $headerKey, $auctionLot);
                } elseif (array_key_exists($headerKey, $itemSimpleFieldMap)) {
                    $bodyRow[$headerKey] = $this->makeSimpleFieldRowData($itemSimpleFieldMap, $headerKey, $lotItem);
                } elseif (in_array($headerKey, $commissionHeadersKey, true)) {
                    $bodyRow[$headerKey] = $commissionData[$headerKey];
                } elseif ($headerKey === Constants\Csv\Lot::BUY_NOW_AMOUNT) {
                    $bodyRow = $this->makeBuyNowAmountRowData($auction, $auctionLot, $lotItem, $bodyRow);
                } elseif ($headerKey === Constants\Csv\Lot::LOCATION) {
                    $bodyRow = $this->makeLocationRowData($lotItem, $bodyRow);
                } else {
                    $rowData = $this->makeRowData($headerKey, $lotItem, $auction, $auctionLot);
                    if ($rowData !== null) {
                        $bodyRow[$headerKey] = $rowData;
                    }
                }
            }
            $bodyRow = $this->makeCustomFieldRow($lotCustomFields, $lotItem, $bodyRow);
            $bodyLine = $this->makeLine($bodyRow);
            $output .= $this->processOutput($bodyLine);
        }
        return $output;
    }

    /**
     * Output titles for csv header (string or echo)
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerTitles = $this->prepareHeaderTitles();
        $headerLine = $this->makeLine($headerTitles);
        return $this->processOutput($headerLine);
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        $this->errorMessage = null;
        $auction = $this->getFilterAuction();
        if ($auction === null) {
            // Unknown auction situation already processed at controller layer in allow() method
            $this->errorMessage = "Auction not found" . composeSuffix(['a' => $this->getFilterAuctionId()]);
        } elseif ($auction->isDeleted()) {
            $this->errorMessage = "Auction already deleted" . composeSuffix(['a' => $this->getFilterAuctionId()]);
        }
        $success = $this->errorMessage === null;
        return $success;
    }

    /**
     * @param DateTime|null $date
     * @param string $timezone
     * @return string
     * @throws Exception
     */
    protected function formatDate(?DateTime $date, string $timezone): string
    {
        return $this->getDateHelper()->formatUtcDate($date, null, $timezone, null, Constants\Date::ISO);
    }

    /**
     * @param int $timezoneId
     * @return string
     */
    private function getTimezoneLocation(int $timezoneId): string
    {
        $timezone = $this->getTimezoneLoader()->load($timezoneId, true);
        if ($timezone === null) {
            log_warning(sprintf('Timezone wit id "%d" not found', $timezoneId));
            return '';
        }
        return $timezone->Location;
    }

    /**
     * @param bool $isStripTag
     * @return $this
     */
    public function enableStripHtmlTag(bool $isStripTag = true): static
    {
        $this->isStripHtmlTag = $isStripTag;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStripHtmlTag(): bool
    {
        return $this->isStripHtmlTag;
    }

    /**
     * Striping html tag and decoding html entities
     * @param string|null $value
     * @return string
     */
    protected function stripHtmlTag(?string $value): string
    {
        if (!$value) {
            return '';
        }
        $value = html_entity_decode(trim(strip_tags($value)));
        return $value;
    }

    protected function removeExtraHeaders(array $headerTitles): array
    {
        unset(
            $headerTitles[Constants\Csv\Lot::RETURNED],
            $headerTitles[Constants\Csv\Lot::GENERAL_NOTE],
            $headerTitles[Constants\Csv\Lot::NOTE_TO_CLERK],
        );
        return $headerTitles;
    }

    /**
     * @param FieldMap $fieldMap
     * @param string $columnName
     * @param LotItem|AuctionLotItem $object
     * @return array|bool|DateTime|float|int|mixed|string|null
     * @throws Exception
     */
    protected function makeSimpleFieldRowData(array $fieldMap, string $columnName, LotItem|AuctionLotItem $object)
    {
        $columnData = $object->{$fieldMap[$columnName]['dbFieldName']};
        $format = $fieldMap[$columnName]['format'];

        $rowData = match ($format) {
            self::REGULAR => $columnData,
            self::MONEY => ((string)$columnData !== '') ? $this->getNumberFormatter()->formatMoneyNto($columnData) : '',
            self::BOOLEAN => $columnData ? "1" : "0",
            self::DATE => $this->formatDate($columnData, $this->getTimezoneLocation($object->TimezoneId)),
            self::PERCENT => $columnData ? $this->getNumberFormatter()->formatPercent($columnData) : '',
        };
        return $rowData;
    }

    /**
     * @return FieldMap
     */
    protected function prepareAuctionLotSimpleFieldMap(): array
    {
        return [
            Constants\Csv\Lot::LOT_NUM_PREFIX => ['format' => self::REGULAR, 'dbFieldName' => 'LotNumPrefix'],
            Constants\Csv\Lot::LOT_NUM => ['format' => self::REGULAR, 'dbFieldName' => 'LotNum'],
            Constants\Csv\Lot::LOT_NUM_EXT => ['format' => self::REGULAR, 'dbFieldName' => 'LotNumExt'],
            Constants\Csv\Lot::TERMS_AND_CONDITIONS => ['format' => self::REGULAR, 'dbFieldName' => 'TermsAndConditions'],
            Constants\Csv\Lot::FEATURED => ['format' => self::BOOLEAN, 'dbFieldName' => 'SampleLot'],
            Constants\Csv\Lot::QUANTITY_DIGITS => ['format' => self::REGULAR, 'dbFieldName' => 'QuantityDigits'],
            Constants\Csv\Lot::QUANTITY_X_MONEY => ['format' => self::BOOLEAN, 'dbFieldName' => 'QuantityXMoney'],
            Constants\Csv\Lot::LISTING_ONLY => ['format' => self::BOOLEAN, 'dbFieldName' => 'ListingOnly'],
            Constants\Csv\Lot::PUBLISH_DATE => ['format' => self::DATE, 'dbFieldName' => 'PublishDate'],
            Constants\Csv\Lot::UNPUBLISH_DATE => ['format' => self::DATE, 'dbFieldName' => 'UnpublishDate'],
            Constants\Csv\Lot::START_BIDDING_DATE => ['format' => self::DATE, 'dbFieldName' => 'StartBiddingDate'],
            Constants\Csv\Lot::START_CLOSING_DATE => ['format' => self::DATE, 'dbFieldName' => 'StartClosingDate'],
            Constants\Csv\Lot::END_PREBIDDING_DATE => ['format' => self::DATE, 'dbFieldName' => 'EndPrebiddingDate'],
            Constants\Csv\Lot::SEO_URL => ['format' => self::REGULAR, 'dbFieldName' => 'SeoUrl'],
            Constants\Csv\Lot::GROUP_ID => ['format' => self::REGULAR, 'dbFieldName' => 'GroupId'],
            Constants\Csv\Lot::BUY_NOW_SELECT_QUANTITY => ['format' => self::REGULAR, 'dbFieldName' => 'BuyNowSelectQuantityEnabled'],
            Constants\Csv\Lot::HP_TAX_SCHEMA_ID => ['format' => self::REGULAR, 'dbFieldName' => 'HpTaxSchemaId'],
            Constants\Csv\Lot::BP_TAX_SCHEMA_ID => ['format' => self::REGULAR, 'dbFieldName' => 'BpTaxSchemaId'],
        ];
    }

    /**
     * @return FieldMap
     */
    protected function prepareItemSimpleFieldMap(): array
    {
        return [
            Constants\Csv\Lot::ITEM_NUM => ['format' => self::REGULAR, 'dbFieldName' => 'ItemNum'],
            Constants\Csv\Lot::ITEM_NUM_EXT => ['format' => self::REGULAR, 'dbFieldName' => 'ItemNumExt'],
            Constants\Csv\Lot::ONLY_TAX_BP => ['format' => self::BOOLEAN, 'dbFieldName' => 'OnlyTaxBp'],
            Constants\Csv\Lot::STARTING_BID => ['format' => self::MONEY, 'dbFieldName' => 'StartingBid'],
            Constants\Csv\Lot::COST => ['format' => self::MONEY, 'dbFieldName' => 'Cost'],
            Constants\Csv\Lot::REPLACEMENT_PRICE => ['format' => self::MONEY, 'dbFieldName' => 'ReplacementPrice'],
            Constants\Csv\Lot::LOW_ESTIMATE => ['format' => self::MONEY, 'dbFieldName' => 'LowEstimate'],
            Constants\Csv\Lot::HIGH_ESTIMATE => ['format' => self::MONEY, 'dbFieldName' => 'HighEstimate'],
            Constants\Csv\Lot::RESERVE_PRICE => ['format' => self::MONEY, 'dbFieldName' => 'ReservePrice'],
            Constants\Csv\Lot::SALES_TAX => ['format' => self::PERCENT, 'dbFieldName' => 'SalesTax'],
            Constants\Csv\Lot::NO_TAX_OUTSIDE_STATE => ['format' => self::BOOLEAN, 'dbFieldName' => 'NoTaxOos'],
            Constants\Csv\Lot::BP_RANGE_CALCULATION => ['format' => self::REGULAR, 'dbFieldName' => 'BpRangeCalculation'],
            Constants\Csv\Lot::ADDITIONAL_BP_INTERNET => ['format' => self::PERCENT, 'dbFieldName' => 'AdditionalBpInternet'],
            Constants\Csv\Lot::SEO_META_TITLE => ['format' => self::REGULAR, 'dbFieldName' => 'SeoMetaTitle'],
            Constants\Csv\Lot::SEO_META_KEYWORDS => ['format' => self::REGULAR, 'dbFieldName' => 'SeoMetaKeywords'],
            Constants\Csv\Lot::SEO_META_DESCRIPTION => ['format' => self::REGULAR, 'dbFieldName' => 'SeoMetaDescription'],
            Constants\Csv\Lot::FB_OG_DESCRIPTION => ['format' => self::REGULAR, 'dbFieldName' => 'FbOgDescription'],
            Constants\Csv\Lot::FB_OG_TITLE => ['format' => self::REGULAR, 'dbFieldName' => 'FbOgTitle'],
            Constants\Csv\Lot::FB_OG_IMAGE_URL => ['format' => self::REGULAR, 'dbFieldName' => 'FbOgImageUrl'],
        ];
    }

    protected function makeCategoryListRowData(LotItem $lotItem): string
    {
        $categoryList = '';
        $lotCategories = $this->getLotCategoryLoader()->loadForLot($lotItem->Id, true);
        foreach ($lotCategories as $lotCategory) {
            if ($categoryList !== '') {
                $categoryList .= ';';
            }
            $categoryList .= $lotCategory->Name;
        }
        return $categoryList;
    }

    protected function makeConsignorNameRowData(LotItem $lotItem): string
    {
        $lotItemConsignor = $this->getUserLoader()->load($lotItem->ConsignorId);
        return ($lotItemConsignor instanceof User
            && $lotItemConsignor->UserStatusId === Constants\User::US_ACTIVE)
            ? $lotItemConsignor->Username : '';
    }

    protected function makeCsvImagesRowData(LotItem $lotItem): string
    {
        //images
        $lotImages = $this->getLotImageLoader()->loadForLot($lotItem->Id, [], true);
        $lotImageLinks = [];

        foreach ($lotImages as $img) {
            $lotImageLinks[] = $img->ImageLink;
        }
        $csvImages = '';
        if (count($lotImageLinks) > 0) {
            $csvImages = implode('|', $lotImageLinks);
        }
        return $csvImages;
    }

    protected function makeQuantityRowData(AuctionLotItem $auctionLot): string
    {
        $quantity = '';
        if ($auctionLot->Quantity !== null) {
            $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($auctionLot->LotItemId, $auctionLot->AuctionId);
            $quantity = $this->getNumberFormatter()->formatNto($auctionLot->Quantity, $quantityScale);
        }
        return $quantity;
    }

    protected function makeTaxStateRowData(LotItem $lotItem): string
    {
        $taxStateList = '';
        if (AddressChecker::new()->isUsa($lotItem->TaxDefaultCountry)) {
            $samTaxCountryStates = $this->createSamTaxCountryStateLoader()->loadStates(
                $lotItem->TaxDefaultCountry,
                null,
                null,
                $lotItem->Id
            );
            $addressRenderer = AddressRenderer::new();
            foreach ($samTaxCountryStates as $samTaxCountryState) {
                $stateName = $addressRenderer->usaStateName($samTaxCountryState);
                $taxStateList .= $stateName ? $stateName . '|' : '';
            }
            $taxStateList = rtrim($taxStateList, '|');
        }
        return $taxStateList;
    }

    protected function makeBpSettingsRowData(LotItem $lotItem): string
    {
        if ($lotItem->hasNamedBuyersPremium()) {
            $buyersPremium = $this->createBuyersPremiumLoader()->load($lotItem->BuyersPremiumId);
            $bpSetting = $buyersPremium->ShortName ?? '';
        } else {
            $bpr = $this->createBuyersPremiumRangeLoader()->loadBpRangeByLotItemId($lotItem->Id);
            $bpSetting = $this->createBuyersPremiumCsvRenderer()->arrayObjectToString($bpr);
        }
        return $bpSetting;
    }

    protected function makeStartClosingDate(Auction $auction, AuctionLotItem $auctionLot, string $lotTzLocation): string
    {
        if (
            $auction->ExtendAll
            && $auctionLot->isActive()
        ) {
            $tzLocation = $this->getTimezoneLocation($auction->TimezoneId);
            if ($auction->StaggerClosing) {
                $lotEndDate = $this->createStaggerClosingHelper()->calcEndDate(
                    $auction->StartClosingDate,
                    $auction->LotsPerInterval,
                    $auction->StaggerClosing,
                    $auctionLot->Order
                );
                $startClosingDateFormatted = $this->formatDate($lotEndDate, $tzLocation);
            } else {
                $startClosingDateFormatted = $this->formatDate($auction->StartClosingDate, $tzLocation);
            }
        } else {
            $startClosingDateFormatted = $this->formatDate($auctionLot->StartClosingDate, $lotTzLocation);
        }
        return $startClosingDateFormatted;
    }

    protected function makeStartBiddingDate(Auction $auction, AuctionLotItem $auctionLot, string $lotTzLocation): string
    {
        if (
            $auction->ExtendAll
            && $auctionLot->isActive()
        ) {
            $tzLocation = $this->getTimezoneLocation($auction->TimezoneId);
            $startBiddingDateFormatted = $this->formatDate($auction->StartBiddingDate, $tzLocation);
        } else {
            $startBiddingDateFormatted = $this->formatDate($auctionLot->StartBiddingDate, $lotTzLocation);
        }
        return $startBiddingDateFormatted;
    }

    protected function makeLocationRowData(LotItem $lotItem, array $bodyRow): array
    {
        $location = $this->getLocationLoader()->loadCommonOrSpecificLocation(Constants\Location::TYPE_LOT_ITEM, $lotItem, true);
        $bodyRow[Constants\Csv\Lot::LOCATION] = $location->Name ?? '';
        $bodyRow[Constants\Csv\Lot::LOCATION_ADDRESS] = $location->Address ?? '';
        $bodyRow[Constants\Csv\Lot::LOCATION_COUNTRY] = $location->Country ?? '';
        $bodyRow[Constants\Csv\Lot::LOCATION_CITY] = $location->City ?? '';
        $bodyRow[Constants\Csv\Lot::LOCATION_COUNTY] = $location->County ?? '';
        $bodyRow[Constants\Csv\Lot::LOCATION_LOGO] = $location->Logo ?? '';
        $bodyRow[Constants\Csv\Lot::LOCATION_STATE] = $location->State ?? '';
        $bodyRow[Constants\Csv\Lot::LOCATION_ZIP] = $location->Zip ?? '';
        return $bodyRow;
    }

    protected function makeBulkControlRowData(AuctionLotItem $auctionLot): string
    {
        $bulkControl = '';
        if ($auctionLot->hasMasterRole()) {
            $bulkControl = Constants\LotBulkGroup::LBGR_MASTER;
        } elseif ($auctionLot->hasPiecemealRole()) {
            $masterAuctionLot = $this->getAuctionLotLoader()->loadById($auctionLot->BulkMasterId);
            $bulkControl = $this->getLotRenderer()->renderLotNo($masterAuctionLot);
        }
        return $bulkControl;
    }

    protected function makeCustomFieldRow(array $lotCustomFields, ?LotItem $lotItem, array $bodyRow): array
    {
        $fieldConfigProvider = $this->getLotFieldConfigProvider()->setFieldMap(LotImportCsvFieldMap::new());
        $accountId = $this->getSystemAccountId();
        foreach ($lotCustomFields as $lotCustomField) {
            if (!$fieldConfigProvider->isVisibleCustomField($lotCustomField->Id, $accountId)) {
                continue;
            }
            $value = '';
            $lotCustomData = $this->createLotCustomDataLoader()->load($lotCustomField->Id, $lotItem->Id, true);
            if ($lotCustomData) {
                // SAM-1570
                $renderMethod = $this->createLotCustomFieldHelper()->makeCustomMethodName($lotCustomField->Name, 'Render');
                if (method_exists($this, $renderMethod)) {
                    $value = $this->$renderMethod(
                        $lotCustomField,
                        $lotCustomData,
                        $this->getEncoding(),
                        $this->getFilterAuction()->Id
                    );
                } else {
                    switch ($lotCustomField->Type) {
                        case Constants\CustomField::TYPE_INTEGER:
                            $value = $lotCustomData->Numeric;
                            break;

                        case Constants\CustomField::TYPE_DECIMAL:
                            if ($lotCustomData->Numeric !== null) {
                                $precision = (int)$lotCustomField->Parameters;
                                $realValue = $lotCustomData->calcDecimalValue($precision);
                                $value = $this->getNumberFormatter()->formatNto($realValue, $precision);
                            } else {
                                $value = '';
                            }
                            break;

                        case Constants\CustomField::TYPE_TEXT:
                        case Constants\CustomField::TYPE_FILE:
                        case Constants\CustomField::TYPE_POSTALCODE:
                        case Constants\CustomField::TYPE_YOUTUBELINK:
                            $value = $lotCustomData->Text;
                            break;

                        case Constants\CustomField::TYPE_FULLTEXT:
                            $value = $lotCustomData->Text;
                            $isRichText = $lotCustomField->FckEditor;
                            if ($isRichText && $this->isStripHtmlTag()) {
                                $value = $this->stripHtmlTag($value);
                            }
                            break;

                        case Constants\CustomField::TYPE_SELECT:
                            $value = trim($lotCustomData->Text);
                            break;

                        case Constants\CustomField::TYPE_DATE:
                            if ($lotCustomData->Numeric !== null) {
                                $value = (new DateTime())
                                    ->setTimestamp($lotCustomData->Numeric)
                                    ->format(Constants\Date::ISO);
                            } else {
                                $value = '';
                            }

                            break;
                    }
                }
            }
            $bodyRow[$lotCustomField->Name] = $value;
        }
        return $bodyRow;
    }

    protected function prepareHeaderTitles(): array
    {
        $auction = $this->getFilterAuction();
        $fieldConfigProvider = $this->getLotFieldConfigProvider()->setFieldMap(LotImportCsvFieldMap::new());
        $isLiveOrHybrid = $auction->isLiveOrHybrid();
        if ($isLiveOrHybrid) {
            $headerTitles = $this->cfg()->get('csv->admin->live')->toArray();
        } else {
            $headerTitles = $this->cfg()->get('csv->admin->timed')->toArray();
        }
        $headerTitles = $this->removeExtraHeaders($headerTitles);
        $skipItemTitles = $this->cfg()->get('core->lot->itemNo->concatenated')
            ? [Constants\Csv\Lot::ITEM_NUM, Constants\Csv\Lot::ITEM_NUM_EXT]
            : [Constants\Csv\Lot::ITEM_FULL_NUMBER];
        $skipLotTitles = $this->cfg()->get('core->lot->lotNo->concatenated')
            ? [Constants\Csv\Lot::LOT_NUM_PREFIX, Constants\Csv\Lot::LOT_NUM, Constants\Csv\Lot::LOT_NUM_EXT]
            : [Constants\Csv\Lot::LOT_FULL_NUMBER];
        $headerTitles = array_diff_key($headerTitles, array_flip($skipItemTitles), array_flip($skipLotTitles));

        $accountId = $this->getSystemAccountId();
        $headerTitles = array_filter(
            $headerTitles,
            static function (string $field) use ($fieldConfigProvider, $accountId) {
                return $fieldConfigProvider->isVisible($field, $accountId);
            },
            ARRAY_FILTER_USE_KEY
        );

        $lotCustomFields = $this->getLotCustomFields();
        foreach ($lotCustomFields ?: [] as $lotCustomField) {
            if (!$fieldConfigProvider->isVisibleCustomField($lotCustomField->Id, $accountId)) {
                continue;
            }
            $headerTitles[] = $lotCustomField->Name;
        }
        return $headerTitles;
    }

    protected function makeRowData(
        string $headerKey,
        LotItem $lotItem,
        Auction $auction,
        AuctionLotItem $auctionLot,
    ): ?string {
        $lotTzLocation = $this->getTimezoneLocation($auctionLot->TimezoneId);
        $categoryList = $this->makeCategoryListRowData($lotItem);
        $lotName = $this->getLotRenderer()->makeName($lotItem->Name, $auction->TestAuction);
        $lotIncrements = $this->getReportRenderer()->makeLotBidIncrements($lotItem->Id);
        $itemFullNo = $this->getLotRenderer()->makeItemNo($lotItem->ItemNum, $lotItem->ItemNumExt);
        $lotFullNo = $this->getLotRenderer()->makeLotNo($auctionLot->LotNum, $auctionLot->LotNumExt, $auctionLot->LotNumPrefix);
        $consignorName = $this->makeConsignorNameRowData($lotItem);
        $csvImages = $this->makeCsvImagesRowData($lotItem);
        $quantity = $this->makeQuantityRowData($auctionLot);
        $taxCountry = AddressRenderer::new()->countryName($lotItem->TaxDefaultCountry);
        $taxStateList = $this->makeTaxStateRowData($lotItem);
        $bpSetting = $this->makeBpSettingsRowData($lotItem);
        $startClosingDateFormatted = $this->makeStartClosingDate($auction, $auctionLot, $lotTzLocation);
        $startBiddingDateFormatted = $this->makeStartBiddingDate($auction, $auctionLot, $lotTzLocation);
        $bulkControl = $this->makeBulkControlRowData($auctionLot);
        $lotDescription = $lotItem->Description;
        $lotChanges = $lotItem->Changes;
        $warranty = $lotItem->Warranty;

        if ($this->isHtmlDecode()) {
            $lotDescription = HtmlRenderer::new()->decodeHtmlEntity($lotDescription);
        }

        if ($this->isStripHtmlTag()) {
            $lotChanges = $this->stripHtmlTag($lotChanges);
            $warranty = $this->stripHtmlTag($warranty);
            $lotDescription = $this->stripHtmlTag($lotDescription);
        }
        try {
            return match ($headerKey) {
                Constants\Csv\Lot::ITEM_FULL_NUMBER => $itemFullNo,
                Constants\Csv\Lot::LOT_FULL_NUMBER => $lotFullNo,
                Constants\Csv\Lot::LOT_CATEGORY => $categoryList,
                Constants\Csv\Lot::LOT_NAME => $lotName,
                Constants\Csv\Lot::INCREMENT => $lotIncrements,
                Constants\Csv\Lot::CONSIGNOR => $consignorName,
                Constants\Csv\Lot::LOT_IMAGE => $csvImages,
                Constants\Csv\Lot::QUANTITY => $quantity,
                Constants\Csv\Lot::ITEM_TAX_COUNTRY => $taxCountry,
                Constants\Csv\Lot::ITEM_TAX_STATES => $taxStateList,
                Constants\Csv\Lot::BP_SETTING => $bpSetting,
                Constants\Csv\Lot::LOT_DESCRIPTION => $lotDescription,
                Constants\Csv\Lot::CHANGES => $lotChanges,
                Constants\Csv\Lot::WARRANTY => $warranty,
                Constants\Csv\Lot::START_BIDDING_DATE => $startBiddingDateFormatted,
                Constants\Csv\Lot::START_CLOSING_DATE => $startClosingDateFormatted,
                Constants\Csv\Lot::BULK_WIN_BID_DISTRIBUTION => Constants\LotBulkGroup::$bulkWinBidDistributionNames[$auctionLot->BulkMasterWinBidDistribution] ?? '',
                Constants\Csv\Lot::BULK_CONTROL => $bulkControl,
            };
        } catch (\UnhandledMatchError $e) {
            return null;
        }
    }

    protected function makeBuyNowAmountRowData(
        Auction $auction,
        AuctionLotItem $auctionLot,
        LotItem $lotItem,
        array $bodyRow
    ): array {
        $buyNowAmountFormatted = $auctionLot->hasBuyNowAmount()
            ? $this->getNumberFormatter()->formatMoneyNto($auctionLot->BuyNowAmount)
            : '';
        if (!$auction->isLiveOrHybrid()) {
            $timedItem = $this->getTimedItemLoader()->load($lotItem->Id, $auction->Id);
            $bodyRow[Constants\Csv\Lot::BEST_OFFER] = $timedItem && $timedItem->BestOffer ? 1 : 0;
            $bodyRow[Constants\Csv\Lot::BUY_NOW_AMOUNT] = $buyNowAmountFormatted;
            $bodyRow[Constants\Csv\Lot::NO_BIDDING] = $timedItem && $timedItem->NoBidding ? 1 : 0;
        } else {
            $bodyRow[Constants\Csv\Lot::BUY_NOW_AMOUNT] = $buyNowAmountFormatted;
        }
        return $bodyRow;
    }
}
