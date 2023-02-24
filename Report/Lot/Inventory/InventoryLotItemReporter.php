<?php
/**
 * SAM-4622: Refactor inventory report
 * https://bidpath.atlassian.net/browse/SAM-4622
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 20, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
 * This might include adding, changing, or moving columns, modifying header names, modifying data or data format(s)
 *
 * Custom methods can be used there or in customized class (SAM-1570)
 *
 * Optional function called when rendering exported custom lot item field value
 * param LotItemCustField $lotItemCustomField the custom lot item field object
 * param LotItemCustData $lotItemCustomData the custom lot item field data
 * return string the rendered value
 * public function LotCustomField_{Field name}_Render(LotItemCustField $lotItemCustomField, LotItemCustData $lotItemCustomData)
 *
 * {Field name} - Camel cased name of custom field (see TextTransformer::toCamelCase())
 */

namespace Sam\Report\Lot\Inventory;

use LotCategory;
use LotImage;
use LotItemCustField;
use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\BuyersPremium\Csv\Render\BuyersPremiumCsvRendererCreateTrait;
use Sam\BuyersPremium\Load\BuyersPremiumLoaderCreateTrait;
use Sam\BuyersPremium\Load\BuyersPremiumRangeLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Transform\Html\HtmlRenderer;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\Lot\Qform\LotCustomFieldFilterControlsManagerCreateTrait;
use Sam\CustomField\Lot\Render\Csv\LotCustomFieldCsvRendererAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Report\Base\Csv\HtmlDecodeAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Storage\Entity\AwareTrait\LotCustomFieldsAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class InventoryLotItemReporter
 */
class InventoryLotItemReporter extends ReporterBase
{
    use ApplicationAccessCheckerCreateTrait;
    use BaseCustomFieldHelperAwareTrait;
    use BuyersPremiumCsvRendererCreateTrait;
    use BuyersPremiumLoaderCreateTrait;
    use BuyersPremiumRangeLoaderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use FilePathHelperAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterAwareTrait;
    use HtmlDecodeAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCustomFieldCsvRendererAwareTrait;
    use LotCustomFieldFilterControlsManagerCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotCustomFieldsAwareTrait;
    use LotImageLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use NumberFormatterAwareTrait;

