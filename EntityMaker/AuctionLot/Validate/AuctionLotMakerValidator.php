<?php
/**
 * Class for validating of Auction Lot input data
 *
 * SAM-4015: Auction Lot and Lot Item Entity Makers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 17, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use Sam\Auction\Validate\AuctionExistenceCheckerAwareTrait;
use Sam\AuctionLot\BulkGroup\Validate\LotBulkGroupExistenceCheckerAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Validate\AuctionLotDataIntegrityCheckerAwareTrait;
use Sam\AuctionLot\Validate\AuctionLotExistenceCheckerAwareTrait;
use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidExistenceCheckerAwareTrait;
use Sam\Consignor\Commission\Convert\ConsignorCommissionFeeRangeDtoConverterCreateTrait;
use Sam\Consignor\Commission\Edit\Validate\ConsignorCommissionFeeRangesValidatorCreateTrait;
use Sam\Consignor\Commission\Edit\Validate\ConsignorCommissionFeeValidatorCreateTrait;
use Sam\Consignor\Commission\Edit\Validate\RangeValidationResultStatus;
use Sam\Consignor\Commission\Validate\ConsignorCommissionFeeExistenceCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Platform\Constant\Base\ConstantNameResolver;
use Sam\EntityMaker\AuctionLot\Common\Access\AuctionLotMakerAccessCheckerAwareTrait;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerConfigDto;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerDtoHelperAwareTrait;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;
use Sam\EntityMaker\AuctionLot\Internal\Validate\BulkGroup\LotBulkGroupToBuyNowValidatorCreateTrait;
use Sam\EntityMaker\AuctionLot\Validate\Constants\ResultCode;
use Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\LotNoValidationIntegratorCreateTrait;
use Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\QuantityValidationIntegratorCreateTrait;
use Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Scale\QuantityScaleDetectorCreateTrait;
use Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Scale\QuantityScaleDetectorInput;
use Sam\EntityMaker\AuctionLot\Validate\Internal\TaxSchema\AuctionLotTaxSchemaValidationIntegratorCreateTrait;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\Base\Validate\BaseMakerValidator;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\Map\EntityMakerFieldMap;
use Sam\Lot\Move\BlockSoldLotsCheckerCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * The following methods are handled by \Sam\EntityMaker\Base\Validator::__call() method:
 * GetErrorMessage Methods
 * @method getBuyNowAmountErrorMessage()
 * @method getBuyNowSelectQuantityEnabledErrorMessage()
 * @method getEndPrebiddingDateErrorMessage()
 * @method getGeneralNoteErrorMessage()
 * @method getLotFullNumErrorMessage()
 * @method getLotGroupErrorMessage()
 * @method getLotNumErrorMessage()
 * @method getLotNumExtErrorMessage()
 * @method getLotNumPrefixErrorMessage()
 * @method getLotStatusIdErrorMessage()
 * @method getNoteToClerkErrorMessage()
 * @method getPublishDateErrorMessage()
 * @method getQuantityErrorMessage()
 * @method getQuantityDigitsErrorMessage()
 * @method getStartBiddingDateErrorMessage()
 * @method getStartClosingDateErrorMessage()
 * @method getTimezoneErrorMessage()
 * @method getUnpublishDateErrorMessage()
 * @method getConsignorCommissionIdErrorMessage()
 * @method getConsignorCommissionCalculationMethodErrorMessage()
 * @method getConsignorCommissionRangeErrorMessage()
 * @method getConsignorSoldFeeIdErrorMessage()
 * @method getConsignorSoldFeeCalculationMethodErrorMessage()
 * @method getConsignorSoldFeeRangeErrorMessage()
 * @method getConsignorUnsoldFeeIdErrorMessage()
 * @method getConsignorUnsoldFeeCalculationMethodErrorMessage()
 * @method getConsignorUnsoldFeeRangeErrorMessage()
 * @method getSeoUrlErrorMessage()
 * @method getTermsAndConditionsErrorMessage()
 * @method getBulkControlErrorMessage()
 * @method getHpTaxSchemaIdErrorMessage()
 * @method getBpTaxSchemaIdErrorMessage()
 * HasError Methods
 * @method hasBuyNowAmountError()
 * @method hasBuyNowSelectQuantityEnabledError()
 * @method hasEndPrebiddingDateError()
 * @method hasGeneralNoteError()
 * @method hasLotFullNumError()
 * @method hasLotGroupError()
 * @method hasLotNumError()
 * @method hasLotNumExtError()
 * @method hasLotNumPrefixError()
 * @method hasLotStatusIdError()
 * @method hasNoteToClerkError()
 * @method hasPublishDateError()
 * @method hasQuantityError()
 * @method hasQuantityDigitsError()
 * @method hasStartBiddingDateError()
 * @method hasStartClosingDateError()
 * @method hasTimezoneError()
 * @method hasUnpublishDateError()
 * @method hasConsignorCommissionIdError()
 * @method hasConsignorCommissionCalculationMethodError()
 * @method hasConsignorCommissionRangeError()
 * @method hasConsignorSoldFeeIdError()
 * @method hasConsignorSoldFeeCalculationMethodError()
 * @method hasConsignorSoldFeeRangeError()
 * @method hasConsignorSoldFeeReferenceError()
 * @method hasConsignorUnsoldFeeIdError()
 * @method hasConsignorUnsoldFeeCalculationMethodError()
 * @method hasConsignorUnsoldFeeRangeError()
 * @method hasConsignorUnsoldFeeReferenceError()
 * @method hasSeoUrlError()
 * @method hasTermsAndConditionsError()
 * @method hasBulkControlError()
 * @method hasHpTaxSchemaIdError()
 * @method hasBpTaxSchemaIdError()
 *
 * @method AuctionLotMakerInputDto getInputDto()
 * @method AuctionLotMakerConfigDto getConfigDto()
 */
class AuctionLotMakerValidator extends BaseMakerValidator
{
    use AbsenteeBidExistenceCheckerAwareTrait;
    use AuctionExistenceCheckerAwareTrait;
    use AuctionLotDataIntegrityCheckerAwareTrait;
    use AuctionLotExistenceCheckerAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionLotMakerAccessCheckerAwareTrait;
    use AuctionLotMakerDtoHelperAwareTrait;
    use AuctionLotTaxSchemaValidationIntegratorCreateTrait;
    use BlockSoldLotsCheckerCreateTrait;
    use ConsignorCommissionFeeExistenceCheckerCreateTrait;
    use ConsignorCommissionFeeRangeDtoConverterCreateTrait;
    use ConsignorCommissionFeeRangesValidatorCreateTrait;
    use ConsignorCommissionFeeValidatorCreateTrait;
    use LotBulkGroupExistenceCheckerAwareTrait;
    use LotBulkGroupToBuyNowValidatorCreateTrait;
    use LotFieldConfigProviderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotNoValidationIntegratorCreateTrait;
    use QuantityScaleDetectorCreateTrait;
    use QuantityValidationIntegratorCreateTrait;
    use SettingsManagerAwareTrait;

