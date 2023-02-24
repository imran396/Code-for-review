<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\Dto\LotItem;

use LotItemCustField;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\BuyersPremium\Csv\Parse\BuyersPremiumCsvParserCreateTrait;
use Sam\Commission\Csv\CommissionCsvParserCreateTrait;
use Sam\Consignor\Commission\Csv\ConsignorCommissionRangeCsvTransformerCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Html\HtmlRenderer;
use Sam\CustomField\Base\Csv\CustomFieldCsvHelperCreateTrait;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\Base\Dto\ValidationStatus;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerDtoFactory;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\Import\Csv\Base\ImportCsvColumnsHelperCreateTrait;
use Sam\Import\Csv\Read\CsvRow;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class LotItemEntityMakerDtoFactory
 * @package Sam\Import\Csv\Lot\Internal\Dto
 */
class LotItemEntityMakerDtoFactory extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use BuyersPremiumCsvParserCreateTrait;
    use CommissionCsvParserCreateTrait;
    use ConsignorCommissionRangeCsvTransformerCreateTrait;
    use CustomFieldCsvHelperCreateTrait;
    use ImportCsvColumnsHelperCreateTrait;
    use SettingsManagerAwareTrait;

    protected const IMAGE_DELIMITER = '|';
    protected const TAX_STATE_DELIMITER = '|';
    protected const CATEGORY_DELIMITER = ';';

    protected int $editorUserid;
    protected int $lotItemAccountId;
    protected int $systemAccountId;
    /**
     * @var LotItemCustField[]
     */
    protected array $lotCustomFields;
    protected bool $shouldReplaceBreaksWithHtml;
    protected ?int $auctionId;
    protected bool $clearEmptyFields;
    protected string $encoding;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $editorUserId
     * @param int $lotItemAccountId
     * @param int $systemAccountId
     * @param array $customFields
     * @param string $encoding
     * @param bool $clearEmptyFields
     * @param bool $shouldReplaceBreaksWithHtml
     * @param int|null $auctionId
     * @return static
     */
    public function construct(
        int $editorUserId,
        int $lotItemAccountId,
        int $systemAccountId,
        array $customFields,
        string $encoding,
        bool $clearEmptyFields,
        bool $shouldReplaceBreaksWithHtml,
        ?int $auctionId = null
    ): static {
        $this->auctionId = $auctionId;
        $this->editorUserid = $editorUserId;
        $this->lotItemAccountId = $lotItemAccountId;
        $this->lotCustomFields = $customFields;
        $this->systemAccountId = $systemAccountId;
        $this->shouldReplaceBreaksWithHtml = $shouldReplaceBreaksWithHtml;
        $this->clearEmptyFields = $clearEmptyFields;
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * Construct and fill LotItemMakerInputDto and LotItemMakerConfigDto with data from a row in CSV file
     *
     * @param CsvRow $row
     * @param int|null $lotItemId
     * @param ValidationStatus $validationStatus
     * @return array
     */
    public function create(CsvRow $row, ?int $lotItemId, ValidationStatus $validationStatus = ValidationStatus::NONE): array
    {
        /**
         * @var LotItemMakerInputDto $lotItemInputDto
         * @var LotItemMakerConfigDto $lotItemConfigDto
         */
        [$lotItemInputDto, $lotItemConfigDto] = LotItemMakerDtoFactory::new()->createDtos(
            Mode::CSV,
            $this->editorUserid,
            $this->lotItemAccountId,
            $this->systemAccountId
        );

        $lotItemInputDto = $this->fillLotItemInputDto($lotItemInputDto, $row, $lotItemId);
        $lotItemConfigDto = $this->fillLotItemConfigDto($lotItemConfigDto, $row);
        $lotItemConfigDto->setValidationStatus($validationStatus);

        return [$lotItemInputDto, $lotItemConfigDto];
    }

    /**
     * @param LotItemMakerInputDto $lotItemInputDto
     * @param CsvRow $row
     * @param int|null $lotItemId
     * @return LotItemMakerInputDto
     */
    protected function fillLotItemInputDto(LotItemMakerInputDto $lotItemInputDto, CsvRow $row, ?int $lotItemId): LotItemMakerInputDto
    {
        $lotItemInputDto->additionalBpInternet = $row->getCell(Constants\Csv\Lot::ADDITIONAL_BP_INTERNET);
        $lotItemInputDto->bpRangeCalculation = strtolower($row->getCell(Constants\Csv\Lot::BP_RANGE_CALCULATION));
        $bpSetting = $row->getCell(Constants\Csv\Lot::BP_SETTING);
        if ($this->isBpShortName($bpSetting)) {
            $lotItemInputDto->bpRule = $bpSetting;
        } else {
            $lotItemInputDto->buyersPremiumString = $bpSetting;
        }
        $lotItemInputDto->buyNowSelectQuantityEnabled = $row->getCell(Constants\Csv\Lot::BUY_NOW_SELECT_QUANTITY);
        $categories = $row->getCell(Constants\Csv\Lot::LOT_CATEGORY);
        $lotItemInputDto->categoriesNames = $categories ? explode(self::CATEGORY_DELIMITER, $categories) : [];
        $lotItemInputDto->changes = $row->getCell(Constants\Csv\Lot::CHANGES);
        $lotItemInputDto->consignorName = $row->getCell(Constants\Csv\Lot::CONSIGNOR);
        $lotItemInputDto->cost = $row->getCell(Constants\Csv\Lot::COST);
        $description = $row->getCell(Constants\Csv\Lot::LOT_DESCRIPTION);
        $description = $this->shouldReplaceBreaksWithHtml
            ? HtmlRenderer::new()->newlinesToHtmlBreaks($description)
            : $description;
        $lotItemInputDto->description = $description;
        $lotItemInputDto->fbOgDescription = $row->getCell(Constants\Csv\Lot::FB_OG_DESCRIPTION);
        $lotItemInputDto->fbOgImageUrl = $row->getCell(Constants\Csv\Lot::FB_OG_IMAGE_URL);
        $lotItemInputDto->fbOgTitle = $row->getCell(Constants\Csv\Lot::FB_OG_TITLE);
        $lotItemInputDto->highEstimate = $row->getCell(Constants\Csv\Lot::HIGH_ESTIMATE);
        $lotItemInputDto->id = $lotItemId;
        $images = $row->getCell(Constants\Csv\Lot::LOT_IMAGE);
        $lotItemInputDto->images = $images ? explode(self::IMAGE_DELIMITER, $images) : [];
        $lotItemInputDto->increments = $this->createCommissionCsvParser()->construct()->parse($row->getCell(Constants\Csv\Lot::INCREMENT));
        $lotItemInputDto->itemFullNum = $row->getCell(Constants\Csv\Lot::ITEM_FULL_NUMBER);
        $lotItemInputDto->itemNum = $row->getCell(Constants\Csv\Lot::ITEM_NUM);
        $lotItemInputDto->itemNumExt = $row->getCell(Constants\Csv\Lot::ITEM_NUM_EXT);
        $lotItemInputDto->location = $row->getCell(Constants\Csv\Lot::LOCATION);
        $lotItemInputDto->lowEstimate = $row->getCell(Constants\Csv\Lot::LOW_ESTIMATE);
        $lotItemInputDto->name = $row->getCell(Constants\Csv\Lot::LOT_NAME);
        $lotItemInputDto->noTaxOos = $row->getCell(Constants\Csv\Lot::NO_TAX_OUTSIDE_STATE);
        $lotItemInputDto->onlyTaxBp = $row->getCell(Constants\Csv\Lot::ONLY_TAX_BP);
        $lotItemInputDto->replacementPrice = $row->getCell(Constants\Csv\Lot::REPLACEMENT_PRICE);
        $lotItemInputDto->reservePrice = $row->getCell(Constants\Csv\Lot::RESERVE_PRICE);
        $lotItemInputDto->returned = $row->getCell(Constants\Csv\Lot::RETURNED);
        $lotItemInputDto->salesTax = $row->getCell(Constants\Csv\Lot::SALES_TAX);
        $lotItemInputDto->seoMetaDescription = $row->getCell(Constants\Csv\Lot::SEO_META_DESCRIPTION);
        $lotItemInputDto->seoMetaKeywords = $row->getCell(Constants\Csv\Lot::SEO_META_KEYWORDS);
        $lotItemInputDto->seoMetaTitle = $row->getCell(Constants\Csv\Lot::SEO_META_TITLE);
        $lotItemInputDto->setArray($this->createCustomFieldCsvHelper()->parseCustomFields($row, $this->lotCustomFields));
        $lotItemInputDto->startingBid = $row->getCell(Constants\Csv\Lot::STARTING_BID);
        $lotItemInputDto->taxDefaultCountry = $this->parseTaxCountry($row->getCell(Constants\Csv\Lot::ITEM_TAX_COUNTRY));
        $tagStates = $row->getCell(Constants\Csv\Lot::ITEM_TAX_STATES);
        $lotItemInputDto->taxStates = $tagStates ? explode(self::TAX_STATE_DELIMITER, $tagStates) : [];
        $lotItemInputDto->warranty = $row->getCell(Constants\Csv\Lot::WARRANTY);
        $specificLocation = [
            'Address' => $row->getCell(Constants\Csv\Lot::LOCATION_ADDRESS),
            'City' => $row->getCell(Constants\Csv\Lot::LOCATION_CITY),
            'Country' => $row->getCell(Constants\Csv\Lot::LOCATION_COUNTRY),
            'County' => $row->getCell(Constants\Csv\Lot::LOCATION_COUNTY),
            'Logo' => $row->getCell(Constants\Csv\Lot::LOCATION_LOGO),
            'State' => $row->getCell(Constants\Csv\Lot::LOCATION_STATE),
            'Zip' => $row->getCell(Constants\Csv\Lot::LOCATION_ZIP),
        ];
        if (array_filter($specificLocation)) {
            $lotItemInputDto->specificLocation = (object)$specificLocation;
        }
        if (
            !$this->auctionId
            || $lotItemId === null
        ) {
            $lotItemInputDto->quantity = $row->getCell(Constants\Csv\Lot::QUANTITY);
            $lotItemInputDto->quantityDigits = $row->getCell(Constants\Csv\Lot::QUANTITY_DIGITS);
            $lotItemInputDto->quantityXMoney = $row->getCell(Constants\Csv\Lot::QUANTITY_X_MONEY);
            $rangeCsvTransformer = $this->createConsignorCommissionRangeCsvTransformer();
            $lotItemInputDto->consignorCommissionCalculationMethod = $row->getCell(Constants\Csv\Lot::CONSIGNOR_COMMISSION_CALCULATION_METHOD);
            $lotItemInputDto->consignorCommissionId = $row->getCell(Constants\Csv\Lot::CONSIGNOR_COMMISSION_ID);
            $lotItemInputDto->consignorCommissionRanges = $rangeCsvTransformer->transformCsvStringToDtos(
                $row->getCell(Constants\Csv\Lot::CONSIGNOR_COMMISSION_RANGES)
            );
            $lotItemInputDto->consignorSoldFeeCalculationMethod = $row->getCell(Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD);
            $lotItemInputDto->consignorSoldFeeId = $row->getCell(Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_ID);
            $lotItemInputDto->consignorSoldFeeRanges = $rangeCsvTransformer->transformCsvStringToDtos(
                $row->getCell(Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_RANGES)
            );
            $lotItemInputDto->consignorSoldFeeReference = $row->getCell(Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_REFERENCE);
            $lotItemInputDto->consignorUnsoldFeeCalculationMethod = $row->getCell(Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD);
            $lotItemInputDto->consignorUnsoldFeeId = $row->getCell(Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_ID);
            $lotItemInputDto->consignorUnsoldFeeRanges = $rangeCsvTransformer->transformCsvStringToDtos(
                $row->getCell(Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_RANGES)
            );
            $lotItemInputDto->consignorUnsoldFeeReference = $row->getCell(Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_REFERENCE);

            $lotItemInputDto->hpTaxSchemaId = $row->getCell(Constants\Csv\Lot::HP_TAX_SCHEMA_ID);
            $lotItemInputDto->bpTaxSchemaId = $row->getCell(Constants\Csv\Lot::BP_TAX_SCHEMA_ID);
        }
        return $lotItemInputDto;
    }

    /**
     * @param LotItemMakerConfigDto $lotItemConfigDto
     * @param CsvRow $row
     * @return LotItemMakerConfigDto
     */
    protected function fillLotItemConfigDto(LotItemMakerConfigDto $lotItemConfigDto, CsvRow $row): LotItemMakerConfigDto
    {
        $lotItemConfigDto->allCustomFields = $this->lotCustomFields;
        $lotItemConfigDto->auctionId = $this->auctionId;
        $lotItemConfigDto->auctionType = $this->getAuctionLoader()->load($this->auctionId)->AuctionType ?? '';
        $lotItemConfigDto->clearValues = $this->clearEmptyFields;
        $lotItemConfigDto->encoding = $this->encoding;
        $lotItemConfigDto->presentedCsvColumns = $this->createImportCsvColumnsHelper()
            ->detectPresentedCsvColumns($row, $this->lotCustomFields, $this->clearEmptyFields);
        return $lotItemConfigDto;
    }

    /**
     * Check, if BpSetting value is Short Name of pre-defined custom buyer's premium ranges.
     * @param string $bpSetting
     * @return bool
     */
    protected function isBpShortName(string $bpSetting): bool
    {
        return strlen($bpSetting) === 1;
    }

    /**
     * Parse 'itemTaxCountry' column and return country code
     * @param string $taxCountry 'itemTaxCountry' column data
     * @return string $taxCountry Country code
     */
    protected function parseTaxCountry(string $taxCountry): string
    {
        return AddressRenderer::new()->normalizeCountry($taxCountry) ?: $taxCountry;
    }
}
