<?php
/**
 * Class for validating of Auction input data
 *
 * SAM-8840: Auction entity-maker module structural adjustments for v3-5
 * SAM-4241 Auction Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 3, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate;

use Sam\Auction\Auctioneer\Validate\AuctionAuctioneerExistenceCheckerAwareTrait;
use Sam\Auction\AuctionHelperAwareTrait;
use Sam\Auction\FieldConfig\Provider\AuctionFieldConfigProviderAwareTrait;
use Sam\Auction\FieldConfig\Provider\Map\EntityMakerFieldMap;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Load\Exception\CouldNotFindAuction;
use Sam\BuyersPremium\Csv\Parse\BuyersPremiumCsvParserCreateTrait;
use Sam\BuyersPremium\Validate\BuyersPremiumExistenceCheckerCreateTrait;
use Sam\Consignor\Commission\Convert\ConsignorCommissionFeeRangeDtoConverterCreateTrait;
use Sam\Consignor\Commission\Edit\Validate\ConsignorCommissionFeeRangesValidatorCreateTrait;
use Sam\Consignor\Commission\Edit\Validate\ConsignorCommissionFeeValidatorCreateTrait;
use Sam\Consignor\Commission\Edit\Validate\RangeValidationResultStatus;
use Sam\Consignor\Commission\Validate\ConsignorCommissionFeeExistenceCheckerCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Email\Validate\EmailAddressChecker;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Platform\Constant\Base\ConstantNameResolver;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Currency\Validate\CurrencyExistenceCheckerAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\Lot\Validate\LotCustomFieldExistenceCheckerCreateTrait;
use Sam\EntityMaker\Auction\Common\Access\AuctionMakerAccessCheckerAwareTrait;
use Sam\EntityMaker\Auction\Common\AuctionMakerCustomFieldManager;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Dto\AuctionMakerDtoHelperAwareTrait;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputDto;
use Sam\EntityMaker\Auction\Validate\Constants\ResultCode;
use Sam\EntityMaker\Auction\Validate\Internal\AuctionInfoLink\AuctionInfoLinkValidationIntegratorCreateTrait;
use Sam\EntityMaker\Auction\Validate\Internal\Date\DateValidationIntegratorCreateTrait;
use Sam\EntityMaker\Auction\Validate\Internal\SaleNo\SaleNoValidationIntegratorCreateTrait;
use Sam\EntityMaker\Auction\Validate\Internal\TaxSchema\AuctionTaxSchemaValidationIntegratorCreateTrait;
use Sam\EntityMaker\Base\Validate\BaseMakerValidator;
use Sam\EntityMaker\Base\Validate\Internal\BuyersPremium\BuyersPremiumValidationInput;
use Sam\EntityMaker\Base\Validate\Internal\BuyersPremium\BuyersPremiumValidationIntegratorCreateTrait;
use Sam\Rtb\Pool\Auction\Validate\AuctionRtbdCheckerCreateTrait;
use Sam\Rtb\Pool\Feature\RtbdPoolFeatureAvailabilityValidatorAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Tax\StackedTax\Schema\Load\TaxSchemaLoaderCreateTrait;
use Sam\Tax\StackedTax\Schema\Validate\TaxSchemaExistenceCheckerCreateTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;

/**
 * The following methods are handled by \Sam\EntityMaker\Base\Validator::__call() method:
 * GetErrorMessage Methods
 * @method getAdditionalBpInternetErrorMessage()
 * @method getAuctionCatalogAccessErrorMessage()
 * @method getAuctionInfoAccessErrorMessage()
 * @method getAuctionTypeErrorMessage()
 * @method getAuctionVisibilityAccessErrorMessage()
 * @method getAuthorizationAmountErrorMessage()
 * @method getBiddingConsoleAccessDateErrorMessage()
 * @method getBpTaxSchemaIdErrorMessage()
 * @method getCcThresholdDomesticErrorMessage()
 * @method getCcThresholdInternationalErrorMessage()
 * @method getDefaultLotPeriodErrorMessage()
 * @method getDefaultLotPostalCodeErrorMessage()
 * @method getEmailErrorMessage()
 * @method getEndRegisterDateErrorMessage()
 * @method getExtendTimeErrorMessage()
 * @method getHpTaxSchemaIdErrorMessage()
 * @method getLiveEndDateErrorMessage()
 * @method getLiveViewAccessErrorMessage()
 * @method getLotBiddingHistoryAccessErrorMessage()
 * @method getLotBiddingInfoAccessErrorMessage()
 * @method getLotDetailsAccessErrorMessage()
 * @method getLotOrderPrimaryTypeErrorMessage()
 * @method getLotOrderQuaternaryTypeErrorMessage()
 * @method getLotOrderSecondaryTypeErrorMessage()
 * @method getLotOrderTertiaryTypeErrorMessage()
 * @method getLotStartGapTimeErrorMessage()
 * @method getLotStartingBidAccessErrorMessage()
 * @method getLotWinningBidAccessErrorMessage()
 * @method getLotsPerIntervalErrorMessage()
 * @method getMaxOutstandingErrorMessage()
 * @method getNameErrorMessage()
 * @method getPostAucImportPremiumErrorMessage()
 * @method getRtbdNameErrorMessage()
 * @method getSaleNumErrorMessage()
 * @method getSaleNumExtensionErrorMessage()
 * @method getStartBiddingDateErrorMessage()
 * @method getStartClosingDateErrorMessage()
 * @method getServicesTaxSchemaIdErrorMessage()
 * @method getTaxPercentErrorMessage()
 * @method getTimezoneErrorMessage()
 * @method getConsignorCommissionIdErrorMessage()
 * @method getConsignorCommissionCalculationMethodErrorMessage()
 * @method getConsignorCommissionRangeErrorMessage()
 * @method getConsignorSoldFeeIdErrorMessage()
 * @method getConsignorSoldFeeCalculationMethodErrorMessage()
 * @method getConsignorSoldFeeRangeErrorMessage()
 * @method getConsignorUnsoldFeeIdErrorMessage()
 * @method getConsignorUnsoldFeeCalculationMethodErrorMessage()
 * @method getConsignorUnsoldFeeRangeErrorMessage()
 * @method getAuctionInfoLinkErrorMessage()
 * @method getDescriptionErrorMessage()
 * @method getEndPrebiddingDateErrorMessage()
 * @method getEventLocationErrorMessage()
 * @method getInvoiceLocationErrorMessage()
 * @method getInvoiceNotesErrorMessage()
 * @method getPublishDateErrorMessage()
 * @method getSeoMetaDescriptionErrorMessage()
 * @method getSeoMetaKeywordsErrorMessage()
 * @method getSeoMetaTitleErrorMessage()
 * @method getShippingInfoErrorMessage()
 * @method getStaggerClosingErrorMessage()
 * @method getStartRegisterDateErrorMessage()
 * @method getTaxDefaultCountryErrorMessage()
 * @method getTermsAndConditionsErrorMessage()
 * @method getUnpublishDateErrorMessage()
 * HasError Methods
 * @method hasAdditionalBpInternetError()
 * @method hasAuctionCatalogAccessError()
 * @method hasAuctionInfoAccessError()
 * @method hasAuctionTypeError()
 * @method hasAuctionVisibilityAccessError()
 * @method hasAuthorizationAmountError()
 * @method hasBiddingConsoleAccessDateError()
 * @method hasBpTaxSchemaIdError()
 * @method hasCcThresholdDomesticError()
 * @method hasCcThresholdInternationalError()
 * @method hasDefaultLotPeriodError()
 * @method hasDefaultLotPostalCodeError()
 * @method hasEmailError()
 * @method hasEndRegisterDateError()
 * @method hasExtendTimeError()
 * @method hasHpTaxSchemaIdError()
 * @method hasLiveEndDateError()
 * @method hasLiveViewAccessError()
 * @method hasLotBiddingHistoryAccessError()
 * @method hasLotBiddingInfoAccessError()
 * @method hasLotDetailsAccessError()
 * @method hasLotOrderPrimaryTypeError()
 * @method hasLotOrderQuaternaryTypeError()
 * @method hasLotOrderSecondaryTypeError()
 * @method hasLotOrderTertiaryTypeError()
 * @method hasLotStartGapTimeError()
 * @method hasLotStartingBidAccessError()
 * @method hasLotWinningBidAccessError()
 * @method hasLotsPerIntervalError()
 * @method hasMaxOutstandingError()
 * @method hasNameError()
 * @method hasPostAucImportPremiumError()
 * @method hasRtbdNameError()
 * @method hasSaleNumError()
 * @method hasSaleNumExtensionError()
 * @method hasStartBiddingDateError()
 * @method hasStartClosingDateError()
 * @method hasTaxPercentError()
 * @method hasTimezoneErrorMessage()
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
 * @method hasAuctionInfoLinkError()
 * @method hasDescriptionError()
 * @method hasEndPrebiddingDateError()
 * @method hasEventLocationError()
 * @method hasInvoiceLocationError()
 * @method hasInvoiceNotesError()
 * @method hasPublishDateError()
 * @method hasSeoMetaDescriptionError()
 * @method hasSeoMetaKeywordsError()
 * @method hasSeoMetaTitleError()
 * @method hasShippingInfoError()
 * @method hasStaggerClosingError()
 * @method hasStartRegisterDateError()
 * @method hasServicesTaxSchemaIdError()
 * @method hasTaxDefaultCountryError()
 * @method hasTermsAndConditionsError()
 * @method hasUnpublishDateError()
 *
 * @method AuctionMakerInputDto getInputDto()
 * @method AuctionMakerConfigDto getConfigDto()
 */
class AuctionMakerValidator extends BaseMakerValidator
{
    use AuctionAuctioneerExistenceCheckerAwareTrait;
    use AuctionFieldConfigProviderAwareTrait;
    use AuctionHelperAwareTrait;
    use AuctionInfoLinkValidationIntegratorCreateTrait;
    use AuctionLoaderAwareTrait;
    use AuctionMakerAccessCheckerAwareTrait;
    use AuctionMakerDtoHelperAwareTrait;
    use AuctionRtbdCheckerCreateTrait;
    use AuctionTaxSchemaValidationIntegratorCreateTrait;
    use BuyersPremiumCsvParserCreateTrait;
    use BuyersPremiumExistenceCheckerCreateTrait;
    use BuyersPremiumValidationIntegratorCreateTrait;
    use ConsignorCommissionFeeExistenceCheckerCreateTrait;
    use ConsignorCommissionFeeRangeDtoConverterCreateTrait;
    use ConsignorCommissionFeeRangesValidatorCreateTrait;
    use ConsignorCommissionFeeValidatorCreateTrait;
    use CurrencyExistenceCheckerAwareTrait;
    use DateValidationIntegratorCreateTrait;
    use LotCustomFieldExistenceCheckerCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use RtbdPoolFeatureAvailabilityValidatorAwareTrait;
    use SaleNoValidationIntegratorCreateTrait;
    use SettingsManagerAwareTrait;

    public const DELIMITER_EMAILS = ',';

    /** @var bool */
    protected bool $needValidateCustomFields = true;
    /** @var AdminPrivilegeChecker|null */
    protected ?AdminPrivilegeChecker $editorAdminPrivilegeChecker = null;
    /**
     * Effective field that has influence to decision-making
     * @var string
     */
    protected string $auctionType = '';