    /** @var string[] */
    protected array $tagNames = [
        ResultCode::ACCESS_DENIED => '',
        ResultCode::AUCTION_ID_NOT_SPECIFIED => 'Auction',
        ResultCode::AUCTION_LOT_EXIST => 'AuctionLot',
        ResultCode::AUCTION_NOT_FOUND => 'Auction',
        ResultCode::BULK_CONTROL_NOT_FOUND => 'BulkControl',
        ResultCode::BULK_GROUP_EXCLUDE_BUY_NOW => 'BulkControl',
        ResultCode::BULK_MASTER_LOT_START_BIDDING_DATE_NOT_EDITABLE => 'StartBiddingDate',
        ResultCode::BULK_MASTER_LOT_START_CLOSING_DATE_NOT_EDITABLE => 'StartClosingDate',
        ResultCode::BULK_MASTER_WIN_BID_DISTRIBUTION_UNKNOWN => 'BulkWinBidDistribution',
        ResultCode::BUY_NOW_AMOUNT_INVALID => 'BuyNowAmount',
        ResultCode::BUY_NOW_AMOUNT_REQUIRED => 'BuyNowAmount',
        ResultCode::BUY_NOW_AMOUNT_SUPPORTED_IN_REVERSE_AUCTION => 'BuyNowAmount',
        ResultCode::BUY_NOW_EXCLUDE_BULK_GROUP => 'BuyNowAmount',
        ResultCode::BUY_NOW_SELECT_QUANTITY_WITH_QUANTITY_DIGITS_NOT_ALLOWED => 'BuyNowSelectQuantity',
        ResultCode::BUY_NOW_SELECT_QUANTITY_FOR_LIVE_OR_HYBRID_AUCTION_NOT_ALLOWED => 'BuyNowSelectQuantity',
        ResultCode::BUY_NOW_SELECT_QUANTITY_FOR_REVERSE_AUCTION_NOT_ALLOWED => 'BuyNowSelectQuantity',
        ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID => 'ConsignorCommissionCalculationMethod',
        ResultCode::CONSIGNOR_COMMISSION_ID_INVALID => 'ConsignorCommissionId',
        ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID => 'ConsignorCommissionRanges',
        ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID => 'ConsignorSoldFeeCalculationMethod',
        ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID => 'ConsignorSoldFeeId',
        ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID => 'ConsignorSoldFeeRanges',
        ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID => 'ConsignorSoldFeeCReference',
        ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID => 'ConsignorUnsoldFeeCalculationMethod',
        ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID => 'ConsignorUnsoldFeeId',
        ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID => 'ConsignorUnsoldFeeRanges',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID => 'ConsignorUnsoldFeeCReference',
        ResultCode::END_PREBIDDING_DATE_INVALID => 'EndPrebiddingDate',
        ResultCode::GENERAL_NOTE_INVALID_ENCODING => 'GeneralNote',
        ResultCode::GENERAL_NOTE_REQUIRED => 'GeneralNote',
        ResultCode::LOT_ALREADY_MARKED_SOLD => 'Lot',
        ResultCode::LOT_FULL_NUM_PARSE_ERROR => 'LotFullNum',
        ResultCode::LOT_GROUP_INVALID => 'LotGroup',
        ResultCode::LOT_GROUP_REQUIRED => 'LotGroup',
        ResultCode::LOT_ITEM_ID_NOT_SPECIFIED => 'LotItem',
        ResultCode::LOT_ITEM_NOT_FOUND => 'LotItem',
        ResultCode::LOT_NUM_EXIST => 'LotNum',
        ResultCode::LOT_NUM_EXT_INVALID => 'LotNumExt',
        ResultCode::LOT_NUM_EXT_INVALID_LENGTH => 'LotNumExt',
        ResultCode::LOT_NUM_HIGHER_MAX_AVAILABLE_VALUE => 'LotNum',
        ResultCode::LOT_NUM_INVALID => 'LotNum',
        ResultCode::LOT_NUM_PREFIX_INVALID => 'LotNumPrefix',
        ResultCode::LOT_NUM_REQUIRED => 'LotNum',
        ResultCode::LOT_STATUS_ID_ALREADY_ACTIVE => 'LotStatus',
        ResultCode::LOT_STATUS_ID_UNKNOWN => 'LotStatus',
        ResultCode::LOT_STATUS_UNKNOWN => 'LotStatus',
        ResultCode::NOTE_TO_CLERK_INVALID_ENCODING => 'NoteToClerk',
        ResultCode::NOTE_TO_CLERK_REQUIRED => 'NoteToClerk',
        ResultCode::ORDER_INVALID => 'Order',
        ResultCode::PUBLISH_DATE_INVALID => 'PublishDate',
        ResultCode::QUANTITY_INVALID => 'Quantity',
        ResultCode::QUANTITY_REQUIRED => 'Quantity',
        ResultCode::QUANTITY_DIGITS_INVALID => 'QuantityDigits',
        ResultCode::QUANTITY_DIGITS_REQUIRED => 'QuantityDigits',
        ResultCode::SEO_URL_INVALID_ENCODING => 'SeoUrl',
        ResultCode::START_BIDDING_DATE_INVALID => 'StartBiddingDate',
        ResultCode::START_BIDDING_DATE_LATER_START_CLOSING_DATE => 'StartClosingDate',
        ResultCode::START_CLOSING_DATE_INVALID => 'StartClosingDate',
        ResultCode::START_CLOSING_DATE_REQUIRED => 'StartClosingDate',
        ResultCode::SYNC_KEY_EXIST => 'SyncKey',
        ResultCode::TIMEZONE_UNKNOWN => 'Timezone',
        ResultCode::UNPUBLISH_DATE_INVALID => 'UnpublishDate',
        ResultCode::LOT_STATUS_REQUIRED => 'LotStatus',
        ResultCode::LOT_STATUS_ID_REQUIRED => 'LotStatus',
        ResultCode::TIMEZONE_REQUIRED => 'Timezone',
        ResultCode::PUBLISH_DATE_REQUIRED => 'PublishDate',
        ResultCode::UNPUBLISH_DATE_REQUIRED => 'UnpublishDate',
        ResultCode::START_BIDDING_DATE_REQUIRED => 'StartBiddingDate',
        ResultCode::END_PREBIDDING_DATE_REQUIRED => 'EndPrebiddingDate',
        ResultCode::SEO_URL_REQUIRED => 'SeoUrl',
        ResultCode::TERMS_AND_CONDITIONS_REQUIRED => 'TermsAnsConditions',
        ResultCode::BULK_GROUP_REQUIRED => 'BulkGroup',
        ResultCode::LOT_STATUS_HIDDEN => 'LotStatus',
        ResultCode::LOT_STATUS_ID_HIDDEN => 'LotStatus',
        ResultCode::TIMEZONE_HIDDEN => 'Timezone',
        ResultCode::PUBLISH_DATE_HIDDEN => 'PublishDate',
        ResultCode::UNPUBLISH_DATE_HIDDEN => 'UnpublishDate',
        ResultCode::START_BIDDING_DATE_HIDDEN => 'StartBiddingDate',
        ResultCode::END_PREBIDDING_DATE_HIDDEN => 'EndPrebiddingDate',
        ResultCode::START_CLOSING_DATE_HIDDEN => 'StartClosingDate',
        ResultCode::LOT_NUMBER_HIDDEN => 'LotNum',
        ResultCode::LOT_NUMBER_EXT_HIDDEN => 'LotNumExt',
        ResultCode::LOT_NUMBER_PREFIX_HIDDEN => 'LotNumPrefix',
        ResultCode::LOT_FULL_NUMBER_HIDDEN => 'LotFullNum',
        ResultCode::SEO_URL_HIDDEN => 'SeoUrl',
        ResultCode::LOT_GROUP_HIDDEN => 'LotGroup',
        ResultCode::QUANTITY_HIDDEN => 'Quantity',
        ResultCode::QUANTITY_DIGITS_HIDDEN => 'QuantityDigits',
        ResultCode::QUANTITY_X_MONEY_HIDDEN => 'QuantityXMoney',
        ResultCode::TERMS_AND_CONDITIONS_HIDDEN => 'TermsAndConditions',
        ResultCode::LISTING_ONLY_HIDDEN => 'ListingOnly',
        ResultCode::BUY_NOW_AMOUNT_HIDDEN => 'BuyNowAmount',
        ResultCode::BUY_NOW_SELECT_QUANTITY_HIDDEN => 'BuyNowSelectQuantity',
        ResultCode::FEATURED_HIDDEN => 'Featured',
        ResultCode::CLERK_NOTE_HIDDEN => 'NoteToClerk',
        ResultCode::GENERAL_NOTE_HIDDEN => 'GeneralNote',
        ResultCode::BULK_GROUP_CONTROL_HIDDEN => 'BulkGroup',
        ResultCode::BULK_WIN_BID_DISTRIBUTION_HIDDEN => 'BulkWinBidDistribution',
        ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_HIDDEN => 'ConsignorCommissionCalculationMethod',
        ResultCode::CONSIGNOR_COMMISSION_ID_HIDDEN => 'ConsignorCommissionId',
        ResultCode::CONSIGNOR_COMMISSION_RANGES_HIDDEN => 'ConsignorCommissionRanges',
        ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_HIDDEN => 'ConsignorSoldFeeCalculationMethod',
        ResultCode::CONSIGNOR_SOLD_FEE_ID_HIDDEN => 'ConsignorSoldFeeId',
        ResultCode::CONSIGNOR_SOLD_FEE_RANGES_HIDDEN => 'ConsignorSoldFeeRanges',
        ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_HIDDEN => 'ConsignorSoldFeeReference',
        ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_HIDDEN => 'ConsignorUnsoldFeeCalculationMethod',
        ResultCode::CONSIGNOR_UNSOLD_FEE_ID_HIDDEN => 'ConsignorUnsoldFeeId',
        ResultCode::CONSIGNOR_UNSOLD_FEE_RANGES_HIDDEN => 'ConsignorUnsoldFeeRanges',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_HIDDEN => 'ConsignorUnsoldFeeReference',
        ResultCode::CONSIGNOR_COMMISSION_REQUIRED => 'ConsignorCommissionId',
        ResultCode::CONSIGNOR_SOLD_FEE_REQUIRED => 'ConsignorSoldFeeId',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REQUIRED => 'ConsignorUnsoldFeeId',
        ResultCode::BEST_OFFER_HIDDEN => 'BestOffer',
        ResultCode::NO_BIDDING_HIDDEN => 'NoBidding',
        ResultCode::HP_TAX_SCHEMA_ID_INVALID => 'HpTaxSchemaId',
        ResultCode::HP_TAX_SCHEMA_ID_REQUIRED => 'HpTaxSchemaId',
        ResultCode::HP_TAX_SCHEMA_ID_HIDDEN => 'HpTaxSchemaId',
        ResultCode::HP_TAX_SCHEMA_COUNTRY_MISMATCH => 'HpTaxSchemaId',
        ResultCode::BP_TAX_SCHEMA_ID_INVALID => 'BpTaxSchemaId',
        ResultCode::BP_TAX_SCHEMA_ID_REQUIRED => 'BpTaxSchemaId',
        ResultCode::BP_TAX_SCHEMA_ID_HIDDEN => 'BpTaxSchemaId',
        ResultCode::BP_TAX_SCHEMA_COUNTRY_MISMATCH => 'BpTaxSchemaId',
    ];

