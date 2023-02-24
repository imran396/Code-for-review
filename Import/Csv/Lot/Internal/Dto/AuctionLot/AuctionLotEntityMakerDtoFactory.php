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

namespace Sam\Import\Csv\Lot\Internal\Dto\AuctionLot;

use Auction;
use DateTimeZone;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Consignor\Commission\Csv\ConsignorCommissionRangeCsvTransformerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerConfigDto;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerDtoFactory;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\Base\Dto\ValidationStatus;
use Sam\Import\Csv\Base\ImportCsvColumnsHelperCreateTrait;
use Sam\Import\Csv\Read\CsvRow;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class AuctionLotEntityMakerDtoFactory
 * @package Sam\Import\Csv\Lot\Internal\Dto
 */
class AuctionLotEntityMakerDtoFactory extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use ConsignorCommissionRangeCsvTransformerCreateTrait;
    use DateHelperAwareTrait;
    use ImportCsvColumnsHelperCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use NumberFormatterAwareTrait;
    use TimezoneLoaderAwareTrait;

    protected const CATEGORY_DELIMITER = ';';

    protected int $editorUserId;
    protected int $lotAccountId;
    protected int $systemAccountId;
    protected array $customFields;
    protected string $encoding;
    protected bool $clearEmptyFields;
    protected Auction $auction;

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
     * @param int $editorUserId
     * @param int $lotAccountId
     * @param int $systemAccountId
     * @param array $customFields
     * @param string $encoding
     * @param bool $clearEmptyFields
     * @return static
     */
    public function construct(
        Auction $auction,
        int $editorUserId,
        int $lotAccountId,
        int $systemAccountId,
        array $customFields,
        string $encoding,
        bool $clearEmptyFields
    ): static {
        $this->editorUserId = $editorUserId;
        $this->lotAccountId = $lotAccountId;
        $this->systemAccountId = $systemAccountId;
        $this->customFields = $customFields;
        $this->encoding = $encoding;
        $this->clearEmptyFields = $clearEmptyFields;
        $this->auction = $auction;
        return $this;
    }

    /**
     * Construct and fill AuctionLotMakerInputDto and AuctionLotMakerConfigDto with data from a row in CSV file
     *
     * @param CsvRow $row
     * @param int|null $lotItemId
     * @param ValidationStatus $validationStatus
     * @return array
     */
    public function create(CsvRow $row, ?int $lotItemId, ValidationStatus $validationStatus = ValidationStatus::NONE): array
    {
        $auctionLotId = $this->detectAuctionLotId($lotItemId, $this->auction->Id);

        /**
         * @var AuctionLotMakerInputDto $auctionLotInputDto
         * @var AuctionLotMakerConfigDto $auctionLotConfigDto
         */
        [$auctionLotInputDto, $auctionLotConfigDto] = AuctionLotMakerDtoFactory::new()->createDtos(
            Mode::CSV,
            $this->editorUserId,
            $this->lotAccountId,
            $this->systemAccountId
        );

        $auctionLotInputDto->auctionId = $this->auction->Id;
        $auctionLotInputDto->bestOffer = $row->getCell(Constants\Csv\Lot::BEST_OFFER);
        $auctionLotInputDto->bulkControl = $row->getCell(Constants\Csv\Lot::BULK_CONTROL);
        $bulkWinBidDistribution = $row->getCell(Constants\Csv\Lot::BULK_WIN_BID_DISTRIBUTION);
        $auctionLotInputDto->bulkWinBidDistribution = $bulkWinBidDistribution;
        $auctionLotInputDto->buyNowAmount = $row->getCell(Constants\Csv\Lot::BUY_NOW_AMOUNT);
        $auctionLotInputDto->buyNowSelectQuantityEnabled = $row->getCell(Constants\Csv\Lot::BUY_NOW_SELECT_QUANTITY);
        $categories = $row->getCell(Constants\Csv\Lot::LOT_CATEGORY);
        $auctionLotInputDto->categoriesNames = $categories ? explode(self::CATEGORY_DELIMITER, $categories) : [];

        $auctionLotInputDto->featured = $row->getCell(Constants\Csv\Lot::FEATURED);
        $auctionLotInputDto->generalNote = $row->getCell(Constants\Csv\Lot::GENERAL_NOTE);
        $auctionLotInputDto->id = $auctionLotId;
        $auctionLotInputDto->listingOnly = $row->getCell(Constants\Csv\Lot::LISTING_ONLY);
        $auctionLotInputDto->lotFullNum = $row->getCell(Constants\Csv\Lot::LOT_FULL_NUMBER);
        $auctionLotInputDto->lotGroup = $row->getCell(Constants\Csv\Lot::GROUP_ID);
        $auctionLotInputDto->lotItemId = $lotItemId;
        $auctionLotInputDto->lotNum = $row->getCell(Constants\Csv\Lot::LOT_NUM);
        $auctionLotInputDto->lotNumExt = $row->getCell(Constants\Csv\Lot::LOT_NUM_EXT);
        $auctionLotInputDto->lotNumPrefix = $row->getCell(Constants\Csv\Lot::LOT_NUM_PREFIX);
        $auctionLotInputDto->noBidding = $row->getCell(Constants\Csv\Lot::NO_BIDDING);
        $auctionLotInputDto->noteToClerk = $row->getCell(Constants\Csv\Lot::NOTE_TO_CLERK);

        $auctionLotInputDto->seoUrl = $row->getCell(Constants\Csv\Lot::SEO_URL);
        $auctionLotInputDto->termsAndConditions = $row->getCell(Constants\Csv\Lot::TERMS_AND_CONDITIONS);

        $auctionLotInputDto->hpTaxSchemaId = $row->getCell(Constants\Csv\Lot::HP_TAX_SCHEMA_ID);
        $auctionLotInputDto->bpTaxSchemaId = $row->getCell(Constants\Csv\Lot::BP_TAX_SCHEMA_ID);

        $auctionLotInputDto = $this->fillDates($auctionLotInputDto, $row);
        $auctionLotInputDto = $this->fillQuantity($auctionLotInputDto, $row, $lotItemId);
        $auctionLotInputDto = $this->fillConsignorCommissionFee($auctionLotInputDto, $row);

        $auctionLotConfigDto = $this->fillConfigDto($auctionLotConfigDto, $row);
        $auctionLotConfigDto->setValidationStatus($validationStatus);

        return [$auctionLotInputDto, $auctionLotConfigDto];
    }

    /**
     * @param AuctionLotMakerInputDto $auctionLotInputDto
     * @param CsvRow $row
     * @return AuctionLotMakerInputDto
     */
    protected function fillDates(AuctionLotMakerInputDto $auctionLotInputDto, CsvRow $row): AuctionLotMakerInputDto
    {
        $auctionTimezoneId = $this->auction->TimezoneId ?? null;
        $auctionTimezoneLocation = $this->getTimezoneLoader()->load($auctionTimezoneId)->Location ?? 'UTC';

        $endPreBiddingDateCsvFormatted = $row->getCell(Constants\Csv\Lot::END_PREBIDDING_DATE);
        $endPreBiddingDateIso = $this->convertCsvFormattedDateToIso($endPreBiddingDateCsvFormatted, $auctionTimezoneLocation);
        $auctionLotInputDto->endPrebiddingDate = $endPreBiddingDateIso;

        $publishDateCsvFormatted = $row->getCell(Constants\Csv\Lot::PUBLISH_DATE);
        $publishDateIso = $this->convertCsvFormattedDateToIso($publishDateCsvFormatted, $auctionTimezoneLocation);
        $auctionLotInputDto->publishDate = $publishDateIso;

        $startBiddingDateCsvFormatted = $row->getCell(Constants\Csv\Lot::START_BIDDING_DATE);
        $startBiddingDateIso = $this->convertCsvFormattedDateToIso($startBiddingDateCsvFormatted, $auctionTimezoneLocation);
        $auctionLotInputDto->startBiddingDate = $startBiddingDateIso;

        $auctionStartClosingDate = $this->getDateHelper()->convertUtcToTzById($this->auction->StartClosingDate, $this->auction->TimezoneId);
        $auctionStartClosingDateIso = $auctionStartClosingDate?->format(Constants\Date::ISO);
        $startClosingDateCsvFormatted = $row->getCell(Constants\Csv\Lot::START_CLOSING_DATE);
        $startClosingDateIso = $this->convertCsvFormattedDateToIso($startClosingDateCsvFormatted, $auctionTimezoneLocation) ?: $auctionStartClosingDateIso;
        $auctionLotInputDto->startClosingDate = $startClosingDateIso;

        $unpublishDateCsvFormatted = $row->getCell(Constants\Csv\Lot::UNPUBLISH_DATE);
        $unpublishDateIso = $this->convertCsvFormattedDateToIso($unpublishDateCsvFormatted, $auctionTimezoneLocation);
        $auctionLotInputDto->unpublishDate = $unpublishDateIso;

        return $auctionLotInputDto;
    }

    /**
     * @param AuctionLotMakerInputDto $auctionLotInputDto
     * @param CsvRow $row
     * @param int|null $lotItemId
     * @return AuctionLotMakerInputDto
     */
    protected function fillQuantity(AuctionLotMakerInputDto $auctionLotInputDto, CsvRow $row, ?int $lotItemId): AuctionLotMakerInputDto
    {
        $quantity = $row->getCell(Constants\Csv\Lot::QUANTITY);
        $quantityDigits = $row->getCell(Constants\Csv\Lot::QUANTITY_DIGITS);
        $quantityXMoney = $row->getCell(Constants\Csv\Lot::QUANTITY_X_MONEY);

        $lotItem = $this->getLotItemLoader()->load($lotItemId);
        if (
            $quantity === ''
            && $lotItem
        ) {
            if ($quantityDigits !== '') {
                $scale = (int)$quantityDigits > 0 ? (int)$quantityDigits : 0;
            } else {
                $scale = $this->createLotQuantityScaleLoader()->loadLotItemQuantityScale($lotItemId);
            }
            $quantity = $lotItem->Quantity
                ? $this->getNumberFormatter()->formatNto($lotItem->Quantity, $scale, $this->lotAccountId)
                : '';
            $quantityXMoney = $lotItem->QuantityXMoney;
        }

        $auctionLotInputDto->quantity = $quantity;
        $auctionLotInputDto->quantityDigits = $quantityDigits;
        $auctionLotInputDto->quantityXMoney = $quantityXMoney;
        return $auctionLotInputDto;
    }

    /**
     * @param AuctionLotMakerInputDto $auctionLotInputDto
     * @param CsvRow $row
     * @return AuctionLotMakerInputDto
     */
    protected function fillConsignorCommissionFee(AuctionLotMakerInputDto $auctionLotInputDto, CsvRow $row): AuctionLotMakerInputDto
    {
        $rangeCsvTransformer = $this->createConsignorCommissionRangeCsvTransformer();
        $auctionLotInputDto->consignorCommissionCalculationMethod = $row->getCell(Constants\Csv\Lot::CONSIGNOR_COMMISSION_CALCULATION_METHOD);
        $auctionLotInputDto->consignorCommissionId = $row->getCell(Constants\Csv\Lot::CONSIGNOR_COMMISSION_ID);
        $auctionLotInputDto->consignorCommissionRanges = $rangeCsvTransformer->transformCsvStringToDtos(
            $row->getCell(Constants\Csv\Lot::CONSIGNOR_COMMISSION_RANGES)
        );
        $auctionLotInputDto->consignorSoldFeeCalculationMethod = $row->getCell(Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD);
        $auctionLotInputDto->consignorSoldFeeId = $row->getCell(Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_ID);
        $auctionLotInputDto->consignorSoldFeeRanges = $rangeCsvTransformer->transformCsvStringToDtos(
            $row->getCell(Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_RANGES)
        );
        $auctionLotInputDto->consignorSoldFeeReference = $row->getCell(Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_REFERENCE);
        $auctionLotInputDto->consignorUnsoldFeeCalculationMethod = $row->getCell(Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD);
        $auctionLotInputDto->consignorUnsoldFeeId = $row->getCell(Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_ID);
        $auctionLotInputDto->consignorUnsoldFeeRanges = $rangeCsvTransformer->transformCsvStringToDtos(
            $row->getCell(Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_RANGES)
        );
        $auctionLotInputDto->consignorUnsoldFeeReference = $row->getCell(Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_REFERENCE);
        return $auctionLotInputDto;
    }

    /**
     * @param AuctionLotMakerConfigDto $auctionLotConfigDto
     * @param CsvRow $row
     * @return AuctionLotMakerConfigDto
     */
    protected function fillConfigDto(AuctionLotMakerConfigDto $auctionLotConfigDto, CsvRow $row): AuctionLotMakerConfigDto
    {
        $auctionLotConfigDto->auctionType = $this->auction->AuctionType;
        $auctionLotConfigDto->clearValues = $this->clearEmptyFields;
        $auctionLotConfigDto->encoding = $this->encoding;
        $auctionLotConfigDto->presentedCsvColumns = $this->createImportCsvColumnsHelper()->detectPresentedCsvColumns($row, $this->customFields, $this->clearEmptyFields);
        $auctionLotConfigDto->reverse = $this->auction->Reverse ?? false;
        return $auctionLotConfigDto;
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @return int|null
     */
    protected function detectAuctionLotId(?int $lotItemId, ?int $auctionId): ?int
    {
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId, true);
        $auctionLotId = $auctionLot->Id ?? null;
        return $auctionLotId;
    }

    /**
     * @param string|null $csvFormattedDate
     * @param string|null $timezoneLocation
     * @return string|null
     */
    protected function convertCsvFormattedDateToIso(?string $csvFormattedDate, string $timezoneLocation = null): ?string
    {
        $date = $this->getDateHelper()->convertCsvFormattedToDate($csvFormattedDate);
        if (!$date) {
            return null;
        }
        if ($timezoneLocation) {
            $date = $date->setTimezone(new DateTimeZone($timezoneLocation));
        }
        return $date->format(Constants\Date::ISO);
    }
}