    /** @var string[] */
    protected array $tagNames = [
        ResultCode::ABSENTEE_BIDS_DISPLAY_UNKNOWN => 'AbsenteeBidsDisplay',
        ResultCode::ACCESS_DENIED => '',
        ResultCode::ADDITIONAL_BP_INTERNET_INVALID => 'AdditionalBpInternet',
        ResultCode::AUCTION_AUCTIONEER_ID_NOT_FOUND => 'AuctionAuctioneerId',
        ResultCode::AUCTION_CATALOG_ACCESS_UNKNOWN => 'AuctionCatalogAccess',
        ResultCode::AUCTION_HELD_IN_UNKNOWN => 'AuctionHeldIn',
        ResultCode::AUCTION_INFO_ACCESS_UNKNOWN => 'AuctionInfoAccess',
        ResultCode::AUCTION_STATUS_ID_UNKNOWN => 'AuctionStatusId',
        ResultCode::AUCTION_STATUS_UNKNOWN => 'AuctionStatus',
        ResultCode::AUCTION_TYPE_REQUIRED => 'AuctionType',
        ResultCode::AUCTION_TYPE_UNKNOWN => 'AuctionType',
        ResultCode::AUCTION_VISIBILITY_ACCESS_UNKNOWN => 'AuctionVisibilityAccess',
        ResultCode::AUTHORIZATION_AMOUNT_INVALID => 'AuthorizationAmount',
        ResultCode::BIDDING_CONSOLE_ACCESS_DATE_EARLIER_START_DATE => 'BiddingConsoleAccessDate',
        ResultCode::BIDDING_CONSOLE_ACCESS_DATE_INVALID => 'BiddingConsoleAccessDate',
        ResultCode::BIDDING_CONSOLE_ACCESS_DATE_REQUIRED => 'BiddingConsoleAccessDate',
        ResultCode::BP_RANGE_CALCULATION_UNKNOWN => 'BpRangeCalculation',
        ResultCode::BP_RULE_NOT_FOUND => 'BpRule',
        ResultCode::BUYERS_PREMIUMS_VALIDATION_FAILED => 'BuyersPremiums',
        ResultCode::BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER => 'BuyersPremiums',
        ResultCode::BP_TAX_SCHEMA_ID_HIDDEN => 'BpTaxSchemaId',
        ResultCode::BP_TAX_SCHEMA_ID_INVALID => 'BpTaxSchemaId',
        ResultCode::BP_TAX_SCHEMA_ID_REQUIRED => 'BpTaxSchemaId',
        ResultCode::BP_TAX_SCHEMA_COUNTRY_MISMATCH => 'BpTaxSchemaId',
        ResultCode::CC_THRESHOLD_DOMESTIC_INVALID => 'CcThresholdDomestic',
        ResultCode::CC_THRESHOLD_INTERNATIONAL_INVALID => 'CcThresholdInternational',
        ResultCode::CLERKING_STYLE_UNKNOWN => 'ClerkingStyle',
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
        ResultCode::CURRENCY_NOT_FOUND => 'Currency',
        ResultCode::DATE_ASSIGNMENT_STRATEGY_UNKNOWN => 'DateAssignmentStrategy',
        ResultCode::DEFAULT_LOT_PERIOD_INVALID => 'DefaultLotPeriod',
        ResultCode::DEFAULT_LOT_POSTAL_CODE_INVALID => 'DefaultLotPostalCode',
        ResultCode::EMAIL_INVALID => 'Email',
        ResultCode::END_PREBIDDING_DATE_INVALID => 'EndPrebiddingDate',
        ResultCode::END_REGISTER_DATE_EARLIER_START_REGISTER_DATE => 'EndRegisterDate',
        ResultCode::END_REGISTER_DATE_INVALID => 'EndRegisterDate',
        ResultCode::EVENT_LOCATION_NOT_FOUND => 'EventLocation',
        ResultCode::EVENT_TYPE_UNKNOWN => 'EventType',
        ResultCode::EXTEND_TIME_INVALID => 'ExtendTime',
        ResultCode::EXTEND_TIME_TOO_SMALL => 'ExtendTime',
        ResultCode::EXTEND_TIME_TOO_BIG => 'ExtendTime',
        ResultCode::EXTEND_TIME_REQUIRED => 'ExtendTime',
        ResultCode::HP_TAX_SCHEMA_ID_HIDDEN => 'HpTaxSchemaId',
        ResultCode::HP_TAX_SCHEMA_ID_INVALID => 'HpTaxSchemaId',
        ResultCode::HP_TAX_SCHEMA_ID_REQUIRED => 'HpTaxSchemaId',
        ResultCode::HP_TAX_SCHEMA_COUNTRY_MISMATCH => 'HpTaxSchemaId',
        ResultCode::INCREMENTS_AMOUNT_EXIST => 'Increments',
        ResultCode::INCREMENTS_INVALID_AMOUNT => 'Increments',
        ResultCode::INCREMENTS_INVALID_FORMAT => 'Increments',
        ResultCode::INCREMENTS_INVALID_RANGE => 'Increments',
        ResultCode::INVOICE_LOCATION_NOT_FOUND => 'InvoiceLocation',
        ResultCode::LIVE_END_DATE_EARLIER_START_CLOSING_DATE => 'LiveEndDate',
        ResultCode::LIVE_END_DATE_INVALID => 'LiveEndDate',
        ResultCode::LIVE_END_DATE_REQUIRED => 'LiveEndDate',
        ResultCode::LIVE_VIEW_ACCESS_UNKNOWN => 'LiveViewAccess',
        ResultCode::LOTS_PER_INTERVAL_INVALID => 'LotsPerInterval',
        ResultCode::LOTS_PER_INTERVAL_REQUIRED => 'LotsPerInterval',
        ResultCode::LOT_BIDDING_HISTORY_ACCESS_UNKNOWN => 'LotBiddingHistoryAccess',
        ResultCode::LOT_BIDDING_INFO_ACCESS_UNKNOWN => 'LotBiddingInfoAccess',
        ResultCode::LOT_DETAILS_ACCESS_UNKNOWN => 'LotDetailsAccess',
        ResultCode::LOT_ORDER_PRIMARY_CUSTOM_FIELD_ID_NOT_FOUND => 'LotOrderPrimaryCustFieldId',
        ResultCode::LOT_ORDER_PRIMARY_CUSTOM_FIELD_NOT_FOUND => 'LotOrderPrimaryCustField',
        ResultCode::LOT_ORDER_PRIMARY_CUSTOM_FIELD_REQUIRED => 'LotOrderPrimaryCustField',
        ResultCode::LOT_ORDER_PRIMARY_TYPE_NOT_UNIQUE => 'LotOrderPrimaryType',
        ResultCode::LOT_ORDER_PRIMARY_TYPE_UNKNOWN => 'LotOrderPrimaryType',
        ResultCode::LOT_ORDER_QUATERNARY_CUSTOM_FIELD_ID_NOT_FOUND => 'LotOrderQuaternaryCustFieldId',
        ResultCode::LOT_ORDER_QUATERNARY_CUSTOM_FIELD_NOT_FOUND => 'LotOrderQuaternaryCustField',
        ResultCode::LOT_ORDER_QUATERNARY_CUSTOM_FIELD_REQUIRED => 'LotOrderQuaternaryCustField',
        ResultCode::LOT_ORDER_QUATERNARY_TYPE_NOT_UNIQUE => 'LotOrderQuaternaryType',
        ResultCode::LOT_ORDER_QUATERNARY_TYPE_UNKNOWN => 'LotOrderQuaternaryType',
        ResultCode::LOT_ORDER_SECONDARY_CUSTOM_FIELD_ID_NOT_FOUND => 'LotOrderSecondaryCustFieldId',
        ResultCode::LOT_ORDER_SECONDARY_CUSTOM_FIELD_NOT_FOUND => 'LotOrderSecondaryCustField',
        ResultCode::LOT_ORDER_SECONDARY_CUSTOM_FIELD_REQUIRED => 'LotOrderSecondaryCustField',
        ResultCode::LOT_ORDER_SECONDARY_TYPE_NOT_UNIQUE => 'LotOrderSecondaryType',
        ResultCode::LOT_ORDER_SECONDARY_TYPE_UNKNOWN => 'LotOrderSecondaryType',
        ResultCode::LOT_ORDER_TERTIARY_CUSTOM_FIELD_ID_NOT_FOUND => 'LotOrderTertiaryCustFieldId',
        ResultCode::LOT_ORDER_TERTIARY_CUSTOM_FIELD_NOT_FOUND => 'LotOrderTertiaryCustField',
        ResultCode::LOT_ORDER_TERTIARY_CUSTOM_FIELD_REQUIRED => 'LotOrderTertiaryCustField',
        ResultCode::LOT_ORDER_TERTIARY_TYPE_NOT_UNIQUE => 'LotOrderTertiaryType',
        ResultCode::LOT_ORDER_TERTIARY_TYPE_UNKNOWN => 'LotOrderTertiaryType',
        ResultCode::LOT_STARTING_BID_ACCESS_UNKNOWN => 'LotStartingBidAccess',
        ResultCode::LOT_START_GAP_TIME_INVALID => 'LotStartGapTime',
        ResultCode::LOT_START_GAP_TIME_TOO_BIG => 'LotStartGapTime',
        ResultCode::LOT_START_GAP_TIME_TOO_SMALL => 'LotStartGapTime',
        ResultCode::LOT_WINNING_BID_ACCESS_UNKNOWN => 'LotWinningBidAccess',
        ResultCode::MAX_OUTSTANDING_INVALID => 'MaxOutstanding',
        ResultCode::NAME_INVALID => 'Name',
        ResultCode::NAME_REQUIRED => 'Name',
        ResultCode::NOTIFY_X_LOTS_INVALID => 'NotifyXLots',
        ResultCode::NOTIFY_X_MINUTES_INVALID => 'NotifyXMinutes',
        ResultCode::POST_AUC_IMPORT_PREMIUM_INVALID => 'PostAucImportPremium',
        ResultCode::PUBLISHED_DEPRECATED => 'Published',
        ResultCode::PUBLISH_DATE_INVALID => 'PublishDate',
        ResultCode::PUBLISH_DATE_MISSING_PRIVILEGE => 'PublishDate',
        ResultCode::RTBD_NAME_INCORRECT => 'RtbdName',
        ResultCode::SALE_FULL_NO_PARSE_ERROR => 'SaleNum',
        ResultCode::SALE_NO_EXIST => 'SaleNum',
        ResultCode::SALE_NUM_EXT_INVALID => 'SaleNumExt',
        ResultCode::SALE_NUM_EXT_NOT_ALPHA => 'SaleNumExt',
        ResultCode::SALE_NUM_HIGHER_MAX_AVAILABLE_VALUE => 'SaleNum',
        ResultCode::SALE_NUM_INVALID => 'SaleNum',
        ResultCode::SPECIFIC_EVENT_LOCATION_INVALID => 'SpecificEventLocation',
        ResultCode::SPECIFIC_EVENT_LOCATION_REDUNDANT => 'SpecificEventLocation',
        ResultCode::SPECIFIC_INVOICE_LOCATION_INVALID => 'SpecificInvoiceLocation',
        ResultCode::SPECIFIC_INVOICE_LOCATION_REDUNDANT => 'SpecificInvoiceLocation',
        ResultCode::STAGGER_CLOSING_UNKNOWN => 'StaggerClosing',
        ResultCode::START_BIDDING_DATE_DO_NOT_MATCH_ITEMS_DATE => 'StartBiddingDate',
        ResultCode::START_BIDDING_DATE_INVALID => 'StartBiddingDate',
        ResultCode::START_BIDDING_DATE_REQUIRED => 'StartBiddingDate',
        ResultCode::START_CLOSING_DATE_DO_NOT_MATCH_ITEMS_DATE => 'StartClosingDate',
        ResultCode::START_CLOSING_DATE_INVALID => 'StartClosingDate',
        ResultCode::START_CLOSING_DATE_REQUIRED => 'StartClosingDate',
        ResultCode::START_REGISTER_DATE_INVALID => 'StartRegisterDate',
        ResultCode::STREAM_DISPLAY_UNKNOWN => 'StreamDisplay',
        ResultCode::SYNC_KEY_EXIST => 'SyncKey',
        ResultCode::SERVICES_TAX_SCHEMA_ID_HIDDEN => 'ServicesTaxSchemaId',
        ResultCode::SERVICES_TAX_SCHEMA_ID_INVALID => 'ServicesTaxSchemaId',
        ResultCode::SERVICES_TAX_SCHEMA_ID_REQUIRED => 'ServicesTaxSchemaId',
        ResultCode::SERVICES_TAX_SCHEMA_COUNTRY_MISMATCH => 'ServicesTaxSchemaId',
        ResultCode::TAX_PERCENT_INVALID => 'TaxPercent',
        ResultCode::TAX_STATE_UNKNOWN => 'TaxState',
        ResultCode::TIMEZONE_REQUIRED => 'Timezone',
        ResultCode::TIMEZONE_UNKNOWN => 'Timezone',
        ResultCode::UNPUBLISH_DATE_INVALID => 'UnpublishDate',
        ResultCode::UNPUBLISH_DATE_MISSING_PRIVILEGE => 'UnpublishDate',
        ResultCode::CLERKING_STYLE_HIDDEN => 'ClerkingStyle',
        ResultCode::CLERKING_STYLE_ID_HIDDEN => 'ClerkingStyle',
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
        ResultCode::DATE_ASSIGNMENT_STRATEGY_HIDDEN => 'DateAssignmentStrategy',
        ResultCode::DESCRIPTION_HIDDEN => 'Description',
        ResultCode::END_PREBIDDING_DATE_HIDDEN => 'EndPrebiddingDate',
        ResultCode::END_REGISTER_DATE_HIDDEN => 'EndRegisterDate',
        ResultCode::EVENT_LOCATION_HIDDEN => 'EventLocation',
        ResultCode::EVENT_LOCATION_ID_HIDDEN => 'EventLocationId',
        ResultCode::EVENT_TYPE_HIDDEN => 'EventType',
        ResultCode::EVENT_TYPE_ID_HIDDEN => 'EventTypeId',
        ResultCode::EXCLUDE_CLOSED_LOTS_HIDDEN => 'ExcludeClosedLots',
        ResultCode::IMAGE_HIDDEN => 'Image',
        ResultCode::INVOICE_LOCATION_HIDDEN => 'InvoiceLocation',
        ResultCode::INVOICE_LOCATION_ID_HIDDEN => 'InvoiceLocationId',
        ResultCode::INVOICE_NOTES_HIDDEN => 'InvoiceNotes',
        ResultCode::LISTING_ONLY_HIDDEN => 'ListingOnly',
        ResultCode::ONLY_ONGOING_LOTS_HIDDEN => 'OnlyOngoingLots',
        ResultCode::NOT_SHOW_UPCOMING_LOTS_HIDDEN => 'NotShowUpcomingLots',
        ResultCode::HIDE_UNSOLD_LOTS_HIDDEN => 'HideUnsoldLots',
        ResultCode::PARCEL_CHOICE_HIDDEN => 'ParcelChoice',
        ResultCode::PUBLISH_DATE_HIDDEN => 'PublishDate',
        ResultCode::REVERSE_HIDDEN => 'Reverse',
        ResultCode::SALE_FULL_NO_HIDDEN => 'SaleFullNo',
        ResultCode::SALE_NUM_HIDDEN => 'SaleNum',
        ResultCode::SALE_NUM_EXT_HIDDEN => 'SaleNumExt',
        ResultCode::SEO_META_DESCRIPTION_HIDDEN => 'SeoMetaDescription',
        ResultCode::SEO_META_KEYWORDS_HIDDEN => 'SeoMetaKeywords',
        ResultCode::SEO_META_TITLE_HIDDEN => 'SeoMetaTitle',
        ResultCode::SHIPPING_INFO_HIDDEN => 'ShippingInfo',
        ResultCode::STAGGER_CLOSING_HIDDEN => 'StaggerClosing',
        ResultCode::LOTS_PER_INTERVAL_HIDDEN => 'LotsPerInterval',
        ResultCode::START_REGISTER_DATE_HIDDEN => 'StartRegisterDate',
        ResultCode::STREAM_DISPLAY_HIDDEN => 'StreamDisplay',
        ResultCode::STREAM_DISPLAY_VALUE_HIDDEN => 'StreamDisplayValue',
        ResultCode::TAX_PERCENT_HIDDEN => 'TaxPercent',
        ResultCode::TAX_DEFAULT_COUNTRY_HIDDEN => 'TaxDefaultCountry',
        ResultCode::TAX_DEFAULT_COUNTRY_UNKNOWN => 'TaxDefaultCountry',
        ResultCode::TERMS_AND_CONDITIONS_HIDDEN => 'TermsAndConditions',
        ResultCode::UNPUBLISH_DATE_HIDDEN => 'UnpublishDate',
        ResultCode::AUCTION_INFO_LINK_HIDDEN => 'AuctionInfoLink',
        ResultCode::AUCTION_INFO_LINK_REQUIRED => 'AuctionInfoLink',
        ResultCode::AUCTION_INFO_LINK_URL_INVALID => 'AuctionInfoLink',
        ResultCode::CLERKING_STYLE_REQUIRED => 'ClerkingStyle',
        ResultCode::CLERKING_STYLE_ID_REQUIRED => 'ClerkingStyle',
        ResultCode::DATE_ASSIGNMENT_STRATEGY_REQUIRED => 'DateAssignmentStrategy',
        ResultCode::DESCRIPTION_REQUIRED => 'Description',
        ResultCode::END_PREBIDDING_DATE_REQUIRED => 'EndPrebiddingDate',
        ResultCode::END_REGISTER_DATE_REQUIRED => 'EndRegisterDate',
        ResultCode::EVENT_LOCATION_REQUIRED => 'EventLocation',
        ResultCode::EVENT_LOCATION_ID_REQUIRED => 'EventLocationId',
        ResultCode::EVENT_TYPE_REQUIRED => 'EventType',
        ResultCode::EVENT_TYPE_ID_REQUIRED => 'EventTypeId',
        ResultCode::EXCLUDE_CLOSED_LOTS_REQUIRED => 'ExcludeClosedLots',
        ResultCode::IMAGE_REQUIRED => 'Image',
        ResultCode::INVOICE_LOCATION_REQUIRED => 'InvoiceLocation',
        ResultCode::INVOICE_LOCATION_ID_REQUIRED => 'InvoiceLocationId',
        ResultCode::INVOICE_NOTES_REQUIRED => 'InvoiceNotes',
        ResultCode::PUBLISH_DATE_REQUIRED => 'PublishDate',
        ResultCode::SALE_FULL_NO_REQUIRED => 'SaleFullNo',
        ResultCode::SALE_NUM_REQUIRED => 'SaleNum',
        ResultCode::SEO_META_DESCRIPTION_REQUIRED => 'SeoMetaDescription',
        ResultCode::SEO_META_KEYWORDS_REQUIRED => 'SeoMetaKeywords',
        ResultCode::SEO_META_TITLE_REQUIRED => 'SeoMetaTitle',
        ResultCode::SHIPPING_INFO_REQUIRED => 'ShippingInfo',
        ResultCode::STAGGER_CLOSING_REQUIRED => 'StaggerClosing',
        ResultCode::START_REGISTER_DATE_REQUIRED => 'StartRegisterDate',
        ResultCode::STREAM_DISPLAY_REQUIRED => 'StreamDisplay',
        ResultCode::STREAM_DISPLAY_VALUE_REQUIRED => 'StreamDisplayValue',
        ResultCode::TAX_PERCENT_REQUIRED => 'TaxPercent',
        ResultCode::TAX_DEFAULT_COUNTRY_REQUIRED => 'TaxDefaultCountry',
        ResultCode::TERMS_AND_CONDITIONS_REQUIRED => 'TermsAndConditions',
        ResultCode::UNPUBLISH_DATE_REQUIRED => 'UnpublishDate',
        ResultCode::CONSIGNOR_COMMISSION_REQUIRED => 'ConsignorCommissionId',
        ResultCode::CONSIGNOR_SOLD_FEE_REQUIRED => 'ConsignorSoldFeeId',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REQUIRED => 'ConsignorUnsoldFeeId',
    ];