    /** @var DataLoader|null */
    protected ?DataLoader $dataLoader = null;
    /**
     * GET request parameters are used for initialization of LotCustomFieldFilterControlsManager, it produces some filtering data for data loader
     * @var string[]
     */
    protected array $getParams = [];
    protected ?int $editorUserId = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function setEditorUserId(?int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * @return LotItemCustField[]
     */
    public function getLotCustomFields(): array
    {
        if ($this->lotCustomFields === null) {
            $this->lotCustomFields = $this->createLotCustomFieldLoader()->loadAll();
        }
        return $this->lotCustomFields;
    }

    /**
     * @return string[]
     */
    public function getGetParams(): array
    {
        return $this->getParams;
    }

    /**
     * @param string[] $params
     * @return static
     */
    public function setGetParams(array $params): static
    {
        $this->getParams = $params;
        return $this;
    }

    /**
     * @param DataLoader $dataLoader
     * @return static
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            // Detect filter account id
            $filterAccountId = null;
            if ($this->isAccountFiltering()) {
                if ($this->getFilterAccountId()) {
                    $filterAccountId = $this->getFilterAccountId();
                }
            } else {
                $filterAccountId = $this->getSystemAccountId();
            }

            // Build lot custom field filter parameters
            $availableLotCustomFields = $this->createLotCustomFieldLoader()->loadInAdminSearch();
            $lotCustomFieldFilterControlsManager = $this->createLotCustomFieldFilterControlsManager()
                ->setGroupId($this->getGroupId())
                ->setCustomFields($availableLotCustomFields)
                ->setGetParams($this->getGetParams());
            $lotCustomFieldFilterParams = $lotCustomFieldFilterControlsManager->getFilterParamsByGet();

            $this->dataLoader = DataLoader::new()
                ->setEditorUserId($this->editorUserId)
                ->filterAccountId($filterAccountId)
                ->filterAuctionId($this->getFilterAuctionId())
                ->filterConsignorUserId($this->getConsignorUserId())
                ->filterGroupId($this->getGroupId())
                ->filterLotCategoryId($this->getLotCategoryId())
                ->filterOverallLotStatus($this->getOverallLotStatus())
                ->filterSearchKey($this->getSearchKey())
                ->setAvailableLotCustomFields($availableLotCustomFields)
                ->setLotCustomFieldFilterParams($lotCustomFieldFilterParams)
                ->setLotCustomFields($this->getLotCustomFields());
        }
        return $this->dataLoader;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $header = 'inventory';
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y');
            $filename = "{$header}-{$dateIso}.csv";
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->setOutputFileName($filename);
        }
        return $this->outputFileName;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $output = '';
        $rowFetcher = $this->getDataLoader()->createRowFetcher();
        /** @var array $row */
        foreach ($rowFetcher as $row) {
            $bodyLine = $this->buildBodyLine($row);
            $output .= $this->processOutput($bodyLine);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    protected function buildBodyLine(array $row): string
    {
        $lotItemId = (int)$row['lot_id'];
        $itemNum = $row['item_num'];
        $itemNumExt = $row['item_num_ext'];
        $lotName = $row['lot_name'];
        $locationName = $row['location_name'];
        $lotDescription = (string)$row['lot_desc'];
        if ($this->isHtmlDecode()) {
            // This should be entity decoded since it can be html tags/entity
            $lotDescription = HtmlRenderer::new()->decodeHtmlEntity($lotDescription);
        }
        $lotChanges = $row['lot_changes'];
        $lotWarranty = $row['lot_warranty'];
        $lotSeoMetaTitle = (string)$row['seo_meta_title'];
        $lotSeoMetaKeywords = (string)$row['seo_meta_keywords'];
        $lotSeoMetaDescription = (string)$row['seo_meta_description'];
        $lowEstimate = (string)$row['low_est'];
        $highEstimate = (string)$row['high_est'];
        $startingBid = (string)$row['start_bid'];
        $bidIncrements = $this->getDataLoader()->loadLotBidIncrements($lotItemId);
        $pairCount = 0;
        $pairs = [];
        foreach ($bidIncrements as $pair) {
            if ($pairCount === 0) {
                $pairs[] = $pair['increment'];
            } else {
                $pairs[] = $pair['amount'] . ':' . $pair['increment'];
            }
            $pairCount++;
        }
        $bidIncrement = !empty($pairs) ? implode('|', $pairs) : '';
        $cost = (string)$row['cost'];
        $replacementPrice = (string)$row['replacement_price'];
        $reservePrice = (string)$row['reserve_price'];
        $hammerPrice = (string)$row['hammer_price'];
        $salesTaxAmount = (string)$row['sales_tax'];
        $csvIsTaxOos = $this->getReportTool()->renderBool((bool)$row['no_tax_oos']);
        $csvIsOnlyTaxBp = $this->getReportTool()->renderBool((bool)$row['only_tax_bp']);
        $csvIsReturned = $this->getReportTool()->renderBool((bool)$row['returned']);
        $consignorText = $row['consignor'];
        $buyersPremiumId = (int)$row['buyers_premium_id'];
        $bpRangeCalculation = $row['bp_range_calculation'];
        $additionalBpInternet = $row['additional_bp_internet'];

        $lowEstimateFormatted = $lowEstimate !== ''
            ? $this->getNumberFormatter()->formatMoneyNto($lowEstimate) : '';
        $highEstimateFormatted = $highEstimate !== ''
            ? $this->getNumberFormatter()->formatMoneyNto($highEstimate) : '';
        $startingBidFormatted = $startingBid !== ''
            ? $this->getNumberFormatter()->formatMoneyNto($startingBid) : '';
        $reservePriceFormatted = $reservePrice !== ''
            ? $this->getNumberFormatter()->formatMoneyNto($reservePrice) : '';
        $hammerPriceFormatted = $hammerPrice !== ''
            ? $this->getNumberFormatter()->formatMoneyNto($hammerPrice) : '';
        $replacementFormatted = $replacementPrice !== ''
            ? $this->getNumberFormatter()->formatMoneyNto($replacementPrice) : '';
        $costFormatted = $cost !== '' ? $this->getNumberFormatter()->formatMoneyNto($cost) : '';
        $salesTaxFormatted = $salesTaxAmount !== ''
            ? $this->getNumberFormatter()->formatPercent($salesTaxAmount) : '';

        $lotCategories = $this->getLotCategoryLoader()->loadForLot($lotItemId, true);
        $lotCategoryNames = array_map(
            static function (LotCategory $lotCategory) {
                return $lotCategory->Name;
            },
            $lotCategories
        );
        $lotCategoryList = implode(';', $lotCategoryNames);

        $lotImages = $this->getLotImageLoader()->loadForLot($lotItemId, [], true);
        $lotImageNames = array_map(
            static function (LotImage $lotImage) {
                return $lotImage->ImageLink;
            },
            $lotImages
        );
        $lotImageList = implode('|', $lotImageNames);

        if ($buyersPremiumId) {
            $buyersPremium = $this->createBuyersPremiumLoader()->load($buyersPremiumId);
            $bpSetting = $buyersPremium->ShortName ?? '';
        } else {
            $bpr = $this->createBuyersPremiumRangeLoader()->loadBpRangeByLotItemId($lotItemId);
            $bpSetting = $this->createBuyersPremiumCsvRenderer()->arrayObjectToString($bpr);
        }

        $bodyRow = [
            $itemNum,
            $itemNumExt,
            $lotCategoryList,
            $lotName,
            $lotDescription,
            $lotWarranty,
            $lotImageList,
            $lowEstimateFormatted,
            $highEstimateFormatted,
            $startingBidFormatted,
            $bidIncrement,
            $costFormatted,
            $replacementFormatted,
            $reservePriceFormatted,
            $consignorText,
            $hammerPriceFormatted,
            $salesTaxFormatted,
            $csvIsTaxOos,
            $csvIsReturned,
            $lotChanges,
            $csvIsOnlyTaxBp,
            $locationName,
            $bpSetting,
            $bpRangeCalculation,
            $additionalBpInternet,
            $lotSeoMetaTitle,
            $lotSeoMetaKeywords,
            $lotSeoMetaDescription,
        ];

        $lotCustomFields = $this->getLotCustomFields();
        if (count($lotCustomFields) > 0) {
            foreach ($lotCustomFields as $lotCustomField) {
                $alias = $this->getBaseCustomFieldHelper()->makeFieldAlias($lotCustomField->Name);
                $bodyRow[] = $this->getLotCustomFieldCsvRenderer()
                    ->renderByValue($lotCustomField->Type, $row[$alias], $lotCustomField->Parameters);
            }
        }

        $bodyLine = $this->makeLine($bodyRow);

        return $bodyLine;
    }

    /**
     * Output titles for csv header (string or echo)
     * @return string
     */
    protected function outputTitles(): string
    {
        $columnHeaders = $this->cfg()->get('csv->admin->inventory');
        $headerTitles = [
            $columnHeaders->{Constants\Csv\Lot::ITEM_NUM},
            $columnHeaders->{Constants\Csv\Lot::ITEM_NUM_EXT},
            $columnHeaders->{Constants\Csv\Lot::LOT_CATEGORY},
            $columnHeaders->{Constants\Csv\Lot::LOT_NAME},
            $columnHeaders->{Constants\Csv\Lot::LOT_DESCRIPTION},
            $columnHeaders->{Constants\Csv\Lot::WARRANTY},
            $columnHeaders->{Constants\Csv\Lot::LOT_IMAGE},
            $columnHeaders->{Constants\Csv\Lot::LOW_ESTIMATE},
            $columnHeaders->{Constants\Csv\Lot::HIGH_ESTIMATE},
            $columnHeaders->{Constants\Csv\Lot::STARTING_BID},
            $columnHeaders->{Constants\Csv\Lot::INCREMENT},
            $columnHeaders->{Constants\Csv\Lot::COST},
            $columnHeaders->{Constants\Csv\Lot::REPLACEMENT_PRICE},
            $columnHeaders->{Constants\Csv\Lot::RESERVE_PRICE},
            $columnHeaders->{Constants\Csv\Lot::CONSIGNOR},
            'HammerPrice',
            $columnHeaders->{Constants\Csv\Lot::SALES_TAX},
            $columnHeaders->{Constants\Csv\Lot::NO_TAX_OUTSIDE_STATE},
            $columnHeaders->{Constants\Csv\Lot::RETURNED},
            $columnHeaders->{Constants\Csv\Lot::CHANGES},
            $columnHeaders->{Constants\Csv\Lot::ONLY_TAX_BP},
            $columnHeaders->{Constants\Csv\Lot::LOCATION},
            $columnHeaders->{Constants\Csv\Lot::BP_SETTING},
            $columnHeaders->{Constants\Csv\Lot::BP_RANGE_CALCULATION},
            $columnHeaders->{Constants\Csv\Lot::ADDITIONAL_BP_INTERNET},
            $columnHeaders->{Constants\Csv\Lot::SEO_META_TITLE},
            $columnHeaders->{Constants\Csv\Lot::SEO_META_KEYWORDS},
            $columnHeaders->{Constants\Csv\Lot::SEO_META_DESCRIPTION},
        ];

        $lotCustomFields = $this->getLotCustomFields();
        foreach ($lotCustomFields ?: [] as $lotCustomField) {
            $headerTitles[] = $lotCustomField->Name;
        }

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
    }

    /**
     * @return bool
     */
    public function isAccountFiltering(): bool
    {
        return $this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant(
            $this->editorUserId,
            $this->getSystemAccountId(),
            true
        );
    }
}