    protected function initColumnNames(): void
    {
        $columnHeaders = AuctionStatusPureChecker::new()->isTimed($this->getConfigDto()->auctionType)
            ? $this->cfg()->get('csv->admin->timed')
            : $this->cfg()->get('csv->admin->live');
        $this->columnNames = [
            ResultCode::ACCESS_DENIED => '',
            ResultCode::AUCTION_ID_NOT_SPECIFIED => '',
            ResultCode::AUCTION_LOT_EXIST => $columnHeaders->{Constants\Csv\Lot::LOT_NUM},
            ResultCode::AUCTION_NOT_FOUND => '',
            ResultCode::BULK_CONTROL_NOT_FOUND => $columnHeaders->{Constants\Csv\Lot::BULK_CONTROL},
            ResultCode::BULK_GROUP_EXCLUDE_BUY_NOW => $columnHeaders->{Constants\Csv\Lot::BULK_CONTROL},
            ResultCode::BULK_MASTER_LOT_START_BIDDING_DATE_NOT_EDITABLE => $columnHeaders->{Constants\Csv\Lot::START_BIDDING_DATE},
            ResultCode::BULK_MASTER_LOT_START_CLOSING_DATE_NOT_EDITABLE => $columnHeaders->{Constants\Csv\Lot::START_CLOSING_DATE},
            ResultCode::BULK_MASTER_WIN_BID_DISTRIBUTION_UNKNOWN => $columnHeaders->{Constants\Csv\Lot::BULK_WIN_BID_DISTRIBUTION},
            ResultCode::BUY_NOW_AMOUNT_INVALID => $columnHeaders->{Constants\Csv\Lot::BUY_NOW_AMOUNT},
            ResultCode::BUY_NOW_AMOUNT_REQUIRED => $columnHeaders->{Constants\Csv\Lot::BUY_NOW_AMOUNT},
            ResultCode::BUY_NOW_AMOUNT_SUPPORTED_IN_REVERSE_AUCTION => $columnHeaders->{Constants\Csv\Lot::BUY_NOW_AMOUNT},
            ResultCode::BUY_NOW_EXCLUDE_BULK_GROUP => $columnHeaders->{Constants\Csv\Lot::BUY_NOW_AMOUNT},
            ResultCode::BUY_NOW_SELECT_QUANTITY_WITH_QUANTITY_DIGITS_NOT_ALLOWED => $columnHeaders->{Constants\Csv\Lot::BUY_NOW_SELECT_QUANTITY},
            ResultCode::BUY_NOW_SELECT_QUANTITY_FOR_LIVE_OR_HYBRID_AUCTION_NOT_ALLOWED => $columnHeaders->{Constants\Csv\Lot::BUY_NOW_SELECT_QUANTITY},
            ResultCode::BUY_NOW_SELECT_QUANTITY_FOR_REVERSE_AUCTION_NOT_ALLOWED => $columnHeaders->{Constants\Csv\Lot::BUY_NOW_SELECT_QUANTITY},
            ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_COMMISSION_CALCULATION_METHOD},
            ResultCode::CONSIGNOR_COMMISSION_ID_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_COMMISSION_ID},
            ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_COMMISSION_RANGES},
            ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD},
            ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_ID},
            ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_RANGES},
            ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_REFERENCE},
            ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD},
            ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_ID},
            ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_RANGES},
            ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_REFERENCE},
            ResultCode::END_PREBIDDING_DATE_INVALID => $columnHeaders->{Constants\Csv\Lot::END_PREBIDDING_DATE},
            ResultCode::GENERAL_NOTE_INVALID_ENCODING => $columnHeaders->{Constants\Csv\Lot::GENERAL_NOTE},
            ResultCode::GENERAL_NOTE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::GENERAL_NOTE},
            ResultCode::LOT_ALREADY_MARKED_SOLD => $columnHeaders->{Constants\Csv\Lot::LOT_NUM},
            ResultCode::LOT_FULL_NUM_PARSE_ERROR => $columnHeaders->{Constants\Csv\Lot::LOT_FULL_NUMBER},
            ResultCode::LOT_GROUP_INVALID => $columnHeaders->{Constants\Csv\Lot::GROUP_ID},
            ResultCode::LOT_GROUP_REQUIRED => $columnHeaders->{Constants\Csv\Lot::GROUP_ID},
            ResultCode::LOT_ITEM_ID_NOT_SPECIFIED => '',
            ResultCode::LOT_ITEM_NOT_FOUND => $columnHeaders->{Constants\Csv\Lot::LOT_NUM},
            ResultCode::LOT_NUM_EXIST => $columnHeaders->{Constants\Csv\Lot::LOT_NUM},
            ResultCode::LOT_NUM_EXT_INVALID => $columnHeaders->{Constants\Csv\Lot::LOT_NUM_EXT},
            ResultCode::LOT_NUM_EXT_INVALID_LENGTH => $columnHeaders->{Constants\Csv\Lot::LOT_NUM_EXT},
            ResultCode::LOT_NUM_HIGHER_MAX_AVAILABLE_VALUE => $columnHeaders->{Constants\Csv\Lot::LOT_NUM},
            ResultCode::LOT_NUM_INVALID => $columnHeaders->{Constants\Csv\Lot::LOT_NUM},
            ResultCode::LOT_NUM_PREFIX_INVALID => $columnHeaders->{Constants\Csv\Lot::LOT_NUM_PREFIX},
            ResultCode::LOT_NUM_REQUIRED => $columnHeaders->{Constants\Csv\Lot::LOT_NUM},
            ResultCode::LOT_STATUS_ID_ALREADY_ACTIVE => $columnHeaders->{Constants\Csv\Lot::LOT_NUM},
            ResultCode::LOT_STATUS_ID_UNKNOWN => $columnHeaders->{Constants\Csv\Lot::LOT_NUM},
            ResultCode::LOT_STATUS_UNKNOWN => $columnHeaders->{Constants\Csv\Lot::LOT_NUM},
            ResultCode::NOTE_TO_CLERK_INVALID_ENCODING => $columnHeaders->{Constants\Csv\Lot::NOTE_TO_CLERK},
            ResultCode::NOTE_TO_CLERK_REQUIRED => $columnHeaders->{Constants\Csv\Lot::NOTE_TO_CLERK},
            ResultCode::ORDER_INVALID => '',
            ResultCode::PUBLISH_DATE_INVALID => $columnHeaders->{Constants\Csv\Lot::PUBLISH_DATE},
            ResultCode::QUANTITY_INVALID => $columnHeaders->{Constants\Csv\Lot::QUANTITY},
            ResultCode::QUANTITY_REQUIRED => $columnHeaders->{Constants\Csv\Lot::QUANTITY},
            ResultCode::QUANTITY_DIGITS_INVALID => $columnHeaders->{Constants\Csv\Lot::QUANTITY_DIGITS},
            ResultCode::QUANTITY_DIGITS_REQUIRED => $columnHeaders->{Constants\Csv\Lot::QUANTITY_DIGITS},
            ResultCode::SEO_URL_INVALID_ENCODING => $columnHeaders->{Constants\Csv\Lot::SEO_URL},
            ResultCode::START_BIDDING_DATE_INVALID => $columnHeaders->{Constants\Csv\Lot::START_BIDDING_DATE},
            ResultCode::START_BIDDING_DATE_LATER_START_CLOSING_DATE => $columnHeaders->{Constants\Csv\Lot::START_BIDDING_DATE},
            ResultCode::START_CLOSING_DATE_INVALID => $columnHeaders->{Constants\Csv\Lot::START_CLOSING_DATE},
            ResultCode::START_CLOSING_DATE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::START_CLOSING_DATE},
            ResultCode::SYNC_KEY_EXIST => '',
            ResultCode::TIMEZONE_UNKNOWN => '',
            ResultCode::UNPUBLISH_DATE_INVALID => $columnHeaders->{Constants\Csv\Lot::UNPUBLISH_DATE},
            ResultCode::LOT_STATUS_REQUIRED => '',
            ResultCode::LOT_STATUS_ID_REQUIRED => '',
            ResultCode::TIMEZONE_REQUIRED => '',
            ResultCode::PUBLISH_DATE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::PUBLISH_DATE},
            ResultCode::UNPUBLISH_DATE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::UNPUBLISH_DATE},
            ResultCode::START_BIDDING_DATE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::START_BIDDING_DATE},
            ResultCode::END_PREBIDDING_DATE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::END_PREBIDDING_DATE},
            ResultCode::SEO_URL_REQUIRED => $columnHeaders->{Constants\Csv\Lot::SEO_URL},
            ResultCode::TERMS_AND_CONDITIONS_REQUIRED => $columnHeaders->{Constants\Csv\Lot::TERMS_AND_CONDITIONS},
            ResultCode::BULK_GROUP_REQUIRED => $columnHeaders->{Constants\Csv\Lot::BULK_CONTROL},
            ResultCode::LOT_STATUS_HIDDEN => '',
            ResultCode::LOT_STATUS_ID_HIDDEN => '',
            ResultCode::TIMEZONE_HIDDEN => '',
            ResultCode::PUBLISH_DATE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::PUBLISH_DATE},
            ResultCode::UNPUBLISH_DATE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::UNPUBLISH_DATE},
            ResultCode::START_BIDDING_DATE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::START_BIDDING_DATE},
            ResultCode::END_PREBIDDING_DATE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::END_PREBIDDING_DATE},
            ResultCode::START_CLOSING_DATE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::START_CLOSING_DATE},
            ResultCode::LOT_NUMBER_HIDDEN => $columnHeaders->{Constants\Csv\Lot::LOT_NUM},
            ResultCode::LOT_NUMBER_EXT_HIDDEN => $columnHeaders->{Constants\Csv\Lot::LOT_NUM_EXT},
            ResultCode::LOT_NUMBER_PREFIX_HIDDEN => $columnHeaders->{Constants\Csv\Lot::LOT_NUM_PREFIX},
            ResultCode::LOT_FULL_NUMBER_HIDDEN => $columnHeaders->{Constants\Csv\Lot::LOT_FULL_NUMBER},
            ResultCode::SEO_URL_HIDDEN => $columnHeaders->{Constants\Csv\Lot::SEO_URL},
            ResultCode::LOT_GROUP_HIDDEN => $columnHeaders->{Constants\Csv\Lot::GROUP_ID},
            ResultCode::QUANTITY_HIDDEN => $columnHeaders->{Constants\Csv\Lot::QUANTITY},
            ResultCode::QUANTITY_DIGITS_HIDDEN => $columnHeaders->{Constants\Csv\Lot::QUANTITY_DIGITS},
            ResultCode::QUANTITY_X_MONEY_HIDDEN => $columnHeaders->{Constants\Csv\Lot::QUANTITY_X_MONEY},
            ResultCode::TERMS_AND_CONDITIONS_HIDDEN => $columnHeaders->{Constants\Csv\Lot::TERMS_AND_CONDITIONS},
            ResultCode::LISTING_ONLY_HIDDEN => $columnHeaders->{Constants\Csv\Lot::LISTING_ONLY},
            ResultCode::BUY_NOW_AMOUNT_HIDDEN => $columnHeaders->{Constants\Csv\Lot::BUY_NOW_AMOUNT},
            ResultCode::BUY_NOW_SELECT_QUANTITY_HIDDEN => $columnHeaders->{Constants\Csv\Lot::BUY_NOW_SELECT_QUANTITY},
            ResultCode::FEATURED_HIDDEN => $columnHeaders->{Constants\Csv\Lot::FEATURED},
            ResultCode::CLERK_NOTE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::NOTE_TO_CLERK},
            ResultCode::GENERAL_NOTE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::GENERAL_NOTE},
            ResultCode::BULK_GROUP_CONTROL_HIDDEN => $columnHeaders->{Constants\Csv\Lot::BULK_CONTROL},
            ResultCode::BULK_WIN_BID_DISTRIBUTION_HIDDEN => $columnHeaders->{Constants\Csv\Lot::BULK_WIN_BID_DISTRIBUTION},
            ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_HIDDEN => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_COMMISSION_CALCULATION_METHOD},
            ResultCode::CONSIGNOR_COMMISSION_ID_HIDDEN => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_COMMISSION_ID},
            ResultCode::CONSIGNOR_COMMISSION_RANGES_HIDDEN => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_COMMISSION_RANGES},
            ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_HIDDEN => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD},
            ResultCode::CONSIGNOR_SOLD_FEE_ID_HIDDEN => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_ID},
            ResultCode::CONSIGNOR_SOLD_FEE_RANGES_HIDDEN => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_RANGES},
            ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_REFERENCE},
            ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_HIDDEN => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD},
            ResultCode::CONSIGNOR_UNSOLD_FEE_ID_HIDDEN => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_ID},
            ResultCode::CONSIGNOR_UNSOLD_FEE_RANGES_HIDDEN => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_RANGES},
            ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_REFERENCE},
            ResultCode::CONSIGNOR_COMMISSION_REQUIRED => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_COMMISSION_ID},
            ResultCode::CONSIGNOR_SOLD_FEE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_ID},
            ResultCode::CONSIGNOR_UNSOLD_FEE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_ID},
            ResultCode::BEST_OFFER_HIDDEN => $columnHeaders->{Constants\Csv\Lot::BEST_OFFER},
            ResultCode::NO_BIDDING_HIDDEN => $columnHeaders->{Constants\Csv\Lot::NO_BIDDING},
            ResultCode::HP_TAX_SCHEMA_ID_INVALID => $columnHeaders->{Constants\Csv\Lot::HP_TAX_SCHEMA_ID},
            ResultCode::HP_TAX_SCHEMA_ID_REQUIRED => $columnHeaders->{Constants\Csv\Lot::HP_TAX_SCHEMA_ID},
            ResultCode::HP_TAX_SCHEMA_ID_HIDDEN => $columnHeaders->{Constants\Csv\Lot::HP_TAX_SCHEMA_ID},
            ResultCode::HP_TAX_SCHEMA_COUNTRY_MISMATCH => $columnHeaders->{Constants\Csv\Lot::HP_TAX_SCHEMA_ID},
            ResultCode::BP_TAX_SCHEMA_ID_INVALID => $columnHeaders->{Constants\Csv\Lot::BP_TAX_SCHEMA_ID},
            ResultCode::BP_TAX_SCHEMA_ID_REQUIRED => $columnHeaders->{Constants\Csv\Lot::BP_TAX_SCHEMA_ID},
            ResultCode::BP_TAX_SCHEMA_ID_HIDDEN => $columnHeaders->{Constants\Csv\Lot::BP_TAX_SCHEMA_ID},
            ResultCode::BP_TAX_SCHEMA_COUNTRY_MISMATCH => $columnHeaders->{Constants\Csv\Lot::BP_TAX_SCHEMA_ID},
        ];
    }

    public array $errorMessages = [
        ResultCode::ACCESS_DENIED => 'Access to auction lot denied',
        ResultCode::AUCTION_ID_NOT_SPECIFIED => 'Id not specified',
        ResultCode::AUCTION_LOT_EXIST => 'For provided Auction and Item already exists',
        ResultCode::AUCTION_NOT_FOUND => 'Not found',
        ResultCode::BULK_CONTROL_NOT_FOUND => 'Not bulk master item',
        ResultCode::BULK_GROUP_EXCLUDE_BUY_NOW => 'Lots that are part of a bulk vs piecemeal group cannot have a buy now amount',
        ResultCode::BULK_MASTER_LOT_START_BIDDING_DATE_NOT_EDITABLE => 'Date for bulk master lot is not editable',
        ResultCode::BULK_MASTER_LOT_START_CLOSING_DATE_NOT_EDITABLE => 'Date for bulk master lot is not editable',
        ResultCode::BULK_MASTER_WIN_BID_DISTRIBUTION_UNKNOWN => 'Unknown',
        ResultCode::BUY_NOW_AMOUNT_INVALID => 'Should be numeric',
        ResultCode::BUY_NOW_AMOUNT_REQUIRED => 'Required',
        ResultCode::BUY_NOW_AMOUNT_SUPPORTED_IN_REVERSE_AUCTION => 'Supported in reverse auction',
        ResultCode::BUY_NOW_EXCLUDE_BULK_GROUP => 'Lots that are part of a bulk vs piecemeal group cannot have a buy now amount',
        ResultCode::BUY_NOW_SELECT_QUANTITY_WITH_QUANTITY_DIGITS_NOT_ALLOWED => 'Cannot be used together with “Quantity digits”. “Allow buyer select quantity” only works for Quantity digits zero or when it is not set on any level',
        ResultCode::BUY_NOW_SELECT_QUANTITY_FOR_LIVE_OR_HYBRID_AUCTION_NOT_ALLOWED => 'Cannot be used for live or hybrid auction',
        ResultCode::BUY_NOW_SELECT_QUANTITY_FOR_REVERSE_AUCTION_NOT_ALLOWED => 'Cannot be used for reverse auction',
        ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_COMMISSION_ID_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID => 'Invalid ranges',
        ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID => 'Invalid ranges',
        ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID => 'Invalid ranges',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID => 'Unknown',
        ResultCode::END_PREBIDDING_DATE_INVALID => 'Invalid',
        ResultCode::GENERAL_NOTE_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::GENERAL_NOTE_REQUIRED => 'Required',
        ResultCode::LOT_ALREADY_MARKED_SOLD => 'Is already marked as sold... Please mark it as unsold/active first.',
        ResultCode::LOT_FULL_NUM_PARSE_ERROR => 'Parse error',
        ResultCode::LOT_GROUP_INVALID => 'Should be numeric integer',
        ResultCode::LOT_GROUP_REQUIRED => 'Required',
        ResultCode::LOT_ITEM_ID_NOT_SPECIFIED => 'Id not specified',
        ResultCode::LOT_ITEM_NOT_FOUND => 'Not found',
        ResultCode::LOT_NUM_EXIST => 'Already exist in this auction. Please pick a unique one',
        ResultCode::LOT_NUM_EXT_INVALID => 'Lot number extension should be alpha numeric',
        ResultCode::LOT_NUM_EXT_INVALID_LENGTH => 'Should be less or equal to %s characters',
        ResultCode::LOT_NUM_HIGHER_MAX_AVAILABLE_VALUE => 'Higher than the max available value',
        ResultCode::LOT_NUM_INVALID => 'Must be numeric integer',
        ResultCode::LOT_NUM_PREFIX_INVALID => 'Should be alpha numeric',
        ResultCode::LOT_NUM_REQUIRED => 'Required',
        ResultCode::LOT_STATUS_ID_ALREADY_ACTIVE => 'Already active',
        ResultCode::LOT_STATUS_ID_UNKNOWN => 'Unknown',
        ResultCode::LOT_STATUS_UNKNOWN => 'Unknown',
        ResultCode::NOTE_TO_CLERK_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::NOTE_TO_CLERK_REQUIRED => 'Required',
        ResultCode::ORDER_INVALID => 'Should be numeric integer',
        ResultCode::PUBLISH_DATE_INVALID => 'Invalid',
        ResultCode::QUANTITY_INVALID => 'Should be positive decimal',
        ResultCode::QUANTITY_REQUIRED => 'Required',
        ResultCode::QUANTITY_DIGITS_INVALID => 'Should be integer between 0 and ' . Constants\Lot::LOT_QUANTITY_MAX_FRACTIONAL_DIGITS,
        ResultCode::QUANTITY_DIGITS_REQUIRED => 'Required',
        ResultCode::SEO_URL_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::START_BIDDING_DATE_INVALID => 'Invalid',
        ResultCode::START_BIDDING_DATE_LATER_START_CLOSING_DATE => 'Later then start closing date',
        ResultCode::START_CLOSING_DATE_INVALID => 'Invalid',
        ResultCode::START_CLOSING_DATE_REQUIRED => 'Required',
        ResultCode::SYNC_KEY_EXIST => 'Already exist',
        ResultCode::TIMEZONE_UNKNOWN => 'Unknown',
        ResultCode::UNPUBLISH_DATE_INVALID => 'Invalid',
        ResultCode::LOT_STATUS_REQUIRED => 'Required',
        ResultCode::LOT_STATUS_ID_REQUIRED => 'Required',
        ResultCode::TIMEZONE_REQUIRED => 'Required',
        ResultCode::PUBLISH_DATE_REQUIRED => 'Required',
        ResultCode::UNPUBLISH_DATE_REQUIRED => 'Required',
        ResultCode::START_BIDDING_DATE_REQUIRED => 'Required',
        ResultCode::END_PREBIDDING_DATE_REQUIRED => 'Required',
        ResultCode::SEO_URL_REQUIRED => 'Required',
        ResultCode::TERMS_AND_CONDITIONS_REQUIRED => 'Required',
        ResultCode::BULK_GROUP_REQUIRED => 'Required',
        ResultCode::CONSIGNOR_COMMISSION_REQUIRED => 'Required',
        ResultCode::CONSIGNOR_SOLD_FEE_REQUIRED => 'Required',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REQUIRED => 'Required',
        ResultCode::LOT_STATUS_HIDDEN => 'Hidden',
        ResultCode::LOT_STATUS_ID_HIDDEN => 'Hidden',
        ResultCode::TIMEZONE_HIDDEN => 'Hidden',
        ResultCode::PUBLISH_DATE_HIDDEN => 'Hidden',
        ResultCode::UNPUBLISH_DATE_HIDDEN => 'Hidden',
        ResultCode::START_BIDDING_DATE_HIDDEN => 'Hidden',
        ResultCode::END_PREBIDDING_DATE_HIDDEN => 'Hidden',
        ResultCode::START_CLOSING_DATE_HIDDEN => 'Hidden',
        ResultCode::LOT_NUMBER_HIDDEN => 'Hidden',
        ResultCode::LOT_NUMBER_EXT_HIDDEN => 'Hidden',
        ResultCode::LOT_NUMBER_PREFIX_HIDDEN => 'Hidden',
        ResultCode::LOT_FULL_NUMBER_HIDDEN => 'Hidden',
        ResultCode::SEO_URL_HIDDEN => 'Hidden',
        ResultCode::LOT_GROUP_HIDDEN => 'Hidden',
        ResultCode::QUANTITY_HIDDEN => 'Hidden',
        ResultCode::QUANTITY_DIGITS_HIDDEN => 'Hidden',
        ResultCode::QUANTITY_X_MONEY_HIDDEN => 'Hidden',
        ResultCode::TERMS_AND_CONDITIONS_HIDDEN => 'Hidden',
        ResultCode::LISTING_ONLY_HIDDEN => 'Hidden',
        ResultCode::BUY_NOW_AMOUNT_HIDDEN => 'Hidden',
        ResultCode::BUY_NOW_SELECT_QUANTITY_HIDDEN => 'Hidden',
        ResultCode::FEATURED_HIDDEN => 'Hidden',
        ResultCode::CLERK_NOTE_HIDDEN => 'Hidden',
        ResultCode::GENERAL_NOTE_HIDDEN => 'Hidden',
        ResultCode::BULK_GROUP_CONTROL_HIDDEN => 'Hidden',
        ResultCode::BULK_WIN_BID_DISTRIBUTION_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_COMMISSION_ID_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_COMMISSION_RANGES_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_SOLD_FEE_ID_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_SOLD_FEE_RANGES_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_UNSOLD_FEE_ID_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_UNSOLD_FEE_RANGES_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_HIDDEN => 'Hidden',
        ResultCode::BEST_OFFER_HIDDEN => 'Hidden',
        ResultCode::NO_BIDDING_HIDDEN => 'Hidden',
        ResultCode::HP_TAX_SCHEMA_ID_INVALID => 'Invalid',
        ResultCode::HP_TAX_SCHEMA_ID_REQUIRED => 'Required',
        ResultCode::HP_TAX_SCHEMA_ID_HIDDEN => 'Hidden',
        ResultCode::HP_TAX_SCHEMA_COUNTRY_MISMATCH => 'Country mismatch',
        ResultCode::BP_TAX_SCHEMA_ID_INVALID => 'Invalid',
        ResultCode::BP_TAX_SCHEMA_ID_REQUIRED => 'Required',
        ResultCode::BP_TAX_SCHEMA_ID_HIDDEN => 'Hidden',
        ResultCode::BP_TAX_SCHEMA_COUNTRY_MISMATCH => 'Country mismatch',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        AuctionLotMakerInputDto $inputDto,
        AuctionLotMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->getAuctionLotMakerDtoHelper()->construct($configDto->mode);
        $this->getAuctionLotMakerAccessChecker()->construct($inputDto, $configDto);
        return $this;
    }

    /**
     * Validate data
     * @return bool
     */
    public function validate(): bool
    {
        $inputDto = $this->getAuctionLotMakerDtoHelper()->prepareValues($this->getInputDto(), $this->getConfigDto());
        $this->setInputDto($inputDto);
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        $this->resetErrors();

        if (!$this->getAuctionLotMakerAccessChecker()->canEdit()) {
            $this->addError(ResultCode::ACCESS_DENIED);
            return false;
        }

        if (!$configDto->mode->isWebAdmin()) {
            $this->addTagNamesToErrorMessages();
        }

        if (!$inputDto->id) {
            if ($configDto->mode->isSoap()) {
                $this->checkRequired('auctionId', ResultCode::AUCTION_ID_NOT_SPECIFIED);
                $this->checkRequired('lotItemId', ResultCode::LOT_ITEM_ID_NOT_SPECIFIED);
            }
            $this->checkExistAuction('auctionId', ResultCode::AUCTION_NOT_FOUND);
            $this->checkExistLotItem('lotItemId', ResultCode::LOT_ITEM_NOT_FOUND);
            $this->checkNotExistAuctionLot('lotItemId', ResultCode::AUCTION_LOT_EXIST);
        }
        $this->checkSyncKeyUnique('syncKey', ResultCode::SYNC_KEY_EXIST, Constants\EntitySync::TYPE_AUCTION_LOT_ITEM);

        $this->checkVisibilityWithLotFieldConfig('lotStatus', ResultCode::LOT_STATUS_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('lotStatusId', ResultCode::LOT_STATUS_ID_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('timezone', ResultCode::TIMEZONE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('publishDate', ResultCode::PUBLISH_DATE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('unpublishDate', ResultCode::UNPUBLISH_DATE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('startBiddingDate', ResultCode::START_BIDDING_DATE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('endPrebiddingDate', ResultCode::END_PREBIDDING_DATE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('startClosingDate', ResultCode::START_CLOSING_DATE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('lotNum', ResultCode::LOT_NUMBER_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('lotNumExt', ResultCode::LOT_NUMBER_EXT_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('lotNumPrefix', ResultCode::LOT_NUMBER_PREFIX_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('lotFullNum', ResultCode::LOT_FULL_NUMBER_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('seoUrl', ResultCode::SEO_URL_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('lotGroup', ResultCode::LOT_GROUP_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('quantityXMoney', ResultCode::QUANTITY_X_MONEY_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('termsAndConditions', ResultCode::TERMS_AND_CONDITIONS_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('listingOnly', ResultCode::LISTING_ONLY_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('buyNowAmount', ResultCode::BUY_NOW_AMOUNT_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('buyNowSelectQuantityEnabled', ResultCode::BUY_NOW_SELECT_QUANTITY_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('featured', ResultCode::FEATURED_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('noteToClerk', ResultCode::CLERK_NOTE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('generalNote', ResultCode::GENERAL_NOTE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('bulkControl', ResultCode::BULK_GROUP_CONTROL_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('bulkWinBidDistribution', ResultCode::BULK_WIN_BID_DISTRIBUTION_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('bestOffer', ResultCode::BEST_OFFER_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('noBidding', ResultCode::NO_BIDDING_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('hpTaxSchemaId', ResultCode::HP_TAX_SCHEMA_ID_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('bpTaxSchemaId', ResultCode::BP_TAX_SCHEMA_ID_HIDDEN);

        $this->validateLotNo();
        if (AuctionStatusPureChecker::new()->isLiveOrHybrid($configDto->auctionType)) {
            $this->checkRequiredWithLotFieldConfig('lotGroup', ResultCode::LOT_GROUP_REQUIRED);
        }
        if (AuctionStatusPureChecker::new()->isTimed($configDto->auctionType)) {
            if (!$configDto->extendAll) {
                $this->checkRequired('startClosingDate', ResultCode::START_CLOSING_DATE_REQUIRED);
            }
            $this->checkDate('startClosingDate', ResultCode::START_CLOSING_DATE_INVALID);
            $this->checkDateLaterThan('startBiddingDate', 'startClosingDate', ResultCode::START_BIDDING_DATE_LATER_START_CLOSING_DATE);
        }
        $this->checkInArray('lotStatus', ResultCode::LOT_STATUS_UNKNOWN, Constants\Lot::$lotStatusNames);
        $this->checkInArrayKeys('lotStatusId', ResultCode::LOT_STATUS_ID_UNKNOWN, Constants\Lot::$lotStatusNames);
        if (isset($inputDto->lotStatusId)) {
            $this->checkRequiredWithLotFieldConfig('lotStatusId', ResultCode::LOT_STATUS_ID_REQUIRED);
        } else {
            $this->checkRequiredWithLotFieldConfig('lotStatus', ResultCode::LOT_STATUS_REQUIRED);
        }

        $this->checkInArrayKeys('bulkWinBidDistribution', ResultCode::BULK_MASTER_WIN_BID_DISTRIBUTION_UNKNOWN, Constants\LotBulkGroup::$bulkWinBidDistributionNames);
        $this->checkDate('endPrebiddingDate', ResultCode::END_PREBIDDING_DATE_INVALID);
        $this->checkRequiredWithLotFieldConfig('endPrebiddingDate', ResultCode::END_PREBIDDING_DATE_REQUIRED);
        $this->checkDate('publishDate', ResultCode::PUBLISH_DATE_INVALID);
        $this->checkRequiredWithLotFieldConfig('publishDate', ResultCode::PUBLISH_DATE_REQUIRED);
        $this->checkDate('unpublishDate', ResultCode::PUBLISH_DATE_INVALID);
        $this->checkRequiredWithLotFieldConfig('unpublishDate', ResultCode::UNPUBLISH_DATE_REQUIRED);
        $this->checkDate('startBiddingDate', ResultCode::START_BIDDING_DATE_INVALID);
        $this->checkRequiredWithLotFieldConfig('startBiddingDate', ResultCode::START_BIDDING_DATE_REQUIRED);
        $this->checkExistAuctionLotBulkControl('bulkControl', ResultCode::BULK_CONTROL_NOT_FOUND);
        $this->checkInteger('order', ResultCode::ORDER_INVALID);
        $this->checkInteger('lotGroup', ResultCode::LOT_GROUP_INVALID);
        $quantityScale = $this->createQuantityScaleDetector()->detect(
            QuantityScaleDetectorInput::new()->fromMakerDto($inputDto, $configDto)
        );
        if (
            $inputDto->id
            || $inputDto->lotItemId
        ) {
            $this->createQuantityValidationIntegrator()->validate($this, $quantityScale);
            $this->checkRequiredWithLotFieldConfig('quantity', ResultCode::QUANTITY_REQUIRED);
            $this->checkVisibilityWithLotFieldConfig('quantity', ResultCode::QUANTITY_HIDDEN);
            $this->checkInteger('quantityDigits', ResultCode::QUANTITY_DIGITS_INVALID);
            $this->checkBetween('quantityDigits', ResultCode::QUANTITY_DIGITS_INVALID, 0, Constants\Lot::LOT_QUANTITY_MAX_FRACTIONAL_DIGITS);
            $this->checkRequiredWithLotFieldConfig('quantityDigits', ResultCode::QUANTITY_DIGITS_REQUIRED);
            $this->checkVisibilityWithLotFieldConfig('quantityDigits', ResultCode::QUANTITY_DIGITS_HIDDEN);
            $this->checkVisibilityWithLotFieldConfig('consignorCommissionCalculationMethod', ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_HIDDEN);
            $this->checkVisibilityWithLotFieldConfig('consignorCommissionId', ResultCode::CONSIGNOR_COMMISSION_ID_HIDDEN);
            $this->checkVisibilityWithLotFieldConfig('consignorCommissionRanges', ResultCode::CONSIGNOR_COMMISSION_RANGES_HIDDEN);
            $this->checkVisibilityWithLotFieldConfig('consignorSoldFeeCalculationMethod', ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_HIDDEN);
            $this->checkVisibilityWithLotFieldConfig('consignorSoldFeeId', ResultCode::CONSIGNOR_SOLD_FEE_ID_HIDDEN);
            $this->checkVisibilityWithLotFieldConfig('consignorSoldFeeRanges', ResultCode::CONSIGNOR_SOLD_FEE_RANGES_HIDDEN);
            $this->checkVisibilityWithLotFieldConfig('consignorSoldFeeReference', ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_HIDDEN);
            $this->checkVisibilityWithLotFieldConfig('consignorUnsoldFeeCalculationMethod', ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_HIDDEN);
            $this->checkVisibilityWithLotFieldConfig('consignorUnsoldFeeId', ResultCode::CONSIGNOR_UNSOLD_FEE_ID_HIDDEN);
            $this->checkVisibilityWithLotFieldConfig('consignorUnsoldFeeRanges', ResultCode::CONSIGNOR_UNSOLD_FEE_RANGES_HIDDEN);
            $this->checkVisibilityWithLotFieldConfig('consignorUnsoldFeeReference', ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_HIDDEN);
            $this->validateConsignorCommission();
            $this->validateConsignorSoldFee();
            $this->validateConsignorUnsoldFee();
        }
        $this->checkLotAlreadyActive('lotItemId', ResultCode::LOT_STATUS_ID_ALREADY_ACTIVE);
        $this->checkRealPositive('buyNowAmount', ResultCode::BUY_NOW_AMOUNT_INVALID, true);
        $this->checkRequiredWithLotFieldConfig('buyNowAmount', ResultCode::BUY_NOW_AMOUNT_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('generalNote', ResultCode::GENERAL_NOTE_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('noteToClerk', ResultCode::NOTE_TO_CLERK_REQUIRED);
        if (AuctionStatusPureChecker::new()->isLotBulkGroupingAvailable($configDto->auctionType, $configDto->extendAll)) {
            $this->checkRequiredWithLotFieldConfig('bulkControl', ResultCode::BULK_GROUP_REQUIRED);
        }
        $this->checkTimezone('timezone', ResultCode::TIMEZONE_UNKNOWN);
        $this->checkRequiredWithLotFieldConfig('timezone', ResultCode::TIMEZONE_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('seoUrl', ResultCode::SEO_URL_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('hpTaxSchemaId', ResultCode::HP_TAX_SCHEMA_ID_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('bpTaxSchemaId', ResultCode::BP_TAX_SCHEMA_ID_REQUIRED);
        $this->checkEncoding('seoUrl', ResultCode::SEO_URL_INVALID_ENCODING);
        $this->checkEncoding('generalNote', ResultCode::GENERAL_NOTE_INVALID_ENCODING);
        $this->checkEncoding('noteToClerk', ResultCode::NOTE_TO_CLERK_INVALID_ENCODING);

        $this->validateBuyNowAmountInReverseAuction();
        $this->validateLotAlreadyMarkedSold();
        $this->validateBulkMasterDates();
        $this->validateBuyNowSelectQuantityEnabled($quantityScale);
        $this->checkRequiredWithLotFieldConfig('termsAndConditions', ResultCode::TERMS_AND_CONDITIONS_REQUIRED);
        $this->validateBulkGroupAndBuyNowMutualExclusion();
        $this->createAuctionLotTaxSchemaValidationIntegrator()->validate($this);

        $this->log();
        $isValid = empty($this->errors);
        $configDto->enableValidStatus($isValid);
        return $isValid;
    }

    protected function validateBuyNowSelectQuantityEnabled(int $quantityScale): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $isSetBuyNowSelectQuantityEnabled = isset($inputDto->buyNowSelectQuantityEnabled);
        $isBuyNowSelectQuantityEnabled = $isSetBuyNowSelectQuantityEnabled
            ? ValueResolver::new()->isTrue((string)$inputDto->buyNowSelectQuantityEnabled)
            : $this->getAuctionLotLoader()->loadById(Cast::toInt($inputDto->id))->BuyNowSelectQuantityEnabled ?? false;

        if (
            $isSetBuyNowSelectQuantityEnabled
            && $isBuyNowSelectQuantityEnabled) {
            if ($quantityScale > 0) {
                $this->addError(ResultCode::BUY_NOW_SELECT_QUANTITY_WITH_QUANTITY_DIGITS_NOT_ALLOWED);
            }
            if (AuctionStatusPureChecker::new()->isLiveOrHybrid($configDto->auctionType)) {
                $this->addError(ResultCode::BUY_NOW_SELECT_QUANTITY_FOR_LIVE_OR_HYBRID_AUCTION_NOT_ALLOWED);
            }
            if (
                AuctionStatusPureChecker::new()->isTimed($configDto->auctionType)
                && $configDto->reverse
            ) {
                $this->addError(ResultCode::BUY_NOW_SELECT_QUANTITY_FOR_REVERSE_AUCTION_NOT_ALLOWED);
            }
        }
        if ($isBuyNowSelectQuantityEnabled) {
            $this->checkRequired('buyNowAmount', ResultCode::BUY_NOW_AMOUNT_REQUIRED);
            $this->checkRequired('quantity', ResultCode::QUANTITY_REQUIRED);
        }
    }

    /**
     * Checks whether a DTO has at least one assigned date field
     *
     * @return bool
     */
    protected function isDateFieldAssigned(): bool
    {
        $inputDto = $this->getInputDto();
        return $inputDto->endPrebiddingDate
            || $inputDto->publishDate
            || $inputDto->startBiddingDate
            || $inputDto->startClosingDate
            || $inputDto->unpublishDate;
    }

    /**
     * Support logging of found errors or success
     */
    protected function log(): void
    {
        $inputDto = $this->getInputDto();
        if (empty($this->errors)) {
            log_trace('Auction lot validation done' . composeSuffix(['ali' => $inputDto->id]));
        } else {
            // detect names of constants for error codes
            [$foundNamesToCodes, $notFoundCodes] = ConstantNameResolver::new()
                ->construct()
                ->resolveManyFromClass($this->errors, ResultCode::class);

            $foundNamesWithCodes = array_map(
                static function ($v) {
                    return "{$v[1]} ({$v[0]})";
                },
                $foundNamesToCodes
            );
            $logData = [
                'ali' => $inputDto->id,
                'errors' => array_merge(array_values($foundNamesWithCodes), $notFoundCodes),
            ];
            log_debug('Auction lot validation failed' . composeSuffix($logData));
        }
    }

    /**
     * Check for buyNowAmount supported in reverse auction
     */
    protected function validateBuyNowAmountInReverseAuction(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (
            $inputDto->buyNowAmount
            && $configDto->reverse
        ) {
            $this->addError(ResultCode::BUY_NOW_AMOUNT_SUPPORTED_IN_REVERSE_AUCTION);
        }
    }

    /**
     * Check for lot is already marked as sold
     */
    protected function validateLotAlreadyMarkedSold(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (
            !$this->getSettingsManager()->get(Constants\Setting::BLOCK_SOLD_LOTS, $configDto->serviceAccountId)
            || !$inputDto->lotItemId
        ) {
            return;
        }

        $blockSoldLotsChecker = $this->createBlockSoldLotsChecker()
            ->setLotItemIds([(int)$inputDto->lotItemId])
            ->setSourceAuctionId((int)$inputDto->auctionId);
        if (!$blockSoldLotsChecker->check()) {
            $this->addError(ResultCode::LOT_ALREADY_MARKED_SOLD, $blockSoldLotsChecker->produceErrorMessageForWebAdmin());
        }
    }

    /**
     * Check lotFullNum, split to lotNum, lotNumExtension, lotNumPrefix to validate each element separately
     */
    protected function validateLotNo(): void
    {
        $this->createLotNoValidationIntegrator()->validate($this);
    }

    protected function validateBulkMasterDates(): void
    {
        $inputDto = $this->getInputDto();
        $auctionLot = $this->getAuctionLotLoader()->loadById(Cast::toInt($inputDto->id));
        if ($auctionLot) {
            $hasMasterRole = isset($inputDto->bulkControl)
                ? $inputDto->bulkControl === Constants\LotBulkGroup::LBGR_MASTER
                : $auctionLot->hasMasterRole();

            if (
                $hasMasterRole
                && $this->getLotBulkGroupExistenceChecker()->existPiecemealGrouping(Cast::toInt($inputDto->id))
            ) {
                $this->checkDateNotChanged($auctionLot->StartClosingDate, 'startClosingDate', ResultCode::BULK_MASTER_LOT_START_CLOSING_DATE_NOT_EDITABLE);
                $this->checkDateNotChanged($auctionLot->StartBiddingDate, 'startBiddingDate', ResultCode::BULK_MASTER_LOT_START_BIDDING_DATE_NOT_EDITABLE);
            }
        }
    }

    /**
     * Is input date equals to entity date
     * @param DateTimeInterface|null $entityDate
     * @param string $field
     * @param int $error
     */
    protected function checkDateNotChanged(
        ?DateTimeInterface $entityDate,
        string $field,
        int $error
    ): void {
        $inputDto = $this->getInputDto();
        if (!isset($inputDto->$field)) {
            return;
        }

        try {
            $inputDateTime = $inputDto->$field
                ? new DateTime($inputDto->$field, new DateTimeZone($inputDto->timezone ?: 'UTC'))
                : null;
            //Input date can't contain seconds, but entity date can
            $entityDate = $this->getDateHelper()->dropSeconds($entityDate);
            $this->addErrorIfFail($error, $inputDateTime == $entityDate);
        } catch (Exception) {
            // Invalid timezone
            return;
        }
    }

    /**
     * Lot cannot be a member of Bulk Group and be enabled for Instant purchase in the same time (SAM-3103)
     */
    protected function validateBulkGroupAndBuyNowMutualExclusion(): void
    {
        $inputDto = $this->getInputDto();
        $auctionLot = $this->getAuctionLotLoader()->loadById(Cast::toInt($inputDto->id));
        $lotBulkGroupingRole = $auctionLot?->getLotBulkGroupingRole();
        $validator = $this->createLotBulkGroupToBuyNowValidator();
        $isValid = $validator->validate(
            isset($inputDto->buyNowAmount),
            Cast::toFloat($inputDto->buyNowAmount),
            $auctionLot->BuyNowAmount ?? null,
            isset($inputDto->bulkControl),
            $inputDto->bulkControl,
            $lotBulkGroupingRole
        );

        if (!$isValid) {
            if ($validator->hasBuyNowError()) {
                $this->addError(ResultCode::BUY_NOW_EXCLUDE_BULK_GROUP);
            } elseif ($validator->hasBulkGroupError()) {
                $this->addError(ResultCode::BULK_GROUP_EXCLUDE_BUY_NOW);
            }
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkLotAlreadyActive(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        if (
            !$inputDto->$field
            || (
                !$auctionLotStatusPureChecker->isActive((int)$inputDto->lotStatusId)
                && $inputDto->lotStatus !== Constants\Lot::$lotStatusNames[Constants\Lot::LS_ACTIVE]
            )
        ) {
            return;
        }

        $auctionId = Cast::toInt($inputDto->auctionId, Constants\Type::F_INT_POSITIVE);
        $lotItemId = Cast::toInt($inputDto->$field, Constants\Type::F_INT_POSITIVE);

        if ($this->getAuctionLotDataIntegrityChecker()->isAlreadyActive($lotItemId, $auctionId)) {
            $this->addError($error, $this->getAuctionLotDataIntegrityChecker()->produceErrorMessage());
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistAuctionLotBulkControl(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (!$inputDto->$field) {
            return;
        }

        $lotNoConcatenated = (string)$inputDto->$field;
        $auctionId = (int)$inputDto->auctionId;
        $isFound = $this->getLotBulkGroupExistenceChecker()
            ->existMasterAuctionLotByLotNoConcatenated($lotNoConcatenated, $auctionId);
        if (!$isFound) {
            $this->addError($error);
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistAuction(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }
        if (!$this->getAuctionExistenceChecker()->existById((int)$inputDto->$field)) {
            $this->addError($error);
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistLotItem(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }
        if (!$this->getLotItemLoader()->load((int)$inputDto->$field)) {
            $this->addError($error);
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkNotExistAuctionLot(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }
        $isFound = $this->getAuctionLotExistenceChecker()->exist((int)$inputDto->$field, (int)$inputDto->auctionId);
        if ($isFound) {
            $this->addError($error);
        }
    }

    // ---------------------------------------------------------------------------------------------
    // General checking methods

    /**
     * Get required attribute for the field config group from lot_field_config table
     * @param string $field
     * @return bool
     */
    protected function isRequiredByLotFieldConfig(string $field): bool
    {
        $configDto = $this->getConfigDto();
        $isRequired = $this->getLotFieldConfigProvider()
            ->setFieldMap(EntityMakerFieldMap::new())
            ->isRequired($field, $configDto->serviceAccountId);
        return $isRequired;
    }

    protected function isVisibleByLotFieldConfig(string $field): bool
    {
        $configDto = $this->getConfigDto();
        $isVisible = $this->getLotFieldConfigProvider()
            ->setFieldMap(EntityMakerFieldMap::new())
            ->isVisible($field, $configDto->serviceAccountId);
        return $isVisible;
    }

    /**
     * Regular check of requirement and if field is required in lot_field_config table
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkRequiredWithLotFieldConfig(string $field, int $error): void
    {
        if (!$this->isRequiredByLotFieldConfig($field)) {
            return;
        }
        $this->checkRequired($field, $error);
    }

    protected function checkVisibilityWithLotFieldConfig(string $field, int $error): void
    {
        if (
            !$this->isVisibleByLotFieldConfig($field)
            && isset($this->getInputDto()->{$field})
        ) {
            $this->addError($error);
        }
    }

    // ---------------------------------------------------------------------------------------------
    /** GetErrors Methods */

    /**
     * Get buyNowAmount errors
     * @return int[]
     */
    protected function getBuyNowAmountErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::BUY_NOW_AMOUNT_INVALID,
                ResultCode::BUY_NOW_AMOUNT_REQUIRED,
                ResultCode::BUY_NOW_EXCLUDE_BULK_GROUP
            ]
        );
        return $intersected;
    }

    /**
     * Get buyNowSelectQuantity errors
     * @return int[]
     */
    protected function getBuyNowSelectQuantityEnabledErrors(): array
    {
        $intersected = array_intersect($this->errors, [
            ResultCode::BUY_NOW_SELECT_QUANTITY_WITH_QUANTITY_DIGITS_NOT_ALLOWED,
            ResultCode::BUY_NOW_SELECT_QUANTITY_FOR_LIVE_OR_HYBRID_AUCTION_NOT_ALLOWED,
            ResultCode::BUY_NOW_SELECT_QUANTITY_FOR_REVERSE_AUCTION_NOT_ALLOWED,
        ]);
        return $intersected;
    }

    /**
     * Get startClosingDate errors
     * @return int[]
     */
    protected function getStartClosingDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::START_CLOSING_DATE_INVALID,
                ResultCode::START_CLOSING_DATE_REQUIRED,
                ResultCode::START_BIDDING_DATE_LATER_START_CLOSING_DATE,
            ]
        );
        return $intersected;
    }

    /**
     * Get endPrebiddingDate errors
     * @return int[]
     */
    protected function getEndPrebiddingDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::END_PREBIDDING_DATE_INVALID,
                ResultCode::END_PREBIDDING_DATE_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * Get generalNote errors
     * @return int[]
     */
    protected function getGeneralNoteErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::GENERAL_NOTE_REQUIRED]);
        return $intersected;
    }

    /**
     * Get lotFullNum errors
     * @return int[]
     */
    protected function getLotFullNumErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::LOT_NUM_EXT_INVALID,
                ResultCode::LOT_FULL_NUM_PARSE_ERROR,
                ResultCode::LOT_NUM_EXIST,
                ResultCode::LOT_NUM_HIGHER_MAX_AVAILABLE_VALUE,
                ResultCode::LOT_NUM_INVALID,
                ResultCode::LOT_NUM_PREFIX_INVALID,
                ResultCode::LOT_NUM_REQUIRED,
                ResultCode::LOT_NUM_EXT_INVALID_LENGTH,
            ]
        );
        return $intersected;
    }

    /**
     * Get lotNum errors
     * @return int[]
     */
    protected function getLotNumErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::LOT_NUM_EXIST,
                ResultCode::LOT_NUM_HIGHER_MAX_AVAILABLE_VALUE,
                ResultCode::LOT_NUM_INVALID,
                ResultCode::LOT_NUM_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * Get lotNumExt errors
     * @return int[]
     */
    public function getLotNumExtErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::LOT_NUM_EXT_INVALID,
                ResultCode::LOT_NUM_EXT_INVALID_LENGTH,
            ]
        );
        return $intersected;
    }

    /**
     * Get lotNumPrefix errors
     * @return int[]
     */
    protected function getLotNumPrefixErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::LOT_NUM_PREFIX_INVALID]);
        return $intersected;
    }

    /**
     * Get lotStatusId errors
     * @return int[]
     */
    protected function getLotStatusIdErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::LOT_ALREADY_MARKED_SOLD,
                ResultCode::LOT_STATUS_ID_ALREADY_ACTIVE
            ]
        );
        return $intersected;
    }

    /**
     * Get noteToClerk errors
     * @return int[]
     */
    protected function getNoteToClerkErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::NOTE_TO_CLERK_REQUIRED]);
        return $intersected;
    }

    /**
     * Get lotGroup errors
     * @return int[]
     */
    protected function getLotGroupErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::LOT_GROUP_REQUIRED,
                ResultCode::LOT_GROUP_INVALID,
            ]
        );
        return $intersected;
    }

    /**
     * Get quantity errors
     * @return int[]
     */
    protected function getQuantityErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::QUANTITY_INVALID,
                ResultCode::QUANTITY_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * Get quantity errors
     * @return int[]
     */
    protected function getQuantityDigitsErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::QUANTITY_DIGITS_INVALID,
                ResultCode::QUANTITY_DIGITS_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * Get startBiddingDate errors
     * @return int[]
     */
    protected function getStartBiddingDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::START_BIDDING_DATE_INVALID,
                ResultCode::START_BIDDING_DATE_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * Get timezone errors
     * @return int[]
     */
    protected function getTimezoneErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::TIMEZONE_UNKNOWN,
                ResultCode::TIMEZONE_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * Get publishDate errors
     * @return int[]
     */
    protected function getPublishDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::PUBLISH_DATE_INVALID,
                ResultCode::PUBLISH_DATE_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * Get unpublishDate errors
     * @return int[]
     */
    protected function getUnpublishDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::PUBLISH_DATE_INVALID,
                ResultCode::UNPUBLISH_DATE_REQUIRED,
            ]
        );
        return $intersected;
    }

    /** --------------------------------------
     * Start "Consignor Commission & Fee" related logic
     */
    protected function validateConsignorCommission(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $this->checkConsignorCommissionFeeRequired('consignorCommissionId', 'consignorCommissionRanges', ResultCode::CONSIGNOR_COMMISSION_REQUIRED);
        $this->checkConsignorCommissionFeeId('consignorCommissionId', ResultCode::CONSIGNOR_COMMISSION_ID_INVALID);
        $consignorCommissionId = $inputDto->consignorCommissionId;
        if ($consignorCommissionId) {
            $isExist = $this->createConsignorCommissionFeeExistenceChecker()->existByIdAndAccountId(
                (int)$consignorCommissionId,
                $configDto->serviceAccountId
            );
            if (!$isExist) {
                $this->addError(ResultCode::CONSIGNOR_COMMISSION_ID_INVALID);
            }
        } else {
            $this->checkConsignorCommissionFeeRanges('consignorCommissionRanges', ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID);
            $this->checkConsignorCommissionFeeCalculationMethod('consignorCommissionCalculationMethod', ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID);
        }
    }

    protected function validateConsignorSoldFee(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $this->checkConsignorCommissionFeeRequired('consignorSoldFeeId', 'consignorSoldFeeRanges', ResultCode::CONSIGNOR_SOLD_FEE_REQUIRED);
        $this->checkConsignorCommissionFeeId('consignorSoldFeeId', ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID);
        $consignorSoldFeeId = $inputDto->consignorSoldFeeId;
        if ($consignorSoldFeeId) {
            $isExist = $this->createConsignorCommissionFeeExistenceChecker()
                ->existByIdAndAccountId((int)$consignorSoldFeeId, $configDto->serviceAccountId);
            if (!$isExist) {
                $this->addError(ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID);
            }
        } else {
            $this->checkConsignorCommissionFeeRanges('consignorSoldFeeRanges', ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID);
            $this->checkConsignorCommissionFeeCalculationMethod('consignorSoldFeeCalculationMethod', ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID);
            $this->checkConsignorCommissionFeeReference('consignorSoldFeeReference', ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID);
        }
    }

    protected function validateConsignorUnsoldFee(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $this->checkConsignorCommissionFeeRequired('consignorUnsoldFeeId', 'consignorUnsoldFeeRanges', ResultCode::CONSIGNOR_UNSOLD_FEE_REQUIRED);
        $this->checkConsignorCommissionFeeId('consignorUnsoldFeeId', ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID);
        $consignorUnsoldFeeId = $inputDto->consignorUnsoldFeeId;
        if ($consignorUnsoldFeeId) {
            $isExist = $this->createConsignorCommissionFeeExistenceChecker()
                ->existByIdAndAccountId((int)$consignorUnsoldFeeId, $configDto->serviceAccountId);
            if (!$isExist) {
                $this->addError(ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID);
            }
        } else {
            $this->checkConsignorCommissionFeeRanges('consignorUnsoldFeeRanges', ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID);
            $this->checkConsignorCommissionFeeCalculationMethod('consignorUnsoldFeeCalculationMethod', ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID);
            $this->checkConsignorCommissionFeeReference('consignorUnsoldFeeReference', ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID);
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkConsignorCommissionFeeId(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $consignorCommissionId = $inputDto->$field;
        if ($consignorCommissionId) {
            $isExist = $this->createConsignorCommissionFeeExistenceChecker()
                ->existByIdAndAccountId((int)$consignorCommissionId, $configDto->serviceAccountId);
            if (!$isExist) {
                $this->addError($error);
            }
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkConsignorCommissionFeeCalculationMethod(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $calculationMethod = $inputDto->$field;
        if ($calculationMethod) {
            $isValidCalculationMethod = $this->createConsignorCommissionFeeValidator()->isValidCalculationMethod($calculationMethod);
            if (!$isValidCalculationMethod) {
                $this->addError($error);
            }
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkConsignorCommissionFeeReference(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $feeReference = $inputDto->$field;
        if ($feeReference) {
            $isValidFeeReference = $this->createConsignorCommissionFeeValidator()->isValidFeeReference($feeReference);
            if (!$isValidFeeReference) {
                $this->addError($error);
            }
        }
    }

    protected function checkConsignorCommissionFeeRequired(string $idField, string $rangesField, int $error): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (
            $inputDto->id
            && !isset($inputDto->{$idField})
            && !isset($inputDto->{$rangesField})
        ) {
            /**
             * Skip validation for the existing Auction Lot Item, when fields are absent in input.
             * I.e. in cases where we allow custom set of fields (CSV, SOAP).
             */
            return;
        }

        $isRequired = $this->getLotFieldConfigProvider()
            ->setFieldMap(null)
            ->isRequired(Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE, $configDto->serviceAccountId);
        if (
            $isRequired
            && !$inputDto->{$idField}
            && !$inputDto->{$rangesField}
        ) {
            $this->addError($error);
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkConsignorCommissionFeeRanges(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $consignorCommissionRanges = $inputDto->$field;
        if ($consignorCommissionRanges !== null) {
            $collection = $this->createConsignorCommissionFeeRangeDtoConverter()->fromRanges($consignorCommissionRanges);
            $rangesValidator = $this->createConsignorCommissionFeeRangesValidator();
            $validationResult = $rangesValidator->validate($collection, $configDto->mode);
            if ($validationResult->hasError()) {
                $rangeErrors = $validationResult->getErrors();
                $this->addError(
                    $error,
                    $this->buildConsignorCommissionFeeRangeErrorMessage($rangeErrors)
                );
            }
        }
    }

    /**
     * Create error message with detailed info that elaborates message in CSV and SOAP outputs.
     * @param RangeValidationResultStatus[] $rangeErrors
     * @return string
     */
    protected function buildConsignorCommissionFeeRangeErrorMessage(array $rangeErrors): string
    {
        $configDto = $this->getConfigDto();
        $glue = $configDto->mode->isCsv() ? '<br />' : "\n";
        $errorLines[] = $this->getErrorMessage(ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID);

        if ($configDto->mode->isCsv() || $configDto->mode->isSoap()) {
            foreach ($rangeErrors as $error) {
                $errorLines[] = $error->getMessage() . composeSuffix(['Row#' => $error->rangeIndex, 'Property' => $error->property]);
            }
        }
        $message = implode($glue, $errorLines);
        return $message;
    }

    /**
     * @return int[]
     */
    public function getConsignorCommissionIdErrors(): array
    {
        $intersected = array_intersect($this->errors, [
            ResultCode::CONSIGNOR_COMMISSION_ID_INVALID,
            ResultCode::CONSIGNOR_COMMISSION_REQUIRED,
        ]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorCommissionCalculationMethodErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorCommissionRangeErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorSoldFeeIdErrors(): array
    {
        $intersected = array_intersect($this->errors, [
            ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID,
            ResultCode::CONSIGNOR_SOLD_FEE_REQUIRED,
        ]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorSoldFeeCalculationMethodErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorSoldFeeRangeErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorSoldFeeReferenceErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorUnsoldFeeIdErrors(): array
    {
        $intersected = array_intersect($this->errors, [
            ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID,
            ResultCode::CONSIGNOR_UNSOLD_FEE_REQUIRED,
        ]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorUnsoldFeeCalculationMethodErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorUnsoldFeeRangeErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorUnsoldFeeReferenceErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    protected function getConsignorCommissionRangesErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    protected function getConsignorSoldFeeRangesErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    protected function getConsignorUnsoldFeeRangesErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    protected function getSeoUrlErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::SEO_URL_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    protected function getTermsAndConditionsErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::TERMS_AND_CONDITIONS_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    protected function getBulkControlErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::BULK_GROUP_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getHpTaxSchemaIdErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::HP_TAX_SCHEMA_ID_INVALID,
                ResultCode::HP_TAX_SCHEMA_ID_REQUIRED,
                ResultCode::HP_TAX_SCHEMA_COUNTRY_MISMATCH,
            ]
        );
        return $intersected;
    }

    protected function getBpTaxSchemaIdErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::BP_TAX_SCHEMA_ID_INVALID,
                ResultCode::BP_TAX_SCHEMA_ID_REQUIRED,
                ResultCode::BP_TAX_SCHEMA_COUNTRY_MISMATCH,
            ]
        );
        return $intersected;
    }
}