    /** @var string[] */
    protected array $errorMessages = [
        ResultCode::ABSENTEE_BIDS_DISPLAY_UNKNOWN => 'Unknown',
        ResultCode::ACCESS_DENIED => 'Access denied to lot item',
        ResultCode::ADDITIONAL_BP_INTERNET_INVALID => 'Invalid',
        ResultCode::AUCTION_AUCTIONEER_ID_NOT_FOUND => 'Incorrect',
        ResultCode::AUCTION_CATALOG_ACCESS_UNKNOWN => 'Unknown',
        ResultCode::AUCTION_HELD_IN_UNKNOWN => 'Unknown',
        ResultCode::AUCTION_INFO_ACCESS_UNKNOWN => 'Unknown',
        ResultCode::AUCTION_INFO_LINK_HIDDEN => 'Hidden',
        ResultCode::AUCTION_INFO_LINK_REQUIRED => 'Required',
        ResultCode::AUCTION_INFO_LINK_URL_INVALID => 'Url should has https:// or http:// scheme, in case if its an absolute url, or starts with "//" or "/", in case if its an relative url path.',
        ResultCode::AUCTION_STATUS_ID_UNKNOWN => 'Unknown',
        ResultCode::AUCTION_STATUS_UNKNOWN => 'Unknown',
        ResultCode::AUCTION_TYPE_REQUIRED => 'Required',
        ResultCode::AUCTION_TYPE_UNKNOWN => 'Unknown',
        ResultCode::AUCTION_VISIBILITY_ACCESS_UNKNOWN => 'Unknown',
        ResultCode::AUTHORIZATION_AMOUNT_INVALID => 'Should be numeric',
        ResultCode::BIDDING_CONSOLE_ACCESS_DATE_EARLIER_START_DATE => 'Should be earlier than Start closing date',
        ResultCode::BIDDING_CONSOLE_ACCESS_DATE_INVALID => 'Invalid',
        ResultCode::BIDDING_CONSOLE_ACCESS_DATE_REQUIRED => 'Required',
        ResultCode::BP_RANGE_CALCULATION_UNKNOWN => 'Unknown',
        ResultCode::BP_RULE_NOT_FOUND => 'Not found',
        ResultCode::BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER => 'Named rule can\'t be assigned together with individual ranges or additional BP internet',
        ResultCode::BP_TAX_SCHEMA_ID_HIDDEN => 'Hidden',
        ResultCode::BP_TAX_SCHEMA_ID_INVALID => 'Invalid',
        ResultCode::BP_TAX_SCHEMA_ID_REQUIRED => 'Required',
        ResultCode::BP_TAX_SCHEMA_COUNTRY_MISMATCH => 'Country mismatch',
        ResultCode::BUYERS_PREMIUMS_VALIDATION_FAILED => 'Validation failed',
        ResultCode::CC_THRESHOLD_DOMESTIC_INVALID => 'Should be numeric',
        ResultCode::CC_THRESHOLD_INTERNATIONAL_INVALID => 'Should be numeric',
        ResultCode::CLERKING_STYLE_HIDDEN => 'Hidden',
        ResultCode::CLERKING_STYLE_ID_HIDDEN => 'Hidden',
        ResultCode::CLERKING_STYLE_ID_REQUIRED => 'Required',
        ResultCode::CLERKING_STYLE_REQUIRED => 'Required',
        ResultCode::CLERKING_STYLE_UNKNOWN => 'Unknown',
        ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_COMMISSION_ID_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_COMMISSION_ID_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_COMMISSION_RANGES_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID => 'Invalid ranges',
        ResultCode::CONSIGNOR_COMMISSION_REQUIRED => 'Required',
        ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_SOLD_FEE_ID_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_SOLD_FEE_RANGES_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID => 'Invalid ranges',
        ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_SOLD_FEE_REQUIRED => 'Required',
        ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_UNSOLD_FEE_ID_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_UNSOLD_FEE_RANGES_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID => 'Invalid ranges',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REQUIRED => 'Required',
        ResultCode::CURRENCY_NOT_FOUND => 'Not found',
        ResultCode::DATE_ASSIGNMENT_STRATEGY_HIDDEN => 'Hidden',
        ResultCode::DATE_ASSIGNMENT_STRATEGY_REQUIRED => 'Required',
        ResultCode::DATE_ASSIGNMENT_STRATEGY_UNKNOWN => 'Unknown',
        ResultCode::DEFAULT_LOT_PERIOD_INVALID => 'Should be integer',
        ResultCode::DEFAULT_LOT_POSTAL_CODE_INVALID => 'Should be 5 digits',
        ResultCode::DESCRIPTION_HIDDEN => 'Hidden',
        ResultCode::DESCRIPTION_REQUIRED => 'Required',
        ResultCode::EMAIL_INVALID => 'Invalid',
        ResultCode::END_PREBIDDING_DATE_HIDDEN => 'Hidden',
        ResultCode::END_PREBIDDING_DATE_INVALID => 'Invalid',
        ResultCode::END_PREBIDDING_DATE_REQUIRED => 'Required',
        ResultCode::END_REGISTER_DATE_EARLIER_START_REGISTER_DATE => 'Should be later than Start register date',
        ResultCode::END_REGISTER_DATE_HIDDEN => 'Hidden',
        ResultCode::END_REGISTER_DATE_INVALID => 'Invalid',
        ResultCode::END_REGISTER_DATE_REQUIRED => 'Required',
        ResultCode::EVENT_LOCATION_HIDDEN => 'Hidden',
        ResultCode::EVENT_LOCATION_ID_HIDDEN => 'Hidden',
        ResultCode::EVENT_LOCATION_ID_REQUIRED => 'Required',
        ResultCode::EVENT_LOCATION_NOT_FOUND => 'Not found',
        ResultCode::EVENT_LOCATION_REQUIRED => 'Required',
        ResultCode::EVENT_TYPE_HIDDEN => 'Hidden',
        ResultCode::EVENT_TYPE_ID_HIDDEN => 'Hidden',
        ResultCode::EVENT_TYPE_ID_REQUIRED => 'Required',
        ResultCode::EVENT_TYPE_REQUIRED => 'Required',
        ResultCode::EVENT_TYPE_UNKNOWN => 'Unknown',
        ResultCode::EXCLUDE_CLOSED_LOTS_HIDDEN => 'Hidden',
        ResultCode::EXCLUDE_CLOSED_LOTS_REQUIRED => 'Required',
        ResultCode::EXTEND_TIME_INVALID => 'Should be positive integer',
        ResultCode::EXTEND_TIME_TOO_SMALL => 'Too small',
        ResultCode::EXTEND_TIME_TOO_BIG => 'Too big',
        ResultCode::EXTEND_TIME_REQUIRED => 'Required',
        ResultCode::HP_TAX_SCHEMA_ID_HIDDEN => 'Hidden',
        ResultCode::HP_TAX_SCHEMA_ID_INVALID => 'Invalid',
        ResultCode::HP_TAX_SCHEMA_ID_REQUIRED => 'Required',
        ResultCode::HP_TAX_SCHEMA_COUNTRY_MISMATCH => 'Country mismatch',
        ResultCode::IMAGE_HIDDEN => 'Hidden',
        ResultCode::IMAGE_REQUIRED => 'Required',
        ResultCode::INCREMENTS_AMOUNT_EXIST => 'Start range already exist',
        ResultCode::INCREMENTS_INVALID_AMOUNT => 'Amount should be > 0',
        ResultCode::INCREMENTS_INVALID_FORMAT => 'Format error',
        ResultCode::INCREMENTS_INVALID_RANGE => 'First range should be 0',
        ResultCode::INVOICE_LOCATION_HIDDEN => 'Hidden',
        ResultCode::INVOICE_LOCATION_ID_HIDDEN => 'Hidden',
        ResultCode::INVOICE_LOCATION_ID_REQUIRED => 'Required',
        ResultCode::INVOICE_LOCATION_NOT_FOUND => 'Not found',
        ResultCode::INVOICE_LOCATION_REQUIRED => 'Required',
        ResultCode::INVOICE_NOTES_HIDDEN => 'Hidden',
        ResultCode::INVOICE_NOTES_REQUIRED => 'Required',
        ResultCode::LISTING_ONLY_HIDDEN => 'Hidden',
        ResultCode::LIVE_END_DATE_EARLIER_START_CLOSING_DATE => 'Should be later than start closing date',
        ResultCode::LIVE_END_DATE_INVALID => 'Invalid',
        ResultCode::LIVE_END_DATE_REQUIRED => 'Required',
        ResultCode::LIVE_VIEW_ACCESS_UNKNOWN => 'Unknown',
        ResultCode::LOTS_PER_INTERVAL_HIDDEN => 'Hidden',
        ResultCode::LOTS_PER_INTERVAL_INVALID => 'Should be integer and greater than 0',
        ResultCode::LOTS_PER_INTERVAL_REQUIRED => 'Required',
        ResultCode::LOT_BIDDING_HISTORY_ACCESS_UNKNOWN => 'Unknown',
        ResultCode::LOT_BIDDING_INFO_ACCESS_UNKNOWN => 'Unknown',
        ResultCode::LOT_DETAILS_ACCESS_UNKNOWN => 'Unknown',
        ResultCode::LOT_ORDER_PRIMARY_CUSTOM_FIELD_ID_NOT_FOUND => 'Not found',
        ResultCode::LOT_ORDER_PRIMARY_CUSTOM_FIELD_NOT_FOUND => 'Not found',
        ResultCode::LOT_ORDER_PRIMARY_CUSTOM_FIELD_REQUIRED => 'Required',
        ResultCode::LOT_ORDER_PRIMARY_TYPE_NOT_UNIQUE => 'Not unique',
        ResultCode::LOT_ORDER_PRIMARY_TYPE_UNKNOWN => 'Unknown',
        ResultCode::LOT_ORDER_QUATERNARY_CUSTOM_FIELD_ID_NOT_FOUND => 'Not found',
        ResultCode::LOT_ORDER_QUATERNARY_CUSTOM_FIELD_NOT_FOUND => 'Not found',
        ResultCode::LOT_ORDER_QUATERNARY_CUSTOM_FIELD_REQUIRED => 'Required',
        ResultCode::LOT_ORDER_QUATERNARY_TYPE_NOT_UNIQUE => 'Not unique',
        ResultCode::LOT_ORDER_QUATERNARY_TYPE_UNKNOWN => 'Unknown',
        ResultCode::LOT_ORDER_SECONDARY_CUSTOM_FIELD_ID_NOT_FOUND => 'Not found',
        ResultCode::LOT_ORDER_SECONDARY_CUSTOM_FIELD_NOT_FOUND => 'Not found',
        ResultCode::LOT_ORDER_SECONDARY_CUSTOM_FIELD_REQUIRED => 'Required',
        ResultCode::LOT_ORDER_SECONDARY_TYPE_NOT_UNIQUE => 'Not unique',
        ResultCode::LOT_ORDER_SECONDARY_TYPE_UNKNOWN => 'Unknown',
        ResultCode::LOT_ORDER_TERTIARY_CUSTOM_FIELD_ID_NOT_FOUND => 'Not found',
        ResultCode::LOT_ORDER_TERTIARY_CUSTOM_FIELD_NOT_FOUND => 'Not found',
        ResultCode::LOT_ORDER_TERTIARY_CUSTOM_FIELD_REQUIRED => 'Required',
        ResultCode::LOT_ORDER_TERTIARY_TYPE_NOT_UNIQUE => 'Not unique',
        ResultCode::LOT_ORDER_TERTIARY_TYPE_UNKNOWN => 'Unknown',
        ResultCode::LOT_STARTING_BID_ACCESS_UNKNOWN => 'Unknown',
        ResultCode::LOT_START_GAP_TIME_INVALID => 'Should be positive integer',
        ResultCode::LOT_START_GAP_TIME_TOO_BIG => 'Too big',
        ResultCode::LOT_START_GAP_TIME_TOO_SMALL => 'Too small',
        ResultCode::LOT_WINNING_BID_ACCESS_UNKNOWN => 'Unknown',
        ResultCode::MAX_OUTSTANDING_INVALID => 'Invalid',
        ResultCode::NAME_INVALID => 'Has not allowed html tags',
        ResultCode::NAME_REQUIRED => 'Required',
        ResultCode::NOTIFY_X_LOTS_INVALID => 'Should be numeric',
        ResultCode::NOTIFY_X_MINUTES_INVALID => 'Should be numeric',
        ResultCode::NOT_SHOW_UPCOMING_LOTS_HIDDEN => 'Hidden',
        ResultCode::HIDE_UNSOLD_LOTS_HIDDEN => 'Hidden',
        ResultCode::ONLY_ONGOING_LOTS_HIDDEN => 'Hidden',
        ResultCode::PARCEL_CHOICE_HIDDEN => 'Hidden',
        ResultCode::POST_AUC_IMPORT_PREMIUM_INVALID => 'Should be numeric',
        ResultCode::PUBLISHED_DEPRECATED => 'Deprecated. Use only publish date',
        ResultCode::PUBLISH_DATE_HIDDEN => 'Hidden',
        ResultCode::PUBLISH_DATE_INVALID => 'Invalid',
        ResultCode::PUBLISH_DATE_MISSING_PRIVILEGE => 'You are not allowed to edit this field',
        ResultCode::PUBLISH_DATE_REQUIRED => 'Required',
        ResultCode::REVERSE_HIDDEN => 'Hidden',
        ResultCode::RTBD_NAME_INCORRECT => 'Incorrect',
        ResultCode::SALE_FULL_NO_HIDDEN => 'Hidden',
        ResultCode::SALE_FULL_NO_PARSE_ERROR => 'Parse error',
        ResultCode::SALE_FULL_NO_REQUIRED => 'Required',
        ResultCode::SALE_NO_EXIST => 'Already exists',
        ResultCode::SALE_NUM_EXT_HIDDEN => 'Hidden',
        ResultCode::SALE_NUM_EXT_INVALID => 'Max limit exceeds',
        ResultCode::SALE_NUM_EXT_NOT_ALPHA => 'Should be letters',
        ResultCode::SALE_NUM_HIDDEN => 'Hidden',
        ResultCode::SALE_NUM_HIGHER_MAX_AVAILABLE_VALUE => 'Higher than the max available mysql value',
        ResultCode::SALE_NUM_INVALID => 'Should be integer',
        ResultCode::SALE_NUM_REQUIRED => 'Required',
        ResultCode::SEO_META_DESCRIPTION_HIDDEN => 'Hidden',
        ResultCode::SEO_META_DESCRIPTION_REQUIRED => 'Required',
        ResultCode::SEO_META_KEYWORDS_HIDDEN => 'Hidden',
        ResultCode::SEO_META_KEYWORDS_REQUIRED => 'Required',
        ResultCode::SEO_META_TITLE_HIDDEN => 'Hidden',
        ResultCode::SEO_META_TITLE_REQUIRED => 'Required',
        ResultCode::SHIPPING_INFO_HIDDEN => 'Hidden',
        ResultCode::SHIPPING_INFO_REQUIRED => 'Required',
        ResultCode::SPECIFIC_EVENT_LOCATION_INVALID => 'Invalid',
        ResultCode::SPECIFIC_EVENT_LOCATION_REDUNDANT => 'Both specific and common event locations are provided',
        ResultCode::SPECIFIC_INVOICE_LOCATION_INVALID => 'Invalid',
        ResultCode::SPECIFIC_INVOICE_LOCATION_REDUNDANT => 'Both specific and common invoice locations are provided',
        ResultCode::STAGGER_CLOSING_HIDDEN => 'Hidden',
        ResultCode::STAGGER_CLOSING_REQUIRED => 'Required',
        ResultCode::STAGGER_CLOSING_UNKNOWN => 'Unknown',
        ResultCode::START_BIDDING_DATE_DO_NOT_MATCH_ITEMS_DATE => 'Auction\'s and items start bidding date don\'t match. Please select another option',
        ResultCode::START_BIDDING_DATE_INVALID => 'Invalid',
        ResultCode::START_BIDDING_DATE_REQUIRED => 'Required',
        ResultCode::START_CLOSING_DATE_DO_NOT_MATCH_ITEMS_DATE => 'Auction\'s and items start closing date don\'t match. Please select another option',
        ResultCode::START_CLOSING_DATE_INVALID => 'Invalid',
        ResultCode::START_CLOSING_DATE_REQUIRED => 'Required',
        ResultCode::START_REGISTER_DATE_HIDDEN => 'Hidden',
        ResultCode::START_REGISTER_DATE_INVALID => 'Invalid',
        ResultCode::START_REGISTER_DATE_REQUIRED => 'Required',
        ResultCode::STREAM_DISPLAY_HIDDEN => 'Hidden',
        ResultCode::STREAM_DISPLAY_REQUIRED => 'Required',
        ResultCode::STREAM_DISPLAY_UNKNOWN => 'Unknown',
        ResultCode::STREAM_DISPLAY_VALUE_HIDDEN => 'Hidden',
        ResultCode::STREAM_DISPLAY_VALUE_REQUIRED => 'Required',
        ResultCode::SYNC_KEY_EXIST => 'Already exists',
        ResultCode::SERVICES_TAX_SCHEMA_ID_HIDDEN => 'Hidden',
        ResultCode::SERVICES_TAX_SCHEMA_ID_INVALID => 'Invalid',
        ResultCode::SERVICES_TAX_SCHEMA_ID_REQUIRED => 'Required',
        ResultCode::SERVICES_TAX_SCHEMA_COUNTRY_MISMATCH => 'Country mismatch',
        ResultCode::TAX_DEFAULT_COUNTRY_HIDDEN => 'Hidden',
        ResultCode::TAX_DEFAULT_COUNTRY_REQUIRED => 'Required',
        ResultCode::TAX_DEFAULT_COUNTRY_UNKNOWN => 'Unknown',
        ResultCode::TAX_PERCENT_HIDDEN => 'Hidden',
        ResultCode::TAX_PERCENT_INVALID => 'Invalid',
        ResultCode::TAX_PERCENT_REQUIRED => 'Required',
        ResultCode::TAX_STATE_UNKNOWN => 'Unknown',
        ResultCode::TERMS_AND_CONDITIONS_HIDDEN => 'Hidden',
        ResultCode::TERMS_AND_CONDITIONS_REQUIRED => 'Required',
        ResultCode::TIMEZONE_REQUIRED => 'Required',
        ResultCode::TIMEZONE_UNKNOWN => 'Unknown',
        ResultCode::UNPUBLISH_DATE_HIDDEN => 'Hidden',
        ResultCode::UNPUBLISH_DATE_INVALID => 'Invalid',
        ResultCode::UNPUBLISH_DATE_MISSING_PRIVILEGE => 'You are not allowed to edit this field',
        ResultCode::UNPUBLISH_DATE_REQUIRED => 'Required',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionMakerInputDto $inputDto
     * @param AuctionMakerConfigDto $configDto
     * @return static
     */
    public function construct(
        AuctionMakerInputDto $inputDto,
        AuctionMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->customFieldManager = AuctionMakerCustomFieldManager::new()->construct($inputDto, $configDto);
        $this->getAuctionMakerDtoHelper()->constructAuctionMakerDtoHelper($configDto->mode, $this->customFieldManager);
        $this->getAuctionMakerAccessChecker()->construct($inputDto, $configDto);
        return $this;
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function enableCustomFieldValidation(bool $enabled): static
    {
        $this->needValidateCustomFields = $enabled;
        return $this;
    }

    /**
     * Validate data
     * @return bool
     */
    public function validate(): bool
    {
        $inputDto = $this->getAuctionMakerDtoHelper()->prepareValues($this->getInputDto(), $this->getConfigDto());
        $this->setInputDto($inputDto);
        $this->initEffectiveFields();

        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        if (!$this->auctionMakerAccessChecker->canEdit()) {
            $this->addError(ResultCode::ACCESS_DENIED);
            return false;
        }

        if (!$configDto->mode->isWebAdmin()) {
            $this->addTagNamesToErrorMessages();
        }

        $this->validateVisibility();

        $this->checkRequiredWithFieldConfig('auctionInfoLink', ResultCode::AUCTION_INFO_LINK_REQUIRED);
        $this->checkRequiredWithFieldConfig('bpTaxSchemaId', ResultCode::BP_TAX_SCHEMA_ID_REQUIRED);
        if (isset($inputDto->clerkingStyleId)) {
            $this->checkRequiredWithFieldConfig('clerkingStyleId', ResultCode::CLERKING_STYLE_ID_REQUIRED);
        } else {
            $this->checkRequiredWithFieldConfig('clerkingStyle', ResultCode::CLERKING_STYLE_REQUIRED);
        }
        $this->checkRequiredWithFieldConfig('dateAssignmentStrategy', ResultCode::DATE_ASSIGNMENT_STRATEGY_REQUIRED);
        $this->checkRequiredWithFieldConfig('description', ResultCode::DESCRIPTION_REQUIRED);
        if (AuctionStatusPureChecker::new()->isLiveOrHybrid($this->auctionType)) {
            $this->checkRequiredWithFieldConfig('endPrebiddingDate', ResultCode::END_PREBIDDING_DATE_REQUIRED);
        }
        $this->checkRequiredWithFieldConfig('endRegisterDate', ResultCode::END_REGISTER_DATE_REQUIRED);
        if (isset($inputDto->eventLocationId)) {
            $this->checkRequiredWithFieldConfig('eventLocationId', ResultCode::EVENT_LOCATION_ID_REQUIRED);
        } else {
            $this->checkRequiredWithFieldConfig('eventLocation', ResultCode::EVENT_LOCATION_REQUIRED);
        }
        if (isset($inputDto->eventTypeId)) {
            $this->checkRequiredZeroAllowedWithFieldConfig('eventTypeId', ResultCode::EVENT_TYPE_ID_REQUIRED);
        } else {
            $this->checkRequiredWithFieldConfig('eventType', ResultCode::EVENT_TYPE_REQUIRED);
        }
        $this->checkRequiredWithFieldConfig('excludeClosedLots', ResultCode::EXCLUDE_CLOSED_LOTS_REQUIRED);
        $this->checkRequiredWithFieldConfig('hpTaxSchemaId', ResultCode::HP_TAX_SCHEMA_ID_REQUIRED);
        $this->checkRequiredWithFieldConfig('servicesTaxSchemaId', ResultCode::SERVICES_TAX_SCHEMA_ID_REQUIRED);
        if (!$configDto->mode->isWebAdmin()) {
            $this->checkRequiredWithFieldConfig('image', ResultCode::IMAGE_REQUIRED);
        }
        if (isset($inputDto->invoiceLocationId)) {
            $this->checkRequiredWithFieldConfig('invoiceLocationId', ResultCode::INVOICE_LOCATION_ID_REQUIRED);
        } else {
            $this->checkRequiredWithFieldConfig('invoiceLocation', ResultCode::INVOICE_LOCATION_REQUIRED);
        }
        $this->checkRequiredWithFieldConfig('invoiceNotes', ResultCode::INVOICE_NOTES_REQUIRED);
        $this->checkRequiredWithFieldConfig('name', ResultCode::NAME_REQUIRED);
        $this->checkRequiredWithFieldConfig('publishDate', ResultCode::PUBLISH_DATE_REQUIRED);
        if ($this->cfg()->get('core->auction->saleNo->concatenated')) {
            $this->checkRequiredWithFieldConfig('saleFullNo', ResultCode::SALE_FULL_NO_REQUIRED);
        } else {
            $this->checkRequiredWithFieldConfig('saleNum', ResultCode::SALE_NUM_REQUIRED);
        }
        $this->checkRequiredWithFieldConfig('seoMetaDescription', ResultCode::SEO_META_DESCRIPTION_REQUIRED);
        $this->checkRequiredWithFieldConfig('seoMetaKeywords', ResultCode::SEO_META_KEYWORDS_REQUIRED);
        $this->checkRequiredWithFieldConfig('seoMetaTitle', ResultCode::SEO_META_TITLE_REQUIRED);
        $this->checkRequiredWithFieldConfig('shippingInfo', ResultCode::SHIPPING_INFO_REQUIRED);
        if (AuctionStatusPureChecker::new()->isTimed($this->auctionType)) {
            $this->checkRequiredWithFieldConfig('staggerClosing', ResultCode::STAGGER_CLOSING_REQUIRED);
        }
        $this->checkRequiredWithFieldConfig('startRegisterDate', ResultCode::START_REGISTER_DATE_REQUIRED);
        $this->checkRequiredWithFieldConfig('streamDisplay', ResultCode::STREAM_DISPLAY_REQUIRED);
        $this->checkRequiredWithFieldConfig('streamDisplayValue', ResultCode::STREAM_DISPLAY_VALUE_REQUIRED);
        $this->checkRequiredWithFieldConfig('taxPercent', ResultCode::TAX_PERCENT_REQUIRED);
        $this->checkRequiredWithFieldConfig('taxDefaultCountry', ResultCode::TAX_DEFAULT_COUNTRY_REQUIRED);
        $this->checkRequiredWithFieldConfig('termsAndConditions', ResultCode::TERMS_AND_CONDITIONS_REQUIRED);
        $this->checkRequiredWithFieldConfig('unpublishDate', ResultCode::UNPUBLISH_DATE_REQUIRED);

        $this->validateAuctionInfoLink();

        // SaleFullNo parsing should be before saleNo, saleNoExt checking
        $this->validateSaleNo();
        $this->validateEmail();
        $this->validateLotOrders();
        $this->validateRtbdName();

        $accessRoles = Constants\Role::$auctionAccessRoles;
        $auctionTypes = $this->getAuctionHelper()->getAvailableTypes($configDto->serviceAccountId);
        $lotOrderTypes = Constants\Auction::$lotOrderTypes;
        $lotOrderTypesPrimary = Constants\Auction::$lotOrderPrimaryTypes;
        $rangeCalculationNames = Constants\BuyersPremium::$rangeCalculationNames;
        $staggerClosingIntervalNames = Constants\Date::$staggerClosingIntervalNames;
        $streamDisplayNames = $this->getAuctionHelper()->getStreamDisplayNames();
        $streamDisplayValues = $this->getAuctionHelper()->getStreamDisplayValues();
        $absenteeBidValues = Constants\SettingAuction::ABSENTEE_BID_DISPLAY_SOAP_VALUES;
        $dateAssignmentStrategies = Constants\Auction::$dateAssignmentStrategies;

        $this->validateAdditionalCurrencies();
        if ($this->needValidateCustomFields) {
            $this->validateCustomFields();
        }

        $this->checkInArray('auctionCatalogAccess', ResultCode::AUCTION_CATALOG_ACCESS_UNKNOWN, $accessRoles);
        $this->checkInArray('auctionInfoAccess', ResultCode::AUCTION_INFO_ACCESS_UNKNOWN, $accessRoles);
        $this->checkInArray('auctionVisibilityAccess', ResultCode::AUCTION_VISIBILITY_ACCESS_UNKNOWN, $accessRoles);
        $this->checkInArray('liveViewAccess', ResultCode::LIVE_VIEW_ACCESS_UNKNOWN, $accessRoles);
        $this->checkInArray('lotBiddingHistoryAccess', ResultCode::LOT_BIDDING_HISTORY_ACCESS_UNKNOWN, $accessRoles);
        $this->checkInArray('lotBiddingInfoAccess', ResultCode::LOT_BIDDING_INFO_ACCESS_UNKNOWN, $accessRoles);
        $this->checkInArray('lotDetailsAccess', ResultCode::LOT_DETAILS_ACCESS_UNKNOWN, $accessRoles);
        $this->checkInArray('lotStartingBidAccess', ResultCode::LOT_STARTING_BID_ACCESS_UNKNOWN, $accessRoles);
        $this->checkInArray('lotWinningBidAccess', ResultCode::LOT_WINNING_BID_ACCESS_UNKNOWN, $accessRoles);
        $this->checkInArray('auctionStatus', ResultCode::AUCTION_STATUS_UNKNOWN, Constants\Auction::$auctionStatusNames);
        $this->checkInArray('auctionStatusId', ResultCode::AUCTION_STATUS_ID_UNKNOWN, Constants\Auction::$auctionStatuses);
        $this->checkInArray('auctionType', ResultCode::AUCTION_TYPE_UNKNOWN, $auctionTypes);
        $this->checkInArray('clerkingStyle', ResultCode::CLERKING_STYLE_UNKNOWN, Constants\Auction::$clerkingStyleNames);
        $this->checkInArrayKeys('dateAssignmentStrategy', ResultCode::DATE_ASSIGNMENT_STRATEGY_UNKNOWN, $dateAssignmentStrategies);
        $this->checkInArrayKeys('bpRangeCalculation', ResultCode::BP_RANGE_CALCULATION_UNKNOWN, $rangeCalculationNames);
        $this->checkExistAuctionAuctioneerId('auctionAuctioneerId', ResultCode::AUCTION_AUCTIONEER_ID_NOT_FOUND);
        $this->checkExistBuyersPremiumShortName('bpRule', ResultCode::BP_RULE_NOT_FOUND);
        $this->checkExistAuctionCurrency('currency', ResultCode::CURRENCY_NOT_FOUND);
        $this->checkExistLocationId('invoiceLocationId', ResultCode::INVOICE_LOCATION_NOT_FOUND);
        $this->checkExistLocationName('invoiceLocation', ResultCode::INVOICE_LOCATION_NOT_FOUND);
        $this->checkExistLocationId('eventLocationId', ResultCode::EVENT_LOCATION_NOT_FOUND);
        $this->checkExistLocationName('eventLocation', ResultCode::EVENT_LOCATION_NOT_FOUND);
        $this->checkCountry('auctionHeldIn', ResultCode::AUCTION_HELD_IN_UNKNOWN);
        $this->checkCountry('taxDefaultCountry', ResultCode::TAX_DEFAULT_COUNTRY_UNKNOWN);
        $countryCode = AddressRenderer::new()->normalizeCountry($inputDto->taxDefaultCountry);
        if (AddressChecker::new()->isCountryWithStates($countryCode)) {
            $this->checkStates('taxDefaultCountry', 'taxStates', ResultCode::TAX_STATE_UNKNOWN);
        }

        //TODO: IM: Remove deprecated
        $this->checkExistLocationId('locationId', ResultCode::INVOICE_LOCATION_NOT_FOUND);
        $this->checkExistLocationName('location', ResultCode::INVOICE_LOCATION_NOT_FOUND);

        $this->checkIncrements(
            'increments',
            [
                'amount-exist' => ResultCode::INCREMENTS_AMOUNT_EXIST,
                'invalid-amount' => ResultCode::INCREMENTS_INVALID_AMOUNT,
                'invalid-format' => ResultCode::INCREMENTS_INVALID_FORMAT,
                'invalid-range' => ResultCode::INCREMENTS_INVALID_RANGE,
            ]
        );
        $this->validateBuyersPremiums();
        $this->checkPostalCode('defaultLotPostalCode', ResultCode::DEFAULT_LOT_POSTAL_CODE_INVALID);
        $this->checkReal('additionalBpInternet', ResultCode::ADDITIONAL_BP_INTERNET_INVALID, true);
        $this->checkReal('authorizationAmount', ResultCode::AUTHORIZATION_AMOUNT_INVALID, true);
        $this->checkReal('ccThresholdDomestic', ResultCode::CC_THRESHOLD_DOMESTIC_INVALID, true);
        $this->checkReal('ccThresholdInternational', ResultCode::CC_THRESHOLD_INTERNATIONAL_INVALID, true);
        $this->checkReal('maxOutstanding', ResultCode::MAX_OUTSTANDING_INVALID, true);
        $this->checkAllowedHtmlTags('name', ResultCode::NAME_INVALID);
        $this->checkReal('postAucImportPremium', ResultCode::POST_AUC_IMPORT_PREMIUM_INVALID, true);
        $this->checkReal('taxPercent', ResultCode::TAX_PERCENT_INVALID, true);
        $this->checkTimezone('timezone', ResultCode::TIMEZONE_UNKNOWN);
        $this->checkIntPositive('extendTime', ResultCode::EXTEND_TIME_INVALID);
        $this->checkInArray('lotOrderPrimaryType', ResultCode::LOT_ORDER_PRIMARY_TYPE_UNKNOWN, $lotOrderTypesPrimary);
        $this->checkInArray('lotOrderSecondaryType', ResultCode::LOT_ORDER_SECONDARY_TYPE_UNKNOWN, $lotOrderTypes);
        $this->checkInArray('lotOrderTertiaryType', ResultCode::LOT_ORDER_TERTIARY_TYPE_UNKNOWN, $lotOrderTypes);
        $this->checkInArray('lotOrderQuaternaryType', ResultCode::LOT_ORDER_QUATERNARY_TYPE_UNKNOWN, $lotOrderTypes);
        $this->checkNumeric('lotOrderPrimaryType', ResultCode::LOT_ORDER_PRIMARY_TYPE_UNKNOWN);
        $this->checkNumeric('lotOrderSecondaryType', ResultCode::LOT_ORDER_SECONDARY_TYPE_UNKNOWN);
        $this->checkNumeric('lotOrderTertiaryType', ResultCode::LOT_ORDER_TERTIARY_TYPE_UNKNOWN);
        $this->checkNumeric('lotOrderQuaternaryType', ResultCode::LOT_ORDER_QUATERNARY_TYPE_UNKNOWN);
        if ((int)$inputDto->lotOrderPrimaryType === Constants\Auction::LOT_ORDER_BY_CUST_FIELD) {
            $this->checkAreRequired(['lotOrderPrimaryCustField', 'lotOrderPrimaryCustFieldId'], ResultCode::LOT_ORDER_PRIMARY_CUSTOM_FIELD_REQUIRED);
            $this->checkExistAuctionLotOrderCustField('lotOrderPrimaryCustField', ResultCode::LOT_ORDER_PRIMARY_CUSTOM_FIELD_NOT_FOUND);
            $this->checkExistAuctionLotOrderCustFieldId('lotOrderPrimaryCustFieldId', ResultCode::LOT_ORDER_PRIMARY_CUSTOM_FIELD_ID_NOT_FOUND);
        }
        if ((int)$inputDto->lotOrderSecondaryType === Constants\Auction::LOT_ORDER_BY_CUST_FIELD) {
            $this->checkAreRequired(['lotOrderSecondaryCustField', 'lotOrderSecondaryCustFieldId'], ResultCode::LOT_ORDER_SECONDARY_CUSTOM_FIELD_REQUIRED);
            $this->checkExistAuctionLotOrderCustField('lotOrderSecondaryCustField', ResultCode::LOT_ORDER_SECONDARY_CUSTOM_FIELD_NOT_FOUND);
            $this->checkExistAuctionLotOrderCustFieldId('lotOrderSecondaryCustFieldId', ResultCode::LOT_ORDER_SECONDARY_CUSTOM_FIELD_ID_NOT_FOUND);
        }
        if ((int)$inputDto->lotOrderTertiaryType === Constants\Auction::LOT_ORDER_BY_CUST_FIELD) {
            $this->checkAreRequired(['lotOrderTertiaryCustField', 'lotOrderTertiaryCustFieldId'], ResultCode::LOT_ORDER_TERTIARY_CUSTOM_FIELD_REQUIRED);
            $this->checkExistAuctionLotOrderCustField('lotOrderTertiaryCustField', ResultCode::LOT_ORDER_TERTIARY_CUSTOM_FIELD_NOT_FOUND);
            $this->checkExistAuctionLotOrderCustFieldId('lotOrderTertiaryCustFieldId', ResultCode::LOT_ORDER_TERTIARY_CUSTOM_FIELD_ID_NOT_FOUND);
        }
        if ((int)$inputDto->lotOrderQuaternaryType === Constants\Auction::LOT_ORDER_BY_CUST_FIELD) {
            $this->checkAreRequired(['lotOrderQuaternaryCustField', 'lotOrderQuaternaryCustFieldId'], ResultCode::LOT_ORDER_QUATERNARY_CUSTOM_FIELD_REQUIRED);
            $this->checkExistAuctionLotOrderCustField('lotOrderQuaternaryCustField', ResultCode::LOT_ORDER_QUATERNARY_CUSTOM_FIELD_NOT_FOUND);
            $this->checkExistAuctionLotOrderCustFieldId('lotOrderQuaternaryCustFieldId', ResultCode::LOT_ORDER_QUATERNARY_CUSTOM_FIELD_ID_NOT_FOUND);
        }
        $this->checkSyncKeyUnique('syncKey', ResultCode::SYNC_KEY_EXIST, Constants\EntitySync::TYPE_AUCTION);

        if (AuctionStatusPureChecker::new()->isLiveOrHybrid($this->auctionType)) {
            $this->checkInArray('absenteeBidsDisplay', ResultCode::ABSENTEE_BIDS_DISPLAY_UNKNOWN, $absenteeBidValues);
            $this->checkNumeric('notifyXLots', ResultCode::NOTIFY_X_LOTS_INVALID);
            $this->checkInArray('streamDisplay', ResultCode::STREAM_DISPLAY_UNKNOWN, $streamDisplayNames);
            $this->checkInArray('streamDisplayValue', ResultCode::STREAM_DISPLAY_UNKNOWN, $streamDisplayValues);
        }

        if (AuctionStatusPureChecker::new()->isHybrid($this->auctionType)) {
            if (isset($inputDto->extendTime)) { // check filling, only when field presents in input
                $this->checkRequired('extendTime', ResultCode::EXTEND_TIME_REQUIRED);
            }
            $this->checkMin('extendTime', ResultCode::EXTEND_TIME_TOO_SMALL, $this->cfg()->get('core->auction->hybrid->extendTime->minLimit') - 1);
            $this->checkMax('extendTime', ResultCode::EXTEND_TIME_TOO_BIG, $this->cfg()->get('core->auction->hybrid->extendTime->maxLimit') + 1);
            $this->checkIntPositive('lotStartGapTime', ResultCode::LOT_START_GAP_TIME_INVALID);
            $this->checkMin('lotStartGapTime', ResultCode::LOT_START_GAP_TIME_TOO_SMALL, $this->cfg()->get('core->auction->hybrid->lotStartGapTime->minLimit') - 1);
            $this->checkMax('lotStartGapTime', ResultCode::LOT_START_GAP_TIME_TOO_BIG, $this->cfg()->get('core->auction->hybrid->lotStartGapTime->maxLimit') + 1);
        }

        if (AuctionStatusPureChecker::new()->isTimed($this->auctionType)) {
            $this->checkInteger('defaultLotPeriod', ResultCode::DEFAULT_LOT_PERIOD_INVALID);
            $this->checkInArray('eventType', ResultCode::EVENT_TYPE_UNKNOWN, Constants\Auction::$eventTypeNames);
            $this->checkNumeric('notifyXMinutes', ResultCode::NOTIFY_X_MINUTES_INVALID);
            $this->checkInArrayKeys('staggerClosing', ResultCode::STAGGER_CLOSING_UNKNOWN, $staggerClosingIntervalNames);
            $this->checkIntPositive('lotsPerInterval', ResultCode::LOTS_PER_INTERVAL_INVALID);
        }

        // Required fields
        $this->checkRequired('auctionType', ResultCode::AUCTION_TYPE_REQUIRED);
        $this->checkRequired('name', ResultCode::NAME_REQUIRED);
        if (
            $this->isDateFieldAssigned()
            && !$inputDto->timezone
        ) {
            $this->addError(ResultCode::TIMEZONE_REQUIRED);
        }

        if (AuctionStatusPureChecker::new()->isTimed($this->auctionType)) {
            if ($inputDto->staggerClosing > 0) {
                $this->checkRequired('lotsPerInterval', ResultCode::LOTS_PER_INTERVAL_REQUIRED);
            }
        }
        //Published backward compatibility
        if (
            isset($inputDto->published)
            && isset($inputDto->publishDate)
        ) {
            $this->addError(ResultCode::PUBLISHED_DEPRECATED);
        }
        $hasPublishPrivilege = $this->getEditorAdminPrivilegeChecker()->hasSubPrivilegeForPublish();
        if (!$hasPublishPrivilege && isset($inputDto->publishDate)) {
            $this->addError(ResultCode::PUBLISH_DATE_MISSING_PRIVILEGE);
        }
        if (!$hasPublishPrivilege && isset($inputDto->unpublishDate)) {
            $this->addError(ResultCode::UNPUBLISH_DATE_MISSING_PRIVILEGE);
        }

        $this->createDateValidationIntegrator()->validate($this);
        $this->validateConsignorCommission();
        $this->validateConsignorSoldFee();
        $this->validateConsignorUnsoldFee();
        $this->createLocationValidationIntegrator()->validate($this, $inputDto->specificEventLocation, ResultCode::SPECIFIC_EVENT_LOCATION_INVALID, Constants\Location::TYPE_AUCTION_EVENT);
        $this->createLocationValidationIntegrator()->validate($this, $inputDto->specificInvoiceLocation, ResultCode::SPECIFIC_INVOICE_LOCATION_INVALID, Constants\Location::TYPE_AUCTION_INVOICE);
        $this->checkProhibits('specificEventLocation', ['eventLocation', 'eventLocationId'], ResultCode::SPECIFIC_EVENT_LOCATION_REDUNDANT);
        $this->checkProhibits('specificInvoiceLocation', ['invoiceLocation', 'invoiceLocationId'], ResultCode::SPECIFIC_INVOICE_LOCATION_REDUNDANT);
        $this->createAuctionTaxSchemaValidationIntegrator()->validate($this);

        $this->log();
        $isValid = $this->needValidateCustomFields ? empty($this->errors) && empty($this->customFieldsErrors) : empty($this->errors);
        $configDto->enableValidStatus($isValid);
        return $isValid;
    }

    /** *** ******************************** ***
     * validate*() are specific to field methods
     */

    /**
     * Check additionalCurrencies
     */
    protected function validateAdditionalCurrencies(): void
    {
        $inputDto = $this->getInputDto();
        $additionalCurrencies = (array)$inputDto->additionalCurrencies;
        if ($additionalCurrencies) {
            foreach ($additionalCurrencies as $additionalCurrency) {
                if (!$this->getCurrencyExistenceChecker()->existByName($additionalCurrency)) {
                    $this->addError(ResultCode::CURRENCY_NOT_FOUND);
                    break;
                }
            }
        }
    }

    /**
     * Perform all lot item (auction/inventory) "Auction info link" field value related validations
     * (including validation for hidden field and required value).
     */
    protected function validateAuctionInfoLink(): void
    {
        $this->createAuctionInfoLinkValidationIntegrator()->validate($this);
    }

    protected function validateBuyersPremiums(): void
    {
        $inputDto = $this->getInputDto();
        $input = BuyersPremiumValidationInput::new()->fromAuctionMakerDto(
            $this->getInputDto(),
            $this->getConfigDto()
        );
        $this->createBuyersPremiumValidationIntegrator()->validate(
            $this,
            $input,
            ResultCode::BUYERS_PREMIUMS_VALIDATION_FAILED
        );

        if (
            $inputDto->bpRule
            && !$this->isIndividualBuyersPremiumEmpty()
        ) {
            $this->addError(ResultCode::BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER);
        }
    }

    protected function isIndividualBuyersPremiumEmpty(): bool
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (
            $inputDto->additionalBpInternet
            && !AuctionStatusPureChecker::new()->isTimed($this->auctionType)
        ) {
            return false;
        }

        if ($configDto->mode->isCsv()) {
            $csvParser = $this->createBuyersPremiumCsvParser();
            $isSyntaxCorrect = $csvParser->validateSyntax((string)$inputDto->buyersPremiumString);
            $buyersPremiumDataRows = $isSyntaxCorrect
                ? $csvParser->parse((string)$inputDto->buyersPremiumString, $configDto->serviceAccountId)
                : [];
        } else {
            $buyersPremiumDataRows = (array)$inputDto->buyersPremiumDataRows;
        }

        return count($buyersPremiumDataRows) === 0;
    }

    /**
     * Check email
     */
    protected function validateEmail(): void
    {
        $inputDto = $this->getInputDto();
        if ($inputDto->email) {
            foreach (explode(self::DELIMITER_EMAILS, $inputDto->email) as $email) {
                if (!EmailAddressChecker::new()->isEmail(trim($email))) {
                    $this->checkEmail('email', ResultCode::EMAIL_INVALID);
                    break;
                }
            }
        }
    }

    /**
     * Check lotOrders
     */
    protected function validateLotOrders(): void
    {
        $inputDto = $this->getInputDto();
        $indexes = [
            $inputDto->lotOrderPrimaryType . $inputDto->lotOrderPrimaryCustFieldId,
            $inputDto->lotOrderSecondaryType . $inputDto->lotOrderSecondaryCustFieldId,
            $inputDto->lotOrderTertiaryType . $inputDto->lotOrderTertiaryCustFieldId,
            $inputDto->lotOrderQuaternaryType . $inputDto->lotOrderQuaternaryCustFieldId,
        ];
        $errors = [
            ResultCode::LOT_ORDER_PRIMARY_TYPE_NOT_UNIQUE,
            ResultCode::LOT_ORDER_SECONDARY_TYPE_NOT_UNIQUE,
            ResultCode::LOT_ORDER_TERTIARY_TYPE_NOT_UNIQUE,
            ResultCode::LOT_ORDER_QUATERNARY_TYPE_NOT_UNIQUE,
        ];
        $uniqueIndexes = array_unique($indexes);

        $count = count($indexes);
        for ($i = 0; $i < $count; $i++) {
            if (
                $indexes[$i]
                && !isset($uniqueIndexes[$i])
            ) {
                $this->addError($errors[$i]);
            }
        }
    }

    /**
     * TODO: implement unit test
     * Check rtbd name changing
     */
    protected function validateRtbdName(): void
    {
        $inputDto = $this->getInputDto();
        $isAvailable = $this->getRtbdPoolFeatureAvailabilityValidator()
            ->isAvailableByAuctionType($this->auctionType);
        if ($isAvailable) {
            $rtbdName = (string)$inputDto->rtbdName;
            if (
                $rtbdName !== '' // check only when set
                && !$this->createAuctionRtbdChecker()->isCorrectLink($inputDto->rtbdName)
            ) {
                $this->addError(ResultCode::RTBD_NAME_INCORRECT);
            }
        }
    }

    /**
     * All sale# related validations
     */
    protected function validateSaleNo(): void
    {
        $this->createSaleNoValidationIntegrator()->validate($this);
    }

    /**
     * Validate field visibility that is configured at account level.
     * This is part of configuration-based validation, and must be called before DtoHelper::prepareValues() that clears values of hidden fields.
     */
    protected function validateVisibility(): void
    {
        $this->checkVisibilityWithFieldConfig('auctionInfoLink', ResultCode::AUCTION_INFO_LINK_HIDDEN);
        $this->checkVisibilityWithFieldConfig('bpTaxSchemaId', ResultCode::BP_TAX_SCHEMA_ID_HIDDEN);
        $this->checkVisibilityWithFieldConfig('clerkingStyle', ResultCode::CLERKING_STYLE_HIDDEN);
        $this->checkVisibilityWithFieldConfig('clerkingStyleId', ResultCode::CLERKING_STYLE_ID_HIDDEN);
        $this->checkVisibilityWithFieldConfig('consignorCommissionCalculationMethod', ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_HIDDEN);
        $this->checkVisibilityWithFieldConfig('consignorCommissionId', ResultCode::CONSIGNOR_COMMISSION_ID_HIDDEN);
        $this->checkVisibilityWithFieldConfig('consignorCommissionRanges', ResultCode::CONSIGNOR_COMMISSION_RANGES_HIDDEN);
        $this->checkVisibilityWithFieldConfig('consignorSoldFeeCalculationMethod', ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_HIDDEN);
        $this->checkVisibilityWithFieldConfig('consignorSoldFeeId', ResultCode::CONSIGNOR_SOLD_FEE_ID_HIDDEN);
        $this->checkVisibilityWithFieldConfig('consignorSoldFeeRanges', ResultCode::CONSIGNOR_SOLD_FEE_RANGES_HIDDEN);
        $this->checkVisibilityWithFieldConfig('consignorSoldFeeReference', ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_HIDDEN);
        $this->checkVisibilityWithFieldConfig('consignorUnsoldFeeCalculationMethod', ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_HIDDEN);
        $this->checkVisibilityWithFieldConfig('consignorUnsoldFeeId', ResultCode::CONSIGNOR_UNSOLD_FEE_ID_HIDDEN);
        $this->checkVisibilityWithFieldConfig('consignorUnsoldFeeRanges', ResultCode::CONSIGNOR_UNSOLD_FEE_RANGES_HIDDEN);
        $this->checkVisibilityWithFieldConfig('consignorUnsoldFeeReference', ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_HIDDEN);
        $this->checkVisibilityWithFieldConfig('dateAssignmentStrategy', ResultCode::DATE_ASSIGNMENT_STRATEGY_HIDDEN);
        $this->checkVisibilityWithFieldConfig('description', ResultCode::DESCRIPTION_HIDDEN);
        $this->checkVisibilityWithFieldConfig('endPrebiddingDate', ResultCode::END_PREBIDDING_DATE_HIDDEN);
        $this->checkVisibilityWithFieldConfig('endRegisterDate', ResultCode::END_REGISTER_DATE_HIDDEN);
        $this->checkVisibilityWithFieldConfig('eventLocation', ResultCode::EVENT_LOCATION_HIDDEN);
        $this->checkVisibilityWithFieldConfig('eventLocationId', ResultCode::EVENT_LOCATION_ID_HIDDEN);
        $this->checkVisibilityWithFieldConfig('eventType', ResultCode::EVENT_TYPE_HIDDEN);
        $this->checkVisibilityWithFieldConfig('eventTypeId', ResultCode::EVENT_TYPE_ID_HIDDEN);
        $this->checkVisibilityWithFieldConfig('excludeClosedLots', ResultCode::EXCLUDE_CLOSED_LOTS_HIDDEN);
        $this->checkVisibilityWithFieldConfig('hpTaxSchemaId', ResultCode::HP_TAX_SCHEMA_ID_HIDDEN);
        $this->checkVisibilityWithFieldConfig('image', ResultCode::IMAGE_HIDDEN);
        $this->checkVisibilityWithFieldConfig('invoiceLocation', ResultCode::INVOICE_LOCATION_HIDDEN);
        $this->checkVisibilityWithFieldConfig('invoiceLocationId', ResultCode::INVOICE_LOCATION_ID_HIDDEN);
        $this->checkVisibilityWithFieldConfig('invoiceNotes', ResultCode::INVOICE_NOTES_HIDDEN);
        $this->checkVisibilityWithFieldConfig('listingOnly', ResultCode::LISTING_ONLY_HIDDEN);
        $this->checkVisibilityWithFieldConfig('onlyOngoingLots', ResultCode::ONLY_ONGOING_LOTS_HIDDEN);
        $this->checkVisibilityWithFieldConfig('notShowUpcomingLots', ResultCode::NOT_SHOW_UPCOMING_LOTS_HIDDEN);
        $this->checkVisibilityWithFieldConfig('hideUnsoldLots', ResultCode::HIDE_UNSOLD_LOTS_HIDDEN);
        $this->checkVisibilityWithFieldConfig('parcelChoice', ResultCode::PARCEL_CHOICE_HIDDEN);
        $this->checkVisibilityWithFieldConfig('publishDate', ResultCode::PUBLISH_DATE_HIDDEN);
        $this->checkVisibilityWithFieldConfig('reverse', ResultCode::REVERSE_HIDDEN);
        $this->checkVisibilityWithFieldConfig('saleFullNo', ResultCode::SALE_FULL_NO_HIDDEN);
        $this->checkVisibilityWithFieldConfig('saleNum', ResultCode::SALE_NUM_HIDDEN);
        $this->checkVisibilityWithFieldConfig('saleNumExt', ResultCode::SALE_NUM_EXT_HIDDEN);
        $this->checkVisibilityWithFieldConfig('seoMetaDescription', ResultCode::SEO_META_DESCRIPTION_HIDDEN);
        $this->checkVisibilityWithFieldConfig('seoMetaKeywords', ResultCode::SEO_META_KEYWORDS_HIDDEN);
        $this->checkVisibilityWithFieldConfig('seoMetaTitle', ResultCode::SEO_META_TITLE_HIDDEN);
        $this->checkVisibilityWithFieldConfig('shippingInfo', ResultCode::SHIPPING_INFO_HIDDEN);
        $this->checkVisibilityWithFieldConfig('staggerClosing', ResultCode::STAGGER_CLOSING_HIDDEN);
        $this->checkVisibilityWithFieldConfig('lotsPerInterval', ResultCode::LOTS_PER_INTERVAL_HIDDEN);
        $this->checkVisibilityWithFieldConfig('startRegisterDate', ResultCode::START_REGISTER_DATE_HIDDEN);
        $this->checkVisibilityWithFieldConfig('streamDisplay', ResultCode::STREAM_DISPLAY_HIDDEN);
        $this->checkVisibilityWithFieldConfig('streamDisplayValue', ResultCode::STREAM_DISPLAY_VALUE_HIDDEN);
        $this->checkVisibilityWithFieldConfig('servicesTaxSchemaId', ResultCode::SERVICES_TAX_SCHEMA_ID_HIDDEN);
        $this->checkVisibilityWithFieldConfig('taxPercent', ResultCode::TAX_PERCENT_HIDDEN);
        $this->checkVisibilityWithFieldConfig('taxDefaultCountry', ResultCode::TAX_DEFAULT_COUNTRY_HIDDEN);
        $this->checkVisibilityWithFieldConfig('termsAndConditions', ResultCode::TERMS_AND_CONDITIONS_HIDDEN);
        $this->checkVisibilityWithFieldConfig('unpublishDate', ResultCode::UNPUBLISH_DATE_HIDDEN);
    }

    /** *** ************************************************************* ***
     * check*() - general checking methods may be called for different fields
     */

    /**
     * Get required attribute for the field config group from auction_field_config table
     * @param string $field
     * @return bool
     */
    protected function isRequiredByFieldConfig(string $field): bool
    {
        $configDto = $this->getConfigDto();
        $isRequired = $this->getAuctionFieldConfigProvider()
            ->setFieldMap(EntityMakerFieldMap::new())
            ->isRequired($field, $configDto->serviceAccountId);
        return $isRequired;
    }

    protected function isVisibleByFieldConfig(string $field): bool
    {
        $configDto = $this->getConfigDto();
        $isVisible = $this->getAuctionFieldConfigProvider()
            ->setFieldMap(EntityMakerFieldMap::new())
            ->isVisible($field, $configDto->serviceAccountId);
        return $isVisible;
    }

    /**
     * Regular check of requirement and if field is required in auction_field_config table
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkRequiredWithFieldConfig(string $field, int $error): void
    {
        if (!$this->isRequiredByFieldConfig($field)) {
            return;
        }
        $this->checkRequired($field, $error);
    }

    /**
     * Regular check of requirement and if field is required in lot_field_config table
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkRequiredZeroAllowedWithFieldConfig(string $field, int $error): void
    {
        if (!$this->isRequiredByFieldConfig($field)) {
            return;
        }
        $this->checkRequiredZeroAllowed($field, $error);
    }

    protected function checkVisibilityWithFieldConfig(string $field, int $error): void
    {
        if (
            !$this->isVisibleByFieldConfig($field)
            && isset($this->getInputDto()->{$field})
        ) {
            $this->addError($error);
        }
    }

    /**
     * Is dto field required and zero value allowed
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkRequiredZeroAllowed(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (
            $inputDto->id
            && !isset($inputDto->$field)
        ) {
            return;
        }

        $value = $inputDto->$field;

        if (
            $value
            || NumberValidator::new()->isIntPositiveOrZero($value)
            || NumberValidator::new()->isRealPositiveOrZero($value)
        ) {
            return;
        }

        $this->addError($error);
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistBuyersPremiumShortName(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (!$inputDto->$field) {
            return;
        }

        $isBpNotDefault = $this->createBuyersPremiumExistenceChecker()
            ->existNotDefault($inputDto->$field, $configDto->serviceAccountId);
        $this->addErrorIfFail($error, $isBpNotDefault);
    }

    /**
     * Do the customFields fields have an error
     * @return bool
     */
    public function hasCustomFieldsErrors(): bool
    {
        $has = !empty($this->getCustomFieldsErrors());
        return $has;
    }

    /** GetErrors Methods */

    /**
     * Get auctionType errors
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getAuctionTypeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::AUCTION_TYPE_REQUIRED,
                ResultCode::AUCTION_TYPE_UNKNOWN,
            ]
        );
        return $intersected;
    }

    /**
     * Get authorizationAmount errors
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getAuthorizationAmountErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::AUTHORIZATION_AMOUNT_INVALID,
            ]
        );
        return $intersected;
    }

    /**
     * Get biddingConsoleAccessDate errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getBiddingConsoleAccessDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::BIDDING_CONSOLE_ACCESS_DATE_EARLIER_START_DATE,
                ResultCode::BIDDING_CONSOLE_ACCESS_DATE_INVALID,
                ResultCode::BIDDING_CONSOLE_ACCESS_DATE_REQUIRED,
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

    /**
     * Get ccThresholdDomestic errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getCcThresholdDomesticErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::CC_THRESHOLD_DOMESTIC_INVALID,
            ]
        );
        return $intersected;
    }

    /**
     * Get ccThresholdInternational errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getCcThresholdInternationalErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::CC_THRESHOLD_INTERNATIONAL_INVALID,
            ]
        );
        return $intersected;
    }

    /**
     * Get customFields errors
     * @return int[]
     */
    public function getCustomFieldsErrors(): array
    {
        return $this->customFieldsErrors;
    }

    /**
     * Get getDefaultLotPeriod errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getDefaultLotPeriodErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::DEFAULT_LOT_PERIOD_INVALID,
            ]
        );
        return $intersected;
    }

    /**
     * Get defaultLotPostalCode errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getDefaultLotPostalCodeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::DEFAULT_LOT_POSTAL_CODE_INVALID,
            ]
        );
        return $intersected;
    }

    /**
     * Get email errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getEmailErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::EMAIL_INVALID,
            ]
        );
        return $intersected;
    }

    /**
     * Get extendTime errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getExtendTimeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::EXTEND_TIME_INVALID,
                ResultCode::EXTEND_TIME_TOO_SMALL,
                ResultCode::EXTEND_TIME_TOO_BIG,
                ResultCode::EXTEND_TIME_REQUIRED,
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

    protected function getServicesTaxSchemaIdErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::SERVICES_TAX_SCHEMA_ID_INVALID,
                ResultCode::SERVICES_TAX_SCHEMA_ID_REQUIRED,
                ResultCode::SERVICES_TAX_SCHEMA_COUNTRY_MISMATCH,
            ]
        );
        return $intersected;
    }

    /**
     * Get startClosingDate errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getStartClosingDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::START_CLOSING_DATE_DO_NOT_MATCH_ITEMS_DATE,
                ResultCode::START_CLOSING_DATE_INVALID,
                ResultCode::START_CLOSING_DATE_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * Get Timezone errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getTimezoneErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::TIMEZONE_REQUIRED,
                ResultCode::TIMEZONE_UNKNOWN,
            ]
        );
        return $intersected;
    }

    /**
     * Get lotOrderPrimaryType errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getLotOrderPrimaryTypeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::LOT_ORDER_PRIMARY_TYPE_NOT_UNIQUE]
        );
        return $intersected;
    }

    /**
     * Get lotOrderSecondaryType errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getLotOrderSecondaryTypeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::LOT_ORDER_SECONDARY_TYPE_NOT_UNIQUE]
        );
        return $intersected;
    }

    /**
     * Get lotOrderTertiaryType errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getLotOrderTertiaryTypeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::LOT_ORDER_TERTIARY_TYPE_NOT_UNIQUE]
        );
        return $intersected;
    }

    /**
     * Get lotOrderQuaternaryType errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getLotOrderQuaternaryTypeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::LOT_ORDER_QUATERNARY_TYPE_NOT_UNIQUE]
        );
        return $intersected;
    }

    /**
     * Get lotStartGapTime errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getLotStartGapTimeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::LOT_START_GAP_TIME_INVALID,
                ResultCode::LOT_START_GAP_TIME_TOO_BIG,
                ResultCode::LOT_START_GAP_TIME_TOO_SMALL,
            ]
        );
        return $intersected;
    }

    /**
     * Get lotsPerInterval errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getLotsPerIntervalErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::LOTS_PER_INTERVAL_HIDDEN,
                ResultCode::LOTS_PER_INTERVAL_INVALID,
                ResultCode::LOTS_PER_INTERVAL_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * Get maxOutstanding errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getMaxOutstandingErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::MAX_OUTSTANDING_INVALID]
        );
        return $intersected;
    }

    /**
     * Get name errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::NAME_INVALID,
                ResultCode::NAME_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * Get postAucImportPremium errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getPostAucImportPremiumErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::POST_AUC_IMPORT_PREMIUM_INVALID]
        );
        return $intersected;
    }

    /**
     * Get rtbdName errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getRtbdNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::RTBD_NAME_INCORRECT]
        );
        return $intersected;
    }

    /**
     * Get saleNo errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getSaleNumErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::SALE_FULL_NO_HIDDEN,
                ResultCode::SALE_FULL_NO_PARSE_ERROR,
                ResultCode::SALE_FULL_NO_REQUIRED,
                ResultCode::SALE_NO_EXIST,
                ResultCode::SALE_NUM_HIDDEN,
                ResultCode::SALE_NUM_HIGHER_MAX_AVAILABLE_VALUE,
                ResultCode::SALE_NUM_INVALID,
                ResultCode::SALE_NUM_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * Get saleNumExt errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getSaleNumExtensionErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::SALE_NUM_EXT_INVALID,
                ResultCode::SALE_NUM_EXT_NOT_ALPHA,
                ResultCode::SALE_NUM_EXT_HIDDEN,
            ]
        );
        return $intersected;
    }

    /**
     * Get taxPercent errors
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getTaxPercentErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::TAX_PERCENT_INVALID,
                ResultCode::TAX_PERCENT_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getAuctionVisibilityAccessErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::AUCTION_VISIBILITY_ACCESS_UNKNOWN]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getAuctionInfoAccessErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::AUCTION_INFO_ACCESS_UNKNOWN]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getAuctionCatalogAccessErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::AUCTION_CATALOG_ACCESS_UNKNOWN]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getLiveViewAccessErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::LIVE_VIEW_ACCESS_UNKNOWN]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getLotDetailsAccessErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::LOT_DETAILS_ACCESS_UNKNOWN]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getLotBiddingHistoryAccessErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::LOT_BIDDING_HISTORY_ACCESS_UNKNOWN]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getStartBiddingDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::START_BIDDING_DATE_INVALID,
                ResultCode::START_BIDDING_DATE_DO_NOT_MATCH_ITEMS_DATE,
                ResultCode::START_BIDDING_DATE_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getLiveEndDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::LIVE_END_DATE_INVALID,
                ResultCode::LIVE_END_DATE_REQUIRED,
                ResultCode::LIVE_END_DATE_EARLIER_START_CLOSING_DATE,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getLotBiddingInfoAccessErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::LOT_BIDDING_INFO_ACCESS_UNKNOWN]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getLotWinningBidAccessErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::LOT_WINNING_BID_ACCESS_UNKNOWN]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getLotStartingBidAccessErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::LOT_STARTING_BID_ACCESS_UNKNOWN]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    protected function getEndRegisterDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::END_REGISTER_DATE_INVALID,
                ResultCode::END_REGISTER_DATE_EARLIER_START_REGISTER_DATE,
                ResultCode::END_REGISTER_DATE_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getAuctionInfoLinkErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::AUCTION_INFO_LINK_HIDDEN,
                ResultCode::AUCTION_INFO_LINK_REQUIRED,
                ResultCode::AUCTION_INFO_LINK_URL_INVALID,
            ]
        );
        return $intersected;
    }

    protected function getDescriptionErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::DESCRIPTION_HIDDEN,
                ResultCode::DESCRIPTION_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getEndPrebiddingDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::END_PREBIDDING_DATE_HIDDEN,
                ResultCode::END_PREBIDDING_DATE_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getEventLocationErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::EVENT_LOCATION_HIDDEN,
                ResultCode::EVENT_LOCATION_ID_HIDDEN,
                ResultCode::EVENT_LOCATION_ID_REQUIRED,
                ResultCode::EVENT_LOCATION_NOT_FOUND,
                ResultCode::EVENT_LOCATION_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getInvoiceLocationErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::INVOICE_LOCATION_HIDDEN,
                ResultCode::INVOICE_LOCATION_ID_HIDDEN,
                ResultCode::INVOICE_LOCATION_ID_REQUIRED,
                ResultCode::INVOICE_LOCATION_NOT_FOUND,
                ResultCode::INVOICE_LOCATION_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getInvoiceNotesErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::INVOICE_NOTES_HIDDEN,
                ResultCode::INVOICE_NOTES_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getPublishDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::PUBLISH_DATE_HIDDEN,
                ResultCode::PUBLISH_DATE_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getSeoMetaDescriptionErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::SEO_META_DESCRIPTION_HIDDEN,
                ResultCode::SEO_META_DESCRIPTION_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getSeoMetaKeywordsErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::SEO_META_KEYWORDS_HIDDEN,
                ResultCode::SEO_META_KEYWORDS_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getSeoMetaTitleErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::SEO_META_TITLE_HIDDEN,
                ResultCode::SEO_META_TITLE_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getShippingInfoErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::SHIPPING_INFO_HIDDEN,
                ResultCode::SHIPPING_INFO_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getStaggerClosingErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::STAGGER_CLOSING_HIDDEN,
                ResultCode::STAGGER_CLOSING_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getStartRegisterDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::START_REGISTER_DATE_HIDDEN,
                ResultCode::START_REGISTER_DATE_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getTaxDefaultCountryErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::TAX_DEFAULT_COUNTRY_HIDDEN,
                ResultCode::TAX_DEFAULT_COUNTRY_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getTermsAndConditionsErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::TERMS_AND_CONDITIONS_HIDDEN,
                ResultCode::TERMS_AND_CONDITIONS_REQUIRED,
            ]
        );
        return $intersected;
    }

    protected function getUnpublishDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::UNPUBLISH_DATE_HIDDEN,
                ResultCode::UNPUBLISH_DATE_REQUIRED,
            ]
        );
        return $intersected;
    }

    /* Auction validation rules */

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistAuctionAuctioneerId(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isFoundBySystemId = $this->getAuctionAuctioneerExistenceChecker()->existSystemById((int)$inputDto->$field);
        $this->addErrorIfFail($error, $isFoundBySystemId);
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistAuctionCurrency(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isFoundByName = $this->getCurrencyExistenceChecker()->existByName($inputDto->$field);
        $this->addErrorIfFail($error, $isFoundByName);
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistAuctionLotOrderCustField(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isFoundByName = $this->createLotCustomFieldExistenceChecker()->existByName($inputDto->$field);
        $this->addErrorIfFail($error, $isFoundByName);
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistAuctionLotOrderCustFieldId(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isFound = (bool)$this->createLotCustomFieldLoader()->load($inputDto->$field);
        $this->addErrorIfFail($error, $isFound);
    }

    /**
     * Check if at least one date field is assigned
     * @return bool
     */
    protected function isDateFieldAssigned(): bool
    {
        $inputDto = $this->getInputDto();
        $dateFields = [
            'biddingConsoleAccessDate',
            'endPrebiddingDate',
            'endRegisterDate',
            'liveEndDate',
            'publishDate',
            'startBiddingDate',
            'startClosingDate',
            'startRegisterDate',
            'unpublishDate',
        ];
        foreach ($dateFields as $dateField) {
            /** @noinspection PhpConditionAlreadyCheckedInspection - because field can be set with null value */
            if (
                isset($inputDto->$dateField)
                && $inputDto->$dateField !== null
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Support logging of found errors or success
     */
    protected function log(): void
    {
        $inputDto = $this->getInputDto();
        if (empty($this->errors)) {
            log_trace('Auction validation done' . composeSuffix(['a' => $inputDto->id]));
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
                'a' => $inputDto->id,
                'errors' => array_merge(array_values($foundNamesWithCodes), $notFoundCodes),
            ];
            log_debug('Auction validation failed' . composeSuffix($logData));
        }
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
             * Skip validation for the existing auction, when fields are absent in input.
             */
            return;
        }

        $isRequired = $this->getAuctionFieldConfigProvider()
            ->setFieldMap(null)
            ->isRequired(Constants\AuctionFieldConfig::CONSIGNOR_COMMISSION_FEE, $configDto->serviceAccountId);
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
    protected function checkConsignorCommissionFeeId(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $consignorCommissionId = $inputDto->$field;
        if ($consignorCommissionId) {
            $isExist = $this->createConsignorCommissionFeeExistenceChecker()->existByIdAndAccountId(
                (int)$consignorCommissionId,
                $configDto->serviceAccountId
            );
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
    public function getAdditionalBpInternetErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::ADDITIONAL_BP_INTERNET_INVALID]);
        return $intersected;
    }

    protected function getEditorAdminPrivilegeChecker(): AdminPrivilegeChecker
    {
        if ($this->editorAdminPrivilegeChecker === null) {
            $this->editorAdminPrivilegeChecker = AdminPrivilegeChecker::new()
                ->initByUserId($this->getConfigDto()->editorUserId);
        }
        return $this->editorAdminPrivilegeChecker;
    }

    public function setEditorAdminPrivilegeChecker(AdminPrivilegeChecker $checker): static
    {
        $this->editorAdminPrivilegeChecker = $checker;
        return $this;
    }

    /**
     * Initialize fields that participate in decision-making.
     * AuctionType should be taken from the existing auction in DB. It isn't editable field.
     * AuctionType can be taken from input only when new auction is created.
     * @return void
     */
    protected function initEffectiveFields(): void
    {
        $inputDto = $this->getInputDto();
        if ($inputDto->id) {
            $auction = $this->getAuctionLoader()->load($inputDto->id);
            if (!$auction) {
                throw CouldNotFindAuction::withId($inputDto->id);
            }
            $this->auctionType = $auction->AuctionType;
        } else {
            $this->auctionType = $inputDto->auctionType;
        }
    }
}
