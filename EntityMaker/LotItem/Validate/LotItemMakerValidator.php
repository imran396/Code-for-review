<?php
/**
 * Class for validating of Lot input data
 *
 * SAM-4015: Auction Lot and Lot Item Entity Makers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 17, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate;

use LotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Validate\AuctionExistenceCheckerAwareTrait;
use Sam\BuyersPremium\Csv\Parse\BuyersPremiumCsvParserCreateTrait;
use Sam\BuyersPremium\Validate\BuyersPremiumExistenceCheckerCreateTrait;
use Sam\Consignor\Commission\Convert\ConsignorCommissionFeeRangeDtoConverterCreateTrait;
use Sam\Consignor\Commission\Edit\Validate\ConsignorCommissionFeeRangesValidatorCreateTrait;
use Sam\Consignor\Commission\Edit\Validate\ConsignorCommissionFeeValidatorCreateTrait;
use Sam\Consignor\Commission\Edit\Validate\RangeValidationResultStatus;
use Sam\Consignor\Commission\Validate\ConsignorCommissionFeeExistenceCheckerCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Platform\Constant\Base\ConstantNameResolver;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Core\Validate\Text\TextChecker;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\Base\Validate\BaseMakerValidator;
use Sam\EntityMaker\Base\Validate\Internal\BuyersPremium\BuyersPremiumValidationInput;
use Sam\EntityMaker\Base\Validate\Internal\BuyersPremium\BuyersPremiumValidationIntegratorCreateTrait;
use Sam\EntityMaker\LotItem\Common\Access\LotItemMakerAccessChecker;
use Sam\EntityMaker\LotItem\Common\Access\LotItemMakerAccessCheckerAwareTrait;
use Sam\EntityMaker\LotItem\Common\LotItemMakerCustomFieldManager;
use Sam\EntityMaker\LotItem\Common\WinningAuction\WinningAuctionInput;
use Sam\EntityMaker\LotItem\Common\WinningBidder\WinningBidderInput;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerDtoHelper;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerDtoHelperAwareTrait;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\EntityMaker\LotItem\Validate\Constants\ResultCode;
use Sam\EntityMaker\LotItem\Validate\Internal\ItemNo\ItemNoValidationIntegratorCreateTrait;
use Sam\EntityMaker\LotItem\Validate\Internal\Quantity\QuantityValidationIntegratorCreateTrait;
use Sam\EntityMaker\LotItem\Validate\Internal\Quantity\Scale\QuantityScaleDetectorCreateTrait;
use Sam\EntityMaker\LotItem\Validate\Internal\Quantity\Scale\QuantityScaleDetectorInput;
use Sam\EntityMaker\LotItem\Validate\Internal\TaxSchema\LotItemTaxSchemaValidationIntegratorCreateTrait;
use Sam\EntityMaker\LotItem\Validate\Internal\WinningBidder\WinningBidderAuctionCheckerCreateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Category\Validate\LotCategoryExistenceCheckerAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\Map\EntityMakerFieldMap;
use Sam\Lot\Validate\LotItemExistenceCheckerAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * The following methods are handled by \Sam\EntityMaker\Base\Validator::__call() method:
 * GetErrorMessage Methods
 * @method getAdditionalBpInternetErrorMessage()
 * @method getBuyNowSelectQuantityEnabledErrorMessage()
 * @method getCategoriesErrorMessage()
 * @method getChangesErrorMessage()
 * @method getConsignorErrorMessage()
 * @method getCostErrorMessage()
 * @method getDateSoldErrorMessage()
 * @method getDescriptionErrorMessage()
 * @method getHammerPriceErrorMessage()
 * @method getHighEstimateErrorMessage()
 * @method getIncrementsErrorMessage()
 * @method getItemNumErrorMessage()
 * @method getItemNumExtErrorMessage()
 * @method getItemFullNumErrorMessage()
 * @method getLowEstimateErrorMessage()
 * @method getNameErrorMessage()
 * @method getReplacementPriceErrorMessage()
 * @method getReservePriceErrorMessage()
 * @method getSalesTaxErrorMessage()
 * @method getStartingBidErrorMessage()
 * @method getWarrantyErrorMessage()
 * @method getConsignorCommissionIdErrorMessage()
 * @method getConsignorCommissionCalculationMethodErrorMessage()
 * @method getConsignorCommissionRangeErrorMessage()
 * @method getConsignorSoldFeeIdErrorMessage()
 * @method getConsignorSoldFeeCalculationMethodErrorMessage()
 * @method getConsignorSoldFeeRangeErrorMessage()
 * @method getConsignorUnsoldFeeIdErrorMessage()
 * @method getConsignorUnsoldFeeCalculationMethodErrorMessage()
 * @method getConsignorUnsoldFeeRangeErrorMessage()
 * @method getWinningBidderErrorMessage()
 * @method getAuctionSoldErrorMessage()
 * @method getBuyersPremiumsErrorMessage()
 * @method getTaxDefaultCountryErrorMessage()
 * @method getLocationErrorMessage()
 * @method getSeoMetaDescriptionErrorMessage()
 * @method getSeoMetaKeywordsErrorMessage()
 * @method getSeoMetaTitleErrorMessage()
 * @method getFacebookOpenGraphDescriptionErrorMessage()
 * @method getFacebookOpenGraphImageUrlErrorMessage()
 * @method getFacebookOpenGraphTitleErrorMessage()
 * @method getQuantityErrorMessage()
 * @method getQuantityDigitsErrorMessage()
 * @method getHpTaxSchemaIdErrorMessage()
 * @method getBpTaxSchemaIdErrorMessage()
 * HasError Methods
 * @method hasAdditionalBpInternetError()
 * @method hasBuyNowSelectQuantityEnabledError()
 * @method hasCategoriesError()
 * @method hasChangesError()
 * @method hasConsignorError()
 * @method hasCostError()
 * @method hasDateSoldError()
 * @method hasDescriptionError()
 * @method hasHammerPriceError()
 * @method hasHighEstimateError()
 * @method hasIncrementsError()
 * @method hasItemNumError()
 * @method hasItemNumExtError()
 * @method hasItemFullNumError()
 * @method hasLowEstimateError()
 * @method hasNameError()
 * @method hasReplacementPriceError()
 * @method hasReservePriceError()
 * @method hasSalesTaxError()
 * @method hasStartingBidError()
 * @method hasWarrantyError()
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
 * @method hasWinningBidderError()
 * @method hasAuctionSoldError()
 * @method hasBuyersPremiumsError()
 * @method hasTaxDefaultCountryError()
 * @method hasLocationError()
 * @method hasSeoMetaDescriptionError()
 * @method hasSeoMetaKeywordsError()
 * @method hasSeoMetaTitleError()
 * @method hasFacebookOpenGraphDescriptionError()
 * @method hasFacebookOpenGraphImageUrlError()
 * @method hasFacebookOpenGraphTitleError()
 * @method hasQuantityError()
 * @method hasQuantityDigitsError()
 * @method hasHpTaxSchemaIdError()
 * @method hasBpTaxSchemaIdError()
 *
 * @method LotItemMakerInputDto getInputDto()
 * @method LotItemMakerConfigDto getConfigDto()
 * @property LotItemMakerCustomFieldManager $customFieldManager
 */
class LotItemMakerValidator extends BaseMakerValidator
{
    use AuctionExistenceCheckerAwareTrait;
    use AuctionLoaderAwareTrait;
    use BuyersPremiumCsvParserCreateTrait;
    use BuyersPremiumExistenceCheckerCreateTrait;
    use BuyersPremiumValidationIntegratorCreateTrait;
    use ConsignorCommissionFeeExistenceCheckerCreateTrait;
    use ConsignorCommissionFeeRangeDtoConverterCreateTrait;
    use ConsignorCommissionFeeRangesValidatorCreateTrait;
    use ConsignorCommissionFeeValidatorCreateTrait;
    use ItemNoValidationIntegratorCreateTrait;
    use LotCategoryExistenceCheckerAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotFieldConfigProviderAwareTrait;
    use LotItemExistenceCheckerAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotItemMakerAccessCheckerAwareTrait;
    use LotItemMakerDtoHelperAwareTrait;
    use LotItemTaxSchemaValidationIntegratorCreateTrait;
    use QuantityScaleDetectorCreateTrait;
    use QuantityValidationIntegratorCreateTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use WinningBidderAuctionCheckerCreateTrait;

    /** @var string[] */
    protected array $tagNames = [
        ResultCode::ACCESS_DENIED => '',
        ResultCode::ADDITIONAL_BP_INTERNET_INVALID_FORMAT => 'AdditionalBpInternet',
        ResultCode::AUCTION_SOLD_DO_NOT_EXIST => 'AuctionSold',
        ResultCode::BUYERS_PREMIUMS_VALIDATION_FAILED => 'BuyersPremiums',
        ResultCode::BP_RANGE_CALCULATION_INVALID_FORMAT => 'BpRangeCalculation',
        ResultCode::BP_RULE_UNKNOWN => 'BpRule',
        ResultCode::BUY_NOW_SELECT_QUANTITY_NOT_ALLOWED => 'BuyNowSelectQuantity',
        ResultCode::CATEGORIES_DO_NOT_EXIST => 'CategoriesNames',
        ResultCode::CATEGORIES_INVALID_ENCODING => 'CategoriesNames',
        ResultCode::CATEGORIES_NODE_CAN_NOT_BE_ASSIGNED => 'CategoriesNames',
        ResultCode::CATEGORIES_REQUIRED => 'CategoriesNames',
        ResultCode::CHANGES_INVALID_ENCODING => 'Changes',
        ResultCode::CHANGES_REQUIRED => 'Changes',
        ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID => 'ConsignorCommissionCalculationMethod',
        ResultCode::CONSIGNOR_COMMISSION_ID_INVALID => 'ConsignorCommissionId',
        ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID => 'ConsignorCommissionRanges',
        ResultCode::CONSIGNOR_NAME_DO_NOT_EXIST => 'Consignor',
        ResultCode::CONSIGNOR_NAME_INVALID_FORMAT => 'Consignor',
        ResultCode::CONSIGNOR_REQUIRED => 'Consignor',
        ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID => 'ConsignorSoldFeeCalculationMethod',
        ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID => 'ConsignorSoldFeeId',
        ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID => 'ConsignorSoldFeeRanges',
        ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID => 'ConsignorSoldFeeCReference',
        ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID => 'ConsignorUnsoldFeeCalculationMethod',
        ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID => 'ConsignorUnsoldFeeId',
        ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID => 'ConsignorUnsoldFeeRanges',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID => 'ConsignorUnsoldFeeCReference',
        ResultCode::COST_INVALID_FORMAT => 'Cost',
        ResultCode::COST_INVALID_THOUSAND_SEPARATOR => 'Cost',
        ResultCode::COST_REQUIRED => 'Cost',
        ResultCode::DATE_SOLD_INVALID_FORMAT => 'DateSold',
        ResultCode::DESCRIPTION_INVALID_ENCODING => 'Description',
        ResultCode::DESCRIPTION_REQUIRED => 'Description',
        ResultCode::HAMMER_PRICE_INVALID_FORMAT => 'HammerPrice',
        ResultCode::HAMMER_PRICE_REQUIRED => 'HammerPrice',
        ResultCode::HIGH_ESTIMATE_INVALID_FORMAT => 'HighEstimate',
        ResultCode::HIGH_ESTIMATE_INVALID_THOUSAND_SEPARATOR => 'HighEstimate',
        ResultCode::HIGH_ESTIMATE_REQUIRED => 'HighEstimate',
        ResultCode::INCREMENTS_AMOUNT_EXIST => 'Increments',
        ResultCode::INCREMENTS_INVALID_AMOUNT => 'Increments',
        ResultCode::INCREMENTS_INVALID_FORMAT => 'Increments',
        ResultCode::INCREMENTS_INVALID_RANGE => 'Increments',
        ResultCode::ITEM_FULL_NUM_PARSE_ERROR => 'ItemFullNum',
        ResultCode::ITEM_NUM_ALREADY_EXIST => 'ItemNum',
        ResultCode::ITEM_NUM_EXT_INVALID_FORMAT => 'ItemNumExt',
        ResultCode::ITEM_NUM_EXT_INVALID_LENGTH => 'ItemNumExt',
        ResultCode::ITEM_NUM_HIGHER_MAX_AVAILABLE_VALUE => 'ItemNum',
        ResultCode::ITEM_NUM_INVALID_FORMAT => 'ItemNum',
        ResultCode::ITEM_NUM_REQUIRED => 'ItemNum',
        ResultCode::LOW_ESTIMATE_INVALID_FORMAT => 'LowEstimate',
        ResultCode::LOW_ESTIMATE_INVALID_THOUSAND_SEPARATOR => 'LowEstimate',
        ResultCode::LOW_ESTIMATE_REQUIRED => 'LowEstimate',
        ResultCode::NAME_INVALID_ENCODING => 'Name',
        ResultCode::NAME_IN_BLACKLIST => 'Name',
        ResultCode::NAME_REQUIRED => 'Name',
        ResultCode::REPLACEMENT_PRISE_INVALID_FORMAT => 'ReplacementPrice',
        ResultCode::REPLACEMENT_PRISE_INVALID_THOUSAND_SEPARATOR => 'ReplacementPrice',
        ResultCode::REPLACEMENT_PRISE_REQUIRED => 'ReplacementPrice',
        ResultCode::RESERVE_PRISE_INVALID_FORMAT => 'ReservePrice',
        ResultCode::RESERVE_PRISE_INVALID_THOUSAND_SEPARATOR => 'ReservePrice',
        ResultCode::RESERVE_PRISE_REQUIRED => 'ReservePrice',
        ResultCode::SALES_TAX_INVALID_FORMAT => 'SalesTax',
        ResultCode::SALES_TAX_INVALID_THOUSAND_SEPARATOR => 'SalesTax',
        ResultCode::SALES_TAX_REQUIRED => 'SalesTax',
        ResultCode::SPECIFIC_LOCATION_INVALID => 'SpecificLocation',
        ResultCode::SPECIFIC_LOCATION_REDUNDANT => 'SpecificLocation',
        ResultCode::STARTING_BID_INVALID_FORMAT => 'StartingBid',
        ResultCode::STARTING_BID_INVALID_THOUSAND_SEPARATOR => 'StartingBid',
        ResultCode::STARTING_BID_REQUIRED => 'StartingBid',
        ResultCode::SYNC_KEY_EXIST => 'SyncKey',
        ResultCode::WARRANTY_INVALID_ENCODING => 'Warranty',
        ResultCode::WARRANTY_REQUIRED => 'Warranty',
        ResultCode::WINNING_BIDDER_DO_NOT_EXIST => 'WinningBidder',
        ResultCode::WINNING_BIDDER_NOT_REGISTERED_IN_AUCTION_SOLD => 'WinningBidder',
        ResultCode::IMAGE_REQUIRED => 'LotImage',
        ResultCode::INCREMENTS_REQUIRED => 'Increments',
        ResultCode::WINNING_BIDDER_REQUIRED => 'WinningBidder',
        ResultCode::WINNING_BIDDER_ID_REQUIRED => 'WinningBidder',
        ResultCode::DATE_SOLD_REQUIRED => 'DateSold',
        ResultCode::AUCTION_SOLD_ID_REQUIRED => 'AuctionSold',
        ResultCode::AUCTION_SOLD_REQUIRED => 'AuctionSold',
        ResultCode::BP_REQUIRED => 'BuyersPremiums',
        ResultCode::BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER => 'BuyersPremiums',
        ResultCode::TAX_DEFAULT_COUNTRY_REQUIRED => 'TaxDefaultCountry',
        ResultCode::TAX_DEFAULT_COUNTRY_UNKNOWN => 'TaxDefaultCountry',
        ResultCode::TAX_STATE_UNKNOWN => 'TaxState',
        ResultCode::LOCATION_NOT_FOUND => 'Location',
        ResultCode::LOCATION_REQUIRED => 'Location',
        ResultCode::SEO_META_DESCRIPTION_REQUIRED => 'SeoMetaDescription',
        ResultCode::SEO_META_KEYWORDS_REQUIRED => 'SeoMetaKeywords',
        ResultCode::SEO_META_TITLE_REQUIRED => 'SeoMetaTitle',
        ResultCode::FB_OG_DESCRIPTION_REQUIRED => 'FacebookOpenGraphDescription',
        ResultCode::FB_OG_IMAGE_URL_REQUIRED => 'FacebookOpenGraphImage',
        ResultCode::FB_OG_TITLE_REQUIRED => 'FacebookOpenGraphTitle',
        ResultCode::QUANTITY_REQUIRED => 'Quantity',
        ResultCode::QUANTITY_INVALID => 'Quantity',
        ResultCode::QUANTITY_DIGITS_REQUIRED => 'QuantityDigits',
        ResultCode::QUANTITY_DIGITS_INVALID => 'QuantityDigits',
        ResultCode::HAMMER_PRICE_HIDDEN => 'HammerPrice',
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
        ResultCode::ITEM_NUMBER_HIDDEN => 'ItemNum',
        ResultCode::ITEM_NUMBER_EXTENSION_HIDDEN => 'ItemNumExt',
        ResultCode::ITEM_FULL_NUMBER_HIDDEN => 'ItemFullNum',
        ResultCode::CATEGORY_HIDDEN => 'Category',
        ResultCode::QUANTITY_HIDDEN => 'Quantity',
        ResultCode::QUANTITY_DIGITS_HIDDEN => 'QuantityDigits',
        ResultCode::QUANTITY_X_MONEY_HIDDEN => 'QuantityXMoney',
        ResultCode::NAME_HIDDEN => 'Name',
        ResultCode::DESCRIPTION_HIDDEN => 'Description',
        ResultCode::CHANGES_HIDDEN => 'Changes',
        ResultCode::WARRANTY_HIDDEN => 'Warranty',
        ResultCode::IMAGE_HIDDEN => 'LotImage',
        ResultCode::HIGH_ESTIMATE_HIDDEN => 'HighEstimate',
        ResultCode::LOW_ESTIMATE_HIDDEN => 'LowEstimate',
        ResultCode::STARTING_BID_HIDDEN => 'StartingBid',
        ResultCode::INCREMENTS_HIDDEN => 'Increments',
        ResultCode::COST_HIDDEN => 'Cost',
        ResultCode::REPLACEMENT_PRICE_HIDDEN => 'ReplacementPrice',
        ResultCode::RESERVE_PRICE_HIDDEN => 'ReservePrice',
        ResultCode::CONSIGNOR_HIDDEN => 'Consignor',
        ResultCode::WINNING_BIDDER_HIDDEN => 'WinningBidder',
        ResultCode::DATE_SOLD_HIDDEN => 'DateSold',
        ResultCode::AUCTION_SOLD_HIDDEN => 'AuctionSold',
        ResultCode::ONLY_TAX_BP_HIDDEN => 'OnlyTaxBp',
        ResultCode::TAX_HIDDEN => 'Tax',
        ResultCode::TAX_ARTIST_RESALE_RIGHTS_HIDDEN => 'LotItemTaxArr',
        ResultCode::BP_RULE_HIDDEN => 'BpRule',
        ResultCode::BUYERS_PREMIUM_ROWS_HIDDEN => 'BuyersPremiumRows',
        ResultCode::BP_RANGE_CALCULATION_HIDDEN => 'BpRangeCalculation',
        ResultCode::ADDITIONAL_BP_INTERNET_HIDDEN => 'AdditionalBpInternet',
        ResultCode::NO_TAX_OUTSIDE_HIDDEN => 'noTaxOos',
        ResultCode::RETURNED_HIDDEN => 'Returned',
        ResultCode::ITEM_BILLING_COUNTRY_HIDDEN => 'TaxDefaultCountry',
        ResultCode::LOCATION_HIDDEN => 'Location',
        ResultCode::SEO_META_DESCRIPTION_HIDDEN => 'SeoMetaDescription',
        ResultCode::SEO_META_KEYWORDS_HIDDEN => 'SeoMetaKeywords',
        ResultCode::SEO_META_TITLE_HIDDEN => 'SeoMetaTitle',
        ResultCode::FB_OG_DESCRIPTION_HIDDEN => 'FbOgDescription',
        ResultCode::FB_OG_IMAGE_URL_HIDDEN => 'FbOgImageUrl',
        ResultCode::FB_OG_TITLE_HIDDEN => 'FbOgTitle',
        ResultCode::BUYERS_PREMIUMS_HIDDEN => 'BuyersPremiums',
        ResultCode::CONSIGNOR_COMMISSION_REQUIRED => 'ConsignorCommissionId',
        ResultCode::CONSIGNOR_SOLD_FEE_REQUIRED => 'ConsignorSoldFeeId',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REQUIRED => 'ConsignorUnsoldFeeId',
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
            ResultCode::ADDITIONAL_BP_INTERNET_INVALID_FORMAT => $columnHeaders->{Constants\Csv\Lot::ADDITIONAL_BP_INTERNET},
            ResultCode::AUCTION_SOLD_DO_NOT_EXIST => '',
            ResultCode::BUYERS_PREMIUMS_VALIDATION_FAILED => $columnHeaders->{Constants\Csv\Lot::BP_SETTING},
            ResultCode::BP_RANGE_CALCULATION_INVALID_FORMAT => $columnHeaders->{Constants\Csv\Lot::BP_SETTING},
            ResultCode::BP_RULE_UNKNOWN => $columnHeaders->{Constants\Csv\Lot::BP_SETTING},
            ResultCode::BUY_NOW_SELECT_QUANTITY_NOT_ALLOWED => $columnHeaders->{Constants\Csv\Lot::BUY_NOW_SELECT_QUANTITY},
            ResultCode::CATEGORIES_DO_NOT_EXIST => $columnHeaders->{Constants\Csv\Lot::LOT_CATEGORY},
            ResultCode::CATEGORIES_INVALID_ENCODING => $columnHeaders->{Constants\Csv\Lot::LOT_CATEGORY},
            ResultCode::CATEGORIES_NODE_CAN_NOT_BE_ASSIGNED => $columnHeaders->{Constants\Csv\Lot::LOT_CATEGORY},
            ResultCode::CATEGORIES_REQUIRED => $columnHeaders->{Constants\Csv\Lot::LOT_CATEGORY},
            ResultCode::CHANGES_INVALID_ENCODING => $columnHeaders->{Constants\Csv\Lot::CHANGES},
            ResultCode::CHANGES_REQUIRED => $columnHeaders->{Constants\Csv\Lot::CHANGES},
            ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_COMMISSION_CALCULATION_METHOD},
            ResultCode::CONSIGNOR_COMMISSION_ID_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_COMMISSION_ID},
            ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_COMMISSION_RANGES},
            ResultCode::CONSIGNOR_NAME_DO_NOT_EXIST => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR},
            ResultCode::CONSIGNOR_NAME_INVALID_FORMAT => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR},
            ResultCode::CONSIGNOR_REQUIRED => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR},
            ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD},
            ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_ID},
            ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_RANGES},
            ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_REFERENCE},
            ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD},
            ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_ID},
            ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_RANGES},
            ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_REFERENCE},
            ResultCode::COST_INVALID_FORMAT => $columnHeaders->{Constants\Csv\Lot::COST},
            ResultCode::COST_INVALID_THOUSAND_SEPARATOR => $columnHeaders->{Constants\Csv\Lot::COST},
            ResultCode::COST_REQUIRED => $columnHeaders->{Constants\Csv\Lot::COST},
            ResultCode::DATE_SOLD_INVALID_FORMAT => '',
            ResultCode::DESCRIPTION_INVALID_ENCODING => $columnHeaders->{Constants\Csv\Lot::LOT_DESCRIPTION},
            ResultCode::DESCRIPTION_REQUIRED => $columnHeaders->{Constants\Csv\Lot::LOT_DESCRIPTION},
            ResultCode::HAMMER_PRICE_INVALID_FORMAT => '',
            ResultCode::HAMMER_PRICE_REQUIRED => '',
            ResultCode::HIGH_ESTIMATE_INVALID_FORMAT => $columnHeaders->{Constants\Csv\Lot::HIGH_ESTIMATE},
            ResultCode::HIGH_ESTIMATE_INVALID_THOUSAND_SEPARATOR => $columnHeaders->{Constants\Csv\Lot::HIGH_ESTIMATE},
            ResultCode::HIGH_ESTIMATE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::HIGH_ESTIMATE},
            ResultCode::INCREMENTS_AMOUNT_EXIST => $columnHeaders->{Constants\Csv\Lot::INCREMENT},
            ResultCode::INCREMENTS_INVALID_AMOUNT => $columnHeaders->{Constants\Csv\Lot::INCREMENT},
            ResultCode::INCREMENTS_INVALID_FORMAT => $columnHeaders->{Constants\Csv\Lot::INCREMENT},
            ResultCode::INCREMENTS_INVALID_RANGE => $columnHeaders->{Constants\Csv\Lot::INCREMENT},
            ResultCode::ITEM_FULL_NUM_PARSE_ERROR => $columnHeaders->{Constants\Csv\Lot::ITEM_FULL_NUMBER},
            ResultCode::ITEM_NUM_ALREADY_EXIST => $columnHeaders->{Constants\Csv\Lot::ITEM_NUM},
            ResultCode::ITEM_NUM_EXT_INVALID_FORMAT => $columnHeaders->{Constants\Csv\Lot::ITEM_NUM_EXT},
            ResultCode::ITEM_NUM_EXT_INVALID_LENGTH => $columnHeaders->{Constants\Csv\Lot::ITEM_NUM_EXT},
            ResultCode::ITEM_NUM_HIGHER_MAX_AVAILABLE_VALUE => $columnHeaders->{Constants\Csv\Lot::ITEM_NUM},
            ResultCode::ITEM_NUM_INVALID_FORMAT => $columnHeaders->{Constants\Csv\Lot::ITEM_NUM},
            ResultCode::ITEM_NUM_REQUIRED => $columnHeaders->{Constants\Csv\Lot::ITEM_NUM},
            ResultCode::LOW_ESTIMATE_INVALID_FORMAT => $columnHeaders->{Constants\Csv\Lot::LOW_ESTIMATE},
            ResultCode::LOW_ESTIMATE_INVALID_THOUSAND_SEPARATOR => $columnHeaders->{Constants\Csv\Lot::LOW_ESTIMATE},
            ResultCode::LOW_ESTIMATE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::LOW_ESTIMATE},
            ResultCode::NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\Lot::LOT_NAME},
            ResultCode::NAME_IN_BLACKLIST => $columnHeaders->{Constants\Csv\Lot::LOT_NAME},
            ResultCode::NAME_REQUIRED => $columnHeaders->{Constants\Csv\Lot::LOT_NAME},
            ResultCode::REPLACEMENT_PRISE_INVALID_FORMAT => $columnHeaders->{Constants\Csv\Lot::REPLACEMENT_PRICE},
            ResultCode::REPLACEMENT_PRISE_INVALID_THOUSAND_SEPARATOR => $columnHeaders->{Constants\Csv\Lot::REPLACEMENT_PRICE},
            ResultCode::REPLACEMENT_PRISE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::REPLACEMENT_PRICE},
            ResultCode::RESERVE_PRISE_INVALID_FORMAT => $columnHeaders->{Constants\Csv\Lot::RESERVE_PRICE},
            ResultCode::RESERVE_PRISE_INVALID_THOUSAND_SEPARATOR => $columnHeaders->{Constants\Csv\Lot::RESERVE_PRICE},
            ResultCode::RESERVE_PRISE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::RESERVE_PRICE},
            ResultCode::SALES_TAX_INVALID_FORMAT => $columnHeaders->{Constants\Csv\Lot::SALES_TAX},
            ResultCode::SALES_TAX_INVALID_THOUSAND_SEPARATOR => $columnHeaders->{Constants\Csv\Lot::SALES_TAX},
            ResultCode::SALES_TAX_REQUIRED => $columnHeaders->{Constants\Csv\Lot::SALES_TAX},
            ResultCode::SPECIFIC_LOCATION_INVALID => 'Specific location',
            ResultCode::SPECIFIC_LOCATION_REDUNDANT => 'Specific location',
            ResultCode::STARTING_BID_INVALID_FORMAT => $columnHeaders->{Constants\Csv\Lot::STARTING_BID},
            ResultCode::STARTING_BID_INVALID_THOUSAND_SEPARATOR => $columnHeaders->{Constants\Csv\Lot::STARTING_BID},
            ResultCode::STARTING_BID_REQUIRED => $columnHeaders->{Constants\Csv\Lot::STARTING_BID},
            ResultCode::SYNC_KEY_EXIST => '',
            ResultCode::WARRANTY_INVALID_ENCODING => $columnHeaders->{Constants\Csv\Lot::WARRANTY},
            ResultCode::WARRANTY_REQUIRED => $columnHeaders->{Constants\Csv\Lot::WARRANTY},
            ResultCode::WINNING_BIDDER_DO_NOT_EXIST => '',
            ResultCode::WINNING_BIDDER_NOT_REGISTERED_IN_AUCTION_SOLD => '',
            ResultCode::IMAGE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::LOT_IMAGE},
            ResultCode::INCREMENTS_REQUIRED => $columnHeaders->{Constants\Csv\Lot::INCREMENT},
            ResultCode::WINNING_BIDDER_REQUIRED => '',
            ResultCode::WINNING_BIDDER_ID_REQUIRED => '',
            ResultCode::DATE_SOLD_REQUIRED => '',
            ResultCode::AUCTION_SOLD_ID_REQUIRED => '',
            ResultCode::AUCTION_SOLD_REQUIRED => '',
            ResultCode::BP_REQUIRED => $columnHeaders->{Constants\Csv\Lot::BP_SETTING},
            ResultCode::BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER => $columnHeaders->{Constants\Csv\Lot::BP_SETTING},
            ResultCode::TAX_DEFAULT_COUNTRY_REQUIRED => $columnHeaders->{Constants\Csv\Lot::ITEM_TAX_COUNTRY},
            ResultCode::TAX_DEFAULT_COUNTRY_UNKNOWN => $columnHeaders->{Constants\Csv\Lot::ITEM_TAX_COUNTRY},
            ResultCode::TAX_STATE_UNKNOWN => $columnHeaders->{Constants\Csv\Lot::ITEM_TAX_STATES},
            ResultCode::LOCATION_NOT_FOUND => $columnHeaders->{Constants\Csv\Lot::LOCATION},
            ResultCode::LOCATION_REQUIRED => $columnHeaders->{Constants\Csv\Lot::LOCATION},
            ResultCode::SEO_META_DESCRIPTION_REQUIRED => $columnHeaders->{Constants\Csv\Lot::SEO_META_DESCRIPTION},
            ResultCode::SEO_META_KEYWORDS_REQUIRED => $columnHeaders->{Constants\Csv\Lot::SEO_META_KEYWORDS},
            ResultCode::SEO_META_TITLE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::SEO_META_TITLE},
            ResultCode::FB_OG_DESCRIPTION_REQUIRED => $columnHeaders->{Constants\Csv\Lot::FB_OG_DESCRIPTION},
            ResultCode::FB_OG_IMAGE_URL_REQUIRED => $columnHeaders->{Constants\Csv\Lot::FB_OG_IMAGE_URL},
            ResultCode::FB_OG_TITLE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::FB_OG_TITLE},
            ResultCode::QUANTITY_REQUIRED => $columnHeaders->{Constants\Csv\Lot::QUANTITY},
            ResultCode::QUANTITY_INVALID => $columnHeaders->{Constants\Csv\Lot::QUANTITY},
            ResultCode::QUANTITY_DIGITS_REQUIRED => $columnHeaders->{Constants\Csv\Lot::QUANTITY_DIGITS},
            ResultCode::QUANTITY_DIGITS_INVALID => $columnHeaders->{Constants\Csv\Lot::QUANTITY_DIGITS},
            ResultCode::HAMMER_PRICE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::HAMMER_PRICE},
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
            ResultCode::ITEM_NUMBER_HIDDEN => $columnHeaders->{Constants\Csv\Lot::ITEM_NUM},
            ResultCode::ITEM_NUMBER_EXTENSION_HIDDEN => $columnHeaders->{Constants\Csv\Lot::ITEM_NUM_EXT},
            ResultCode::ITEM_FULL_NUMBER_HIDDEN => $columnHeaders->{Constants\Csv\Lot::ITEM_FULL_NUMBER},
            ResultCode::CATEGORY_HIDDEN => $columnHeaders->{Constants\Csv\Lot::LOT_CATEGORY},
            ResultCode::QUANTITY_HIDDEN => $columnHeaders->{Constants\Csv\Lot::QUANTITY},
            ResultCode::QUANTITY_DIGITS_HIDDEN => $columnHeaders->{Constants\Csv\Lot::QUANTITY_DIGITS},
            ResultCode::QUANTITY_X_MONEY_HIDDEN => $columnHeaders->{Constants\Csv\Lot::QUANTITY_X_MONEY},
            ResultCode::NAME_HIDDEN => $columnHeaders->{Constants\Csv\Lot::LOT_NAME},
            ResultCode::DESCRIPTION_HIDDEN => $columnHeaders->{Constants\Csv\Lot::LOT_DESCRIPTION},
            ResultCode::CHANGES_HIDDEN => $columnHeaders->{Constants\Csv\Lot::CHANGES},
            ResultCode::WARRANTY_HIDDEN => $columnHeaders->{Constants\Csv\Lot::WARRANTY},
            ResultCode::IMAGE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::LOT_IMAGE},
            ResultCode::HIGH_ESTIMATE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::HIGH_ESTIMATE},
            ResultCode::LOW_ESTIMATE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::LOW_ESTIMATE},
            ResultCode::STARTING_BID_HIDDEN => $columnHeaders->{Constants\Csv\Lot::STARTING_BID},
            ResultCode::INCREMENTS_HIDDEN => $columnHeaders->{Constants\Csv\Lot::INCREMENT},
            ResultCode::COST_HIDDEN => $columnHeaders->{Constants\Csv\Lot::COST},
            ResultCode::REPLACEMENT_PRICE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::REPLACEMENT_PRICE},
            ResultCode::RESERVE_PRICE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::RESERVE_PRICE},
            ResultCode::CONSIGNOR_HIDDEN => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR},
            ResultCode::WINNING_BIDDER_HIDDEN => '',
            ResultCode::DATE_SOLD_HIDDEN => '',
            ResultCode::AUCTION_SOLD_HIDDEN => '',
            ResultCode::ONLY_TAX_BP_HIDDEN => $columnHeaders->{Constants\Csv\Lot::ONLY_TAX_BP},
            ResultCode::TAX_HIDDEN => $columnHeaders->{Constants\Csv\Lot::SALES_TAX},
            ResultCode::TAX_ARTIST_RESALE_RIGHTS_HIDDEN => '',
            ResultCode::BUYERS_PREMIUMS_HIDDEN => $columnHeaders->{Constants\Csv\Lot::BP_SETTING},
            ResultCode::BP_RULE_HIDDEN => '',
            ResultCode::BUYERS_PREMIUM_ROWS_HIDDEN => $columnHeaders->{Constants\Csv\Lot::BP_SETTING},
            ResultCode::BP_RANGE_CALCULATION_HIDDEN => $columnHeaders->{Constants\Csv\Lot::BP_RANGE_CALCULATION},
            ResultCode::ADDITIONAL_BP_INTERNET_HIDDEN => $columnHeaders->{Constants\Csv\Lot::ADDITIONAL_BP_INTERNET},
            ResultCode::NO_TAX_OUTSIDE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::NO_TAX_OUTSIDE_STATE},
            ResultCode::RETURNED_HIDDEN => $columnHeaders->{Constants\Csv\Lot::RETURNED},
            ResultCode::ITEM_BILLING_COUNTRY_HIDDEN => $columnHeaders->{Constants\Csv\Lot::ITEM_TAX_COUNTRY},
            ResultCode::LOCATION_HIDDEN => $columnHeaders->{Constants\Csv\Lot::LOCATION},
            ResultCode::SEO_META_DESCRIPTION_HIDDEN => $columnHeaders->{Constants\Csv\Lot::SEO_META_DESCRIPTION},
            ResultCode::SEO_META_KEYWORDS_HIDDEN => $columnHeaders->{Constants\Csv\Lot::SEO_META_KEYWORDS},
            ResultCode::SEO_META_TITLE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::SEO_META_TITLE},
            ResultCode::FB_OG_DESCRIPTION_HIDDEN => $columnHeaders->{Constants\Csv\Lot::FB_OG_DESCRIPTION},
            ResultCode::FB_OG_IMAGE_URL_HIDDEN => $columnHeaders->{Constants\Csv\Lot::FB_OG_IMAGE_URL},
            ResultCode::FB_OG_TITLE_HIDDEN => $columnHeaders->{Constants\Csv\Lot::FB_OG_TITLE},
            ResultCode::CONSIGNOR_COMMISSION_REQUIRED => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_COMMISSION_ID},
            ResultCode::CONSIGNOR_SOLD_FEE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_ID},
            ResultCode::CONSIGNOR_UNSOLD_FEE_REQUIRED => $columnHeaders->{Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_ID},
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

    protected array $errorMessages = [
        ResultCode::ACCESS_DENIED => 'Access denied to lot item',
        ResultCode::ADDITIONAL_BP_INTERNET_INVALID_FORMAT => 'Should be numeric',
        ResultCode::AUCTION_SOLD_DO_NOT_EXIST => 'Not found',
        ResultCode::BUYERS_PREMIUMS_VALIDATION_FAILED => 'Validation failed',
        ResultCode::BP_RANGE_CALCULATION_INVALID_FORMAT => 'Should be sliding or tiered',
        ResultCode::BP_RULE_UNKNOWN => 'Does not exist',
        ResultCode::BUY_NOW_SELECT_QUANTITY_NOT_ALLOWED => 'Cannot be used together with “Quantity digits”. “Allow buyer select quantity” only works for Quantity digits zero or when it is not set on any level',
        ResultCode::CATEGORIES_DO_NOT_EXIST => 'Does not exist',
        ResultCode::CATEGORIES_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::CATEGORIES_NODE_CAN_NOT_BE_ASSIGNED => 'Node category cannot be assigned',
        ResultCode::CATEGORIES_REQUIRED => 'Required',
        ResultCode::CHANGES_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::CHANGES_REQUIRED => 'Required',
        ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_COMMISSION_ID_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID => 'Invalid ranges',
        ResultCode::CONSIGNOR_NAME_DO_NOT_EXIST => 'Does not exist',
        ResultCode::CONSIGNOR_NAME_INVALID_FORMAT => 'Should be alphanumeric with only the following chars allowed ( _,-,.,+,@)',
        ResultCode::CONSIGNOR_REQUIRED => 'Required',
        ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID => 'Invalid ranges',
        ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID => 'Invalid ranges',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID => 'Unknown',
        ResultCode::COST_INVALID_FORMAT => 'Should be numeric',
        ResultCode::COST_INVALID_THOUSAND_SEPARATOR => 'Should not contain a thousand separator',
        ResultCode::COST_REQUIRED => 'Required',
        ResultCode::DATE_SOLD_INVALID_FORMAT => 'Invalid',
        ResultCode::DESCRIPTION_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::DESCRIPTION_REQUIRED => 'Required',
        ResultCode::HAMMER_PRICE_INVALID_FORMAT => 'Should be numeric',
        ResultCode::HAMMER_PRICE_REQUIRED => 'Required',
        ResultCode::HIGH_ESTIMATE_INVALID_FORMAT => 'Should be numeric',
        ResultCode::HIGH_ESTIMATE_INVALID_THOUSAND_SEPARATOR => 'Should not contain a thousand separator',
        ResultCode::HIGH_ESTIMATE_REQUIRED => 'Required',
        ResultCode::INCREMENTS_AMOUNT_EXIST => 'Start range already exist',
        ResultCode::INCREMENTS_INVALID_AMOUNT => 'Amount should be > 0',
        ResultCode::INCREMENTS_INVALID_FORMAT => 'Format error',
        ResultCode::INCREMENTS_INVALID_RANGE => 'First range should be 0',
        ResultCode::ITEM_FULL_NUM_PARSE_ERROR => 'Parse error',
        ResultCode::ITEM_NUM_ALREADY_EXIST => 'Already in use',
        ResultCode::ITEM_NUM_EXT_INVALID_FORMAT => 'Invalid',
        ResultCode::ITEM_NUM_EXT_INVALID_LENGTH => 'Length should be less than %s characters',
        ResultCode::ITEM_NUM_HIGHER_MAX_AVAILABLE_VALUE => 'Higher than the max available value',
        ResultCode::ITEM_NUM_INVALID_FORMAT => 'Should be numeric',
        ResultCode::ITEM_NUM_REQUIRED => 'Required',
        ResultCode::LOW_ESTIMATE_INVALID_FORMAT => 'Should be numeric',
        ResultCode::LOW_ESTIMATE_INVALID_THOUSAND_SEPARATOR => 'Should not contain a thousand separator',
        ResultCode::LOW_ESTIMATE_REQUIRED => 'Required',
        // TODO: move $errorMessages to initErrorMessages() to use translations, like $this->getTranslator()->translate('ITEM_BLACKLIST_PHRASE', 'item'),
        ResultCode::NAME_IN_BLACKLIST => 'Contains the blacklisted phrase',
        ResultCode::NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::NAME_REQUIRED => 'Required',
        ResultCode::REPLACEMENT_PRISE_INVALID_FORMAT => 'Should be numeric',
        ResultCode::REPLACEMENT_PRISE_INVALID_THOUSAND_SEPARATOR => 'Should not contain a thousand separator',
        ResultCode::REPLACEMENT_PRISE_REQUIRED => 'Required',
        ResultCode::RESERVE_PRISE_INVALID_FORMAT => 'Should be numeric',
        ResultCode::RESERVE_PRISE_INVALID_THOUSAND_SEPARATOR => 'Should not contain a thousand separator',
        ResultCode::RESERVE_PRISE_REQUIRED => 'Required',
        ResultCode::SALES_TAX_INVALID_FORMAT => 'Should be numeric',
        ResultCode::SALES_TAX_INVALID_THOUSAND_SEPARATOR => 'Should not contain a thousand separator',
        ResultCode::SALES_TAX_REQUIRED => 'Required',
        ResultCode::SPECIFIC_LOCATION_INVALID => 'Invalid',
        ResultCode::SPECIFIC_LOCATION_REDUNDANT => 'Both specific and common event locations are provided',
        ResultCode::STARTING_BID_INVALID_FORMAT => 'Should be numeric',
        ResultCode::STARTING_BID_INVALID_THOUSAND_SEPARATOR => 'Should not contain a thousand separator',
        ResultCode::STARTING_BID_REQUIRED => 'Required',
        ResultCode::SYNC_KEY_EXIST => 'Already exists',
        ResultCode::WARRANTY_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::WARRANTY_REQUIRED => 'Required',
        ResultCode::WINNING_BIDDER_DO_NOT_EXIST => 'Does not exist',
        ResultCode::WINNING_BIDDER_NOT_REGISTERED_IN_AUCTION_SOLD => 'Is not registered in the auction in which the lot was sold',
        ResultCode::IMAGE_REQUIRED => 'Required',
        ResultCode::INCREMENTS_REQUIRED => 'Required',
        ResultCode::WINNING_BIDDER_REQUIRED => 'Required',
        ResultCode::WINNING_BIDDER_ID_REQUIRED => 'Required',
        ResultCode::DATE_SOLD_REQUIRED => 'Required',
        ResultCode::AUCTION_SOLD_ID_REQUIRED => 'Required',
        ResultCode::AUCTION_SOLD_REQUIRED => 'Required',
        ResultCode::BP_REQUIRED => 'Required',
        ResultCode::BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER => 'Named rule can\'t be assigned together with individual ranges or additional BP internet',
        ResultCode::TAX_DEFAULT_COUNTRY_REQUIRED => 'Required',
        ResultCode::TAX_DEFAULT_COUNTRY_UNKNOWN => 'Unknown',
        ResultCode::TAX_STATE_UNKNOWN => 'Unknown',
        ResultCode::LOCATION_NOT_FOUND => 'Not found',
        ResultCode::LOCATION_REQUIRED => 'Required',
        ResultCode::SEO_META_DESCRIPTION_REQUIRED => 'Required',
        ResultCode::SEO_META_KEYWORDS_REQUIRED => 'Required',
        ResultCode::SEO_META_TITLE_REQUIRED => 'Required',
        ResultCode::FB_OG_DESCRIPTION_REQUIRED => 'Required',
        ResultCode::FB_OG_IMAGE_URL_REQUIRED => 'Required',
        ResultCode::FB_OG_TITLE_REQUIRED => 'Required',
        ResultCode::QUANTITY_REQUIRED => 'Required',
        ResultCode::QUANTITY_INVALID => 'Should be positive decimal',
        ResultCode::QUANTITY_DIGITS_REQUIRED => 'Required',
        ResultCode::QUANTITY_DIGITS_INVALID => 'Should be integer between 0 and ' . Constants\Lot::LOT_QUANTITY_MAX_FRACTIONAL_DIGITS,
        ResultCode::CONSIGNOR_COMMISSION_REQUIRED => 'Required',
        ResultCode::CONSIGNOR_SOLD_FEE_REQUIRED => 'Required',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REQUIRED => 'Required',
        ResultCode::HAMMER_PRICE_HIDDEN => 'Hidden',
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
        ResultCode::ITEM_NUMBER_HIDDEN => 'Hidden',
        ResultCode::ITEM_NUMBER_EXTENSION_HIDDEN => 'Hidden',
        ResultCode::ITEM_FULL_NUMBER_HIDDEN => 'Hidden',
        ResultCode::CATEGORY_HIDDEN => 'Hidden',
        ResultCode::QUANTITY_HIDDEN => 'Hidden',
        ResultCode::QUANTITY_DIGITS_HIDDEN => 'Hidden',
        ResultCode::QUANTITY_X_MONEY_HIDDEN => 'Hidden',
        ResultCode::NAME_HIDDEN => 'Hidden',
        ResultCode::DESCRIPTION_HIDDEN => 'Hidden',
        ResultCode::CHANGES_HIDDEN => 'Hidden',
        ResultCode::WARRANTY_HIDDEN => 'Hidden',
        ResultCode::IMAGE_HIDDEN => 'Hidden',
        ResultCode::HIGH_ESTIMATE_HIDDEN => 'Hidden',
        ResultCode::LOW_ESTIMATE_HIDDEN => 'Hidden',
        ResultCode::STARTING_BID_HIDDEN => 'Hidden',
        ResultCode::INCREMENTS_HIDDEN => 'Hidden',
        ResultCode::COST_HIDDEN => 'Hidden',
        ResultCode::REPLACEMENT_PRICE_HIDDEN => 'Hidden',
        ResultCode::RESERVE_PRICE_HIDDEN => 'Hidden',
        ResultCode::CONSIGNOR_HIDDEN => 'Hidden',
        ResultCode::WINNING_BIDDER_HIDDEN => 'Hidden',
        ResultCode::DATE_SOLD_HIDDEN => 'Hidden',
        ResultCode::AUCTION_SOLD_HIDDEN => 'Hidden',
        ResultCode::ONLY_TAX_BP_HIDDEN => 'Hidden',
        ResultCode::TAX_HIDDEN => 'Hidden',
        ResultCode::TAX_ARTIST_RESALE_RIGHTS_HIDDEN => 'Hidden',
        ResultCode::BP_RULE_HIDDEN => 'Hidden',
        ResultCode::BUYERS_PREMIUM_ROWS_HIDDEN => 'Hidden',
        ResultCode::BP_RANGE_CALCULATION_HIDDEN => 'Hidden',
        ResultCode::ADDITIONAL_BP_INTERNET_HIDDEN => 'Hidden',
        ResultCode::NO_TAX_OUTSIDE_HIDDEN => 'Hidden',
        ResultCode::RETURNED_HIDDEN => 'Hidden',
        ResultCode::ITEM_BILLING_COUNTRY_HIDDEN => 'Hidden',
        ResultCode::LOCATION_HIDDEN => 'Hidden',
        ResultCode::SEO_META_DESCRIPTION_HIDDEN => 'Hidden',
        ResultCode::SEO_META_KEYWORDS_HIDDEN => 'Hidden',
        ResultCode::SEO_META_TITLE_HIDDEN => 'Hidden',
        ResultCode::FB_OG_DESCRIPTION_HIDDEN => 'Hidden',
        ResultCode::FB_OG_IMAGE_URL_HIDDEN => 'Hidden',
        ResultCode::FB_OG_TITLE_HIDDEN => 'Hidden',
        ResultCode::BUYERS_PREMIUMS_HIDDEN => 'Hidden',
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

    /**
     * @param LotItemMakerInputDto $inputDto
     * @param LotItemMakerConfigDto $configDto
     * @return static
     */
    public function construct(
        LotItemMakerInputDto $inputDto,
        LotItemMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->customFieldManager ??= LotItemMakerCustomFieldManager::new()->construct($inputDto, $configDto);
        $this->lotItemMakerDtoHelper ??= LotItemMakerDtoHelper::new()->constructLotItemMakerDtoHelper($configDto->mode, $this->customFieldManager);
        $this->lotItemMakerAccessChecker ??= LotItemMakerAccessChecker::new()->construct($inputDto, $configDto);
        return $this;
    }

    /**
     * Validate data
     * @return bool
     */
    public function validate(): bool
    {
        $this->resetErrors();

        $inputDto = $this->getLotItemMakerDtoHelper()->prepareValues($this->getInputDto(), $this->getConfigDto());
        $this->setInputDto($inputDto);
        $configDto = $this->getConfigDto();

        if (!$this->lotItemMakerAccessChecker->canEdit()) {
            $this->addError(ResultCode::ACCESS_DENIED);
            return false;
        }

        if (!$configDto->mode->isWebAdmin()) {
            $this->addTagNamesToErrorMessages();
        }

        $lotItem = $this->getLotItemLoader()->load(Cast::toInt($inputDto->id));
        $this->checkVisibilityWithLotFieldConfig('itemNum', ResultCode::ITEM_NUMBER_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('itemNumExt', ResultCode::ITEM_NUMBER_EXTENSION_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('itemFullNum', ResultCode::ITEM_FULL_NUMBER_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('categories', ResultCode::CATEGORY_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('categoriesNames', ResultCode::CATEGORY_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('categoriesIds', ResultCode::CATEGORY_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('quantity', ResultCode::QUANTITY_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('quantityDigits', ResultCode::QUANTITY_DIGITS_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('quantityXMoney', ResultCode::QUANTITY_X_MONEY_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('name', ResultCode::NAME_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('description', ResultCode::DESCRIPTION_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('changes', ResultCode::CHANGES_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('warranty', ResultCode::WARRANTY_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('images', ResultCode::IMAGE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('highEstimate', ResultCode::HIGH_ESTIMATE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('lowEstimate', ResultCode::LOW_ESTIMATE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('startingBid', ResultCode::STARTING_BID_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('increments', ResultCode::INCREMENTS_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('cost', ResultCode::COST_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('replacementPrice', ResultCode::REPLACEMENT_PRICE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('reservePrice', ResultCode::RESERVE_PRICE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('consignorId', ResultCode::CONSIGNOR_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('consignorName', ResultCode::CONSIGNOR_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('winningBidderId', ResultCode::WINNING_BIDDER_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('winningBidderName', ResultCode::WINNING_BIDDER_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('hammerPrice', ResultCode::HAMMER_PRICE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('dateSold', ResultCode::DATE_SOLD_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('auctionSoldId', ResultCode::AUCTION_SOLD_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('auctionSoldName', ResultCode::AUCTION_SOLD_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('onlyTaxBp', ResultCode::ONLY_TAX_BP_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('salesTax', ResultCode::TAX_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('lotItemTaxArr', ResultCode::TAX_ARTIST_RESALE_RIGHTS_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('bpRule', ResultCode::BP_RULE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('buyersPremiumString', ResultCode::BUYERS_PREMIUM_ROWS_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('buyersPremiumDataRows', ResultCode::BUYERS_PREMIUM_ROWS_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('bpRangeCalculation', ResultCode::BP_RANGE_CALCULATION_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('additionalBpInternet', ResultCode::ADDITIONAL_BP_INTERNET_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('noTaxOos', ResultCode::NO_TAX_OUTSIDE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('returned', ResultCode::RETURNED_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('taxDefaultCountry', ResultCode::ITEM_BILLING_COUNTRY_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('location', ResultCode::LOCATION_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('seoMetaDescription', ResultCode::SEO_META_DESCRIPTION_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('seoMetaKeywords', ResultCode::SEO_META_KEYWORDS_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('seoMetaTitle', ResultCode::SEO_META_TITLE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('fbOgDescription', ResultCode::FB_OG_DESCRIPTION_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('fbOgImageUrl', ResultCode::FB_OG_IMAGE_URL_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('fbOgTitle', ResultCode::FB_OG_TITLE_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('buyersPremiums', ResultCode::BUYERS_PREMIUMS_HIDDEN);
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
        $this->checkVisibilityWithLotFieldConfig('hpTaxSchemaId', ResultCode::HP_TAX_SCHEMA_ID_HIDDEN);
        $this->checkVisibilityWithLotFieldConfig('bpTaxSchemaId', ResultCode::BP_TAX_SCHEMA_ID_HIDDEN);

        $this->validateItemNo();
        if (!$configDto->mode->isWebAdmin()) {
            $this->checkRequiredWithLotFieldConfig('images', ResultCode::IMAGE_REQUIRED);
        }
        $this->validateCustomFields();
        $this->validateCategoriesNamesInvalidEncoding();
        $this->validateConsignorRequired();
        $this->validateNameInBlackList();
        $this->checkInArrayKeys('bpRangeCalculation', ResultCode::BP_RANGE_CALCULATION_INVALID_FORMAT, Constants\BuyersPremium::$rangeCalculationNames);

        $this->checkExistAuctionId('auctionSoldId', ResultCode::AUCTION_SOLD_DO_NOT_EXIST);
        $this->checkExistAuctionSaleNo('auctionSoldName', ResultCode::AUCTION_SOLD_DO_NOT_EXIST);
        if (isset($inputDto->auctionSoldId)) {
            $this->checkRequiredWithLotFieldConfig('auctionSoldId', ResultCode::AUCTION_SOLD_ID_REQUIRED);
        } else {
            $this->checkRequiredWithLotFieldConfig('auctionSoldName', ResultCode::AUCTION_SOLD_REQUIRED);
        }

        $this->checkExistBuyersPremiumShortName('bpRule', ResultCode::BP_RULE_UNKNOWN);
        $this->checkExistUserId('consignorId', ResultCode::CONSIGNOR_NAME_DO_NOT_EXIST);
        $this->checkExistUserId('winningBidderId', ResultCode::WINNING_BIDDER_DO_NOT_EXIST);
        $this->checkExistUserName('winningBidderName', ResultCode::WINNING_BIDDER_DO_NOT_EXIST);
        if (ValueResolver::new()->isTrue($inputDto->internetBid)) {
            if (isset($inputDto->winningBidderId)) {
                $this->checkRequiredWithLotFieldConfig('winningBidderId', ResultCode::WINNING_BIDDER_ID_REQUIRED);
            } else {
                $this->checkRequiredWithLotFieldConfig('winningBidderName', ResultCode::WINNING_BIDDER_REQUIRED);
            }
        }
        $this->checkSyncKeyUnique('syncKey', ResultCode::SYNC_KEY_EXIST, Constants\EntitySync::TYPE_LOT_ITEM);
        $this->checkSyncKeyExistence('auctionSoldSyncKey', ResultCode::AUCTION_SOLD_DO_NOT_EXIST, Constants\EntitySync::TYPE_AUCTION);
        $this->checkSyncKeyExistence('consignorSyncKey', ResultCode::CONSIGNOR_NAME_DO_NOT_EXIST, Constants\EntitySync::TYPE_USER, $configDto->serviceAccountId);
        $this->checkSyncKeyExistence('winningBidderSyncKey', ResultCode::WINNING_BIDDER_DO_NOT_EXIST, Constants\EntitySync::TYPE_USER, $configDto->serviceAccountId);

        if (!$this->getLotItemMakerDtoHelper()->shouldAutoCreateConsignor()) {
            $this->checkExistUserName('consignorName', ResultCode::CONSIGNOR_NAME_DO_NOT_EXIST);
        }
        if (!$this->getLotItemMakerDtoHelper()->shouldAutoCreateCategory()) {
            $this->validateForCategoriesDoNotExist();
        }
        if (!$this->cfg()->get('core->lot->category->nodeCategory->isAssignable')) {
            $this->validateForCategoriesNodes();
        }

        $this->checkDate('dateSold', ResultCode::DATE_SOLD_INVALID_FORMAT);
        $this->checkRequiredWithLotFieldConfig('dateSold', ResultCode::DATE_SOLD_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('increments', ResultCode::INCREMENTS_REQUIRED);
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
        $this->checkReal('additionalBpInternet', ResultCode::ADDITIONAL_BP_INTERNET_INVALID_FORMAT, true);
        $this->checkReal('cost', ResultCode::COST_INVALID_FORMAT, true);
        $this->checkReal('hammerPrice', ResultCode::HAMMER_PRICE_INVALID_FORMAT, true);
        $this->checkReal('highEstimate', ResultCode::HIGH_ESTIMATE_INVALID_FORMAT, true);
        $this->checkReal('lowEstimate', ResultCode::LOW_ESTIMATE_INVALID_FORMAT, true);
        $this->checkReal('replacementPrice', ResultCode::REPLACEMENT_PRISE_INVALID_FORMAT, true);
        $this->checkReal('reservePrice', ResultCode::RESERVE_PRISE_INVALID_FORMAT, true);
        $this->checkReal('salesTax', ResultCode::SALES_TAX_INVALID_FORMAT, true);
        $this->checkReal('startingBid', ResultCode::STARTING_BID_INVALID_FORMAT, true);
        $this->checkCountry('taxDefaultCountry', ResultCode::TAX_DEFAULT_COUNTRY_UNKNOWN);
        $this->validateTaxStates();

        if (isset($inputDto->categoriesIds)) {
            $this->checkRequiredWithLotFieldConfig('categoriesIds', ResultCode::CATEGORIES_REQUIRED);
        } else {
            $this->checkRequiredWithLotFieldConfig('categoriesNames', ResultCode::CATEGORIES_REQUIRED);
        }
        $this->checkRequiredWithLotFieldConfig('cost', ResultCode::COST_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('changes', ResultCode::CHANGES_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('description', ResultCode::DESCRIPTION_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('highEstimate', ResultCode::HIGH_ESTIMATE_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('lowEstimate', ResultCode::LOW_ESTIMATE_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('name', ResultCode::NAME_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('replacementPrice', ResultCode::REPLACEMENT_PRISE_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('reservePrice', ResultCode::RESERVE_PRISE_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('salesTax', ResultCode::SALES_TAX_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('warranty', ResultCode::WARRANTY_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('taxDefaultCountry', ResultCode::TAX_DEFAULT_COUNTRY_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('hpTaxSchemaId', ResultCode::HP_TAX_SCHEMA_ID_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('bpTaxSchemaId', ResultCode::BP_TAX_SCHEMA_ID_REQUIRED);
        $this->checkRequiredWithLotFieldConfigLocation(ResultCode::LOCATION_REQUIRED);
        $this->createLocationValidationIntegrator()->validate($this, $inputDto->specificLocation, ResultCode::SPECIFIC_LOCATION_INVALID, Constants\Location::TYPE_LOT_ITEM);
        $this->checkProhibits('specificLocation', ['location'], ResultCode::SPECIFIC_LOCATION_REDUNDANT);
        $this->checkExistLocationName('location', ResultCode::LOCATION_NOT_FOUND);
        if (
            !$configDto->auctionId
            || !$inputDto->id
        ) {
            $this->checkRequiredWithLotFieldConfig('quantity', ResultCode::QUANTITY_REQUIRED);
            $this->checkRequiredWithLotFieldConfig('quantityDigits', ResultCode::QUANTITY_DIGITS_REQUIRED);
        }
        $quantityScale = $this->createQuantityScaleDetector()->detect(
            QuantityScaleDetectorInput::new()->fromMakerDto($inputDto, $configDto)
        );
        $this->createQuantityValidationIntegrator()->validate($this, $quantityScale);
        $this->checkInteger('quantityDigits', ResultCode::QUANTITY_DIGITS_INVALID);
        $this->checkBetween('quantityDigits', ResultCode::QUANTITY_DIGITS_INVALID, 0, Constants\Lot::LOT_QUANTITY_MAX_FRACTIONAL_DIGITS);
        $this->checkUsername('consignorName', ResultCode::CONSIGNOR_NAME_INVALID_FORMAT);
        $this->checkEncoding('changes', ResultCode::CHANGES_INVALID_ENCODING);
        $this->checkEncoding('description', ResultCode::DESCRIPTION_INVALID_ENCODING);
        $this->checkEncoding('name', ResultCode::NAME_INVALID_ENCODING);
        $this->checkEncoding('warranty', ResultCode::WARRANTY_INVALID_ENCODING);
        $this->checkThousandSeparator('cost', ResultCode::COST_INVALID_THOUSAND_SEPARATOR);
        $this->checkThousandSeparator('highEstimate', ResultCode::HIGH_ESTIMATE_INVALID_THOUSAND_SEPARATOR);
        $this->checkThousandSeparator('lowEstimate', ResultCode::LOW_ESTIMATE_INVALID_THOUSAND_SEPARATOR);
        $this->checkThousandSeparator('replacementPrice', ResultCode::REPLACEMENT_PRISE_INVALID_THOUSAND_SEPARATOR);
        $this->checkThousandSeparator('reservePrice', ResultCode::RESERVE_PRISE_INVALID_THOUSAND_SEPARATOR);
        $this->checkThousandSeparator('salesTax', ResultCode::SALES_TAX_INVALID_THOUSAND_SEPARATOR);
        $this->checkThousandSeparator('startingBid', ResultCode::STARTING_BID_INVALID_THOUSAND_SEPARATOR);
        $this->checkRequiredWithLotFieldConfig('seoMetaDescription', ResultCode::SEO_META_DESCRIPTION_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('seoMetaKeywords', ResultCode::SEO_META_KEYWORDS_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('seoMetaTitle', ResultCode::SEO_META_TITLE_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('fbOgDescription', ResultCode::FB_OG_DESCRIPTION_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('fbOgTitle', ResultCode::FB_OG_TITLE_REQUIRED);
        $this->checkRequiredWithLotFieldConfig('fbOgImageUrl', ResultCode::FB_OG_IMAGE_URL_REQUIRED);
        if (AuctionStatusPureChecker::new()->isTimed($configDto->auctionType)) {
            $this->validateStartingBid();
        }
        if (AuctionLotStatusPureChecker::new()->isAmongWonStatuses($configDto->lotStatusId)) {
            $this->checkRequiredZeroAllowed('hammerPrice', ResultCode::HAMMER_PRICE_REQUIRED);
        } else {
            $this->checkRequiredZeroAllowedWithLotFieldConfig('hammerPrice', ResultCode::HAMMER_PRICE_REQUIRED);
        }
        $isWinningBidderNotRegisteredInAuctionSold = $this->createWinningBidderAuctionChecker()->isNotRegisteredInAuctionSold(
            WinningBidderInput::new()->fromDto($inputDto),
            $lotItem->WinningBidderId ?? null,
            WinningAuctionInput::new()->fromDto($inputDto),
            $lotItem->AuctionId ?? null,
            Cast::toInt($inputDto->syncNamespaceId),
            $configDto->serviceAccountId,
            true
        );
        if ($isWinningBidderNotRegisteredInAuctionSold) {
            $this->addError(ResultCode::WINNING_BIDDER_NOT_REGISTERED_IN_AUCTION_SOLD);
        }
        $this->validateBuyNowSelectQuantityEnabled($quantityScale);
        $this->validateConsignorCommission();
        $this->validateConsignorSoldFee();
        $this->validateConsignorUnsoldFee();
        $this->createLotItemTaxSchemaValidationIntegrator()->validate($this);

        $this->log();
        $isValid = empty($this->errors) && empty($this->customFieldsErrors);
        $configDto->enableValidStatus($isValid);
        return $isValid;
    }

    protected function validateBuyersPremiums(): void
    {
        $inputDto = $this->getInputDto();
        $input = BuyersPremiumValidationInput::new()->fromLotItemMakerDto(
            $inputDto,
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

        if (
            !$this->hasError(ResultCode::BUYERS_PREMIUMS_VALIDATION_FAILED)
            && $this->isRequiredByLotFieldConfig('buyersPremiums')
            && $this->isBuyersPremiumFieldsSet()
            && $this->isBuyersPremiumEmpty()
        ) {
            $this->addError(ResultCode::BP_REQUIRED);
        }
    }

    protected function isBuyersPremiumFieldsSet(): bool
    {
        $inputDto = $this->getInputDto();
        return isset($inputDto->bpRule) || $this->isIndividualBuyersPremiumFieldsSet();
    }

    protected function isIndividualBuyersPremiumFieldsSet(): bool
    {
        $inputDto = $this->getInputDto();
        return isset($inputDto->additionalBpInternet)
            || isset($inputDto->buyersPremiumString)
            || isset($inputDto->buyersPremiumDataRows);
    }

    protected function isBuyersPremiumEmpty(): bool
    {
        return !$this->getInputDto()->bpRule
            && $this->isIndividualBuyersPremiumEmpty();
    }

    protected function isIndividualBuyersPremiumEmpty(): bool
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (
            $inputDto->additionalBpInternet
            && !AuctionStatusPureChecker::new()->isTimed($configDto->auctionType)
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
     * Check for categories do not exist
     */
    protected function validateForCategoriesDoNotExist(): void
    {
        $categoriesNames = $this->getInputDto()->categoriesNames;
        if (!$categoriesNames) {
            return;
        }
        foreach ($categoriesNames as $categoryName) {
            $categoryName = trim($categoryName);
            if (
                $categoryName !== ''
                && !$this->getLotCategoryExistenceChecker()->existByName($categoryName)
            ) {
                $this->addError(ResultCode::CATEGORIES_DO_NOT_EXIST, "Category '$categoryName' does not exist");
                break;
            }
        }
    }

    protected function validateForCategoriesNodes(): void
    {
        foreach ($this->getInputDto()->categoriesIds ?? [] as $categoryId) {
            if ($this->getLotCategoryExistenceChecker()->countChildren($categoryId)) {
                $this->addError(ResultCode::CATEGORIES_NODE_CAN_NOT_BE_ASSIGNED);
                break;
            }
        }

        foreach ($this->getInputDto()->categoriesNames ?? [] as $categoryName) {
            $categoryName = trim($categoryName);
            if (!$categoryName) {
                continue;
            }
            $lotCategory = $this->getLotCategoryLoader()->loadByName($categoryName);
            if (!$lotCategory) {
                continue;
            }
            if ($this->getLotCategoryExistenceChecker()->countChildren($lotCategory->Id)) {
                $this->addError(ResultCode::CATEGORIES_NODE_CAN_NOT_BE_ASSIGNED, "'$categoryName' node category cannot be assigned");
                break;
            }
        }
    }

    /**
     * Check for categories names invalid encoding
     */
    protected function validateCategoriesNamesInvalidEncoding(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (
            !$inputDto->categoriesNames
            || !$configDto->encoding
        ) {
            return;
        }

        $concatCategories = implode('', $inputDto->categoriesNames);
        $hasValidEncoding = TextChecker::new()->hasValidEncoding($concatCategories, $configDto->encoding);
        $this->addErrorIfFail(ResultCode::CATEGORIES_INVALID_ENCODING, $hasValidEncoding);
    }

    /**
     * Check for consignor required
     */
    protected function validateConsignorRequired(): void
    {
        $inputDto = $this->getInputDto();
        if (
            !$inputDto->consignorId
            && !$inputDto->consignorName
            && !$inputDto->consignorSyncKey
        ) {
            $this->checkRequiredWithLotFieldConfig('consignorId', ResultCode::CONSIGNOR_REQUIRED);
        }
    }

    /**
     * Check itemFullNum, split to itemNum, itemNumExtension to validate each element separately
     */
    protected function validateItemNo(): void
    {
        $this->createItemNoValidationIntegrator()->validate($this);
    }

    /**
     * Check for name is in the black list
     */
    protected function validateNameInBlackList(): void
    {
        $name = $this->getInputDto()->name;
        if ($name) {
            $blacklistPhrase = $this->getBlacklistPhrase();
            if ($blacklistPhrase) {
                if (TextChecker::new()->isInBlacklistPhrase($name, $blacklistPhrase)) {
                    $message = $this->getTranslator()->translate('ITEM_BLACKLIST_PHRASE', 'item') . $blacklistPhrase;
                    $this->addError(ResultCode::NAME_IN_BLACKLIST, $message);
                }
            }
        }
    }

    /**
     * Check startingBid
     */
    protected function validateStartingBid(): void
    {
        $configDto = $this->getConfigDto();
        if (
            $configDto->auctionId > 0
            && !$configDto->reverse
            && !$configDto->noBidding
        ) {
            $this->checkRequired('startingBid', ResultCode::STARTING_BID_REQUIRED);
        }
    }

    /**
     * Get blacklist phrase
     * @return string
     */
    private function getBlacklistPhrase(): string
    {
        $configDto = $this->getConfigDto();
        $blacklistPhrase = (string)$this->getSettingsManager()
            ->get(Constants\Setting::BLACKLIST_PHRASE, $configDto->serviceAccountId);
        if (!$blacklistPhrase) {
            $auction = $this->getAuctionLoader()->load($configDto->auctionId);
            if ($auction) {
                $blacklistPhrase = $auction->BlacklistPhrase;
            }
        }
        return $blacklistPhrase;
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

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistAuctionId(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isFoundById = $this->getAuctionExistenceChecker()->existById((int)$inputDto->$field);
        $this->addErrorIfFail($error, $isFoundById);
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistAuctionSaleNo(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isFoundByFullSaleNo = $this->getAuctionExistenceChecker()->existByFullSaleNo($inputDto->$field);
        $this->addErrorIfFail($error, $isFoundByFullSaleNo);
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
     * @param string $field
     * @param int $error
     */
    protected function checkExistUserName(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isFoundByUsername = $this->getUserExistenceChecker()->existByUsername($inputDto->$field);
        $this->addErrorIfFail($error, $isFoundByUsername);
    }

    private function validateBuyNowSelectQuantityEnabled(int $quantityScale): void
    {
        $inputDto = $this->getInputDto();
        $isSetBuyNowSelectQuantityEnabled = isset($inputDto->buyNowSelectQuantityEnabled);
        $isBuyNowSelectQuantityEnabled = ValueResolver::new()->isTrue((string)$inputDto->buyNowSelectQuantityEnabled);
        if (
            $isSetBuyNowSelectQuantityEnabled
            && $isBuyNowSelectQuantityEnabled
            && $quantityScale > 0
        ) {
            $this->addError(ResultCode::BUY_NOW_SELECT_QUANTITY_NOT_ALLOWED);
        }
    }

    /** GetErrors Protected Methods */

    /**
     * Get additionalBpInternet errors
     * @return int[]
     */
    protected function getAdditionalBpInternetErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::ADDITIONAL_BP_INTERNET_INVALID_FORMAT]);
        return $intersected;
    }

    /**
     * Get categories errors
     * @return int[]
     */
    protected function getCategoriesErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::CATEGORIES_NODE_CAN_NOT_BE_ASSIGNED,
                ResultCode::CATEGORIES_REQUIRED
            ]
        );
        return $intersected;
    }

    /**
     * Get changes errors
     * @return int[]
     */
    protected function getChangesErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::CHANGES_REQUIRED]
        );
        return $intersected;
    }

    /**
     * Get consignor errors
     * @return int[]
     */
    protected function getConsignorErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::CONSIGNOR_NAME_DO_NOT_EXIST,
                ResultCode::CONSIGNOR_NAME_INVALID_FORMAT,
                ResultCode::CONSIGNOR_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * Get cost errors
     * @return int[]
     */
    protected function getCostErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::COST_REQUIRED,
                ResultCode::COST_INVALID_FORMAT,
                ResultCode::COST_INVALID_THOUSAND_SEPARATOR
            ]
        );
        return $intersected;
    }

    /**
     * Get customFields errors
     * @return array
     */
    public function getCustomFieldsErrors(): array
    {
        return $this->customFieldsErrors;
    }

    /**
     * Get dateSold errors
     * @return int[]
     */
    protected function getDateSoldErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::DATE_SOLD_INVALID_FORMAT,
                ResultCode::DATE_SOLD_REQUIRED
            ]
        );
        return $intersected;
    }

    /**
     * Get description errors
     * @return int[]
     */
    protected function getDescriptionErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::DESCRIPTION_REQUIRED]
        );
        return $intersected;
    }

    /**
     * Get hammerPrice errors
     * @return int[]
     */
    protected function getHammerPriceErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::HAMMER_PRICE_REQUIRED,
                ResultCode::HAMMER_PRICE_INVALID_FORMAT
            ]
        );
        return $intersected;
    }

    /**
     * Get highEstimate errors
     * @return int[]
     */
    protected function getHighEstimateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::HIGH_ESTIMATE_REQUIRED,
                ResultCode::HIGH_ESTIMATE_INVALID_FORMAT,
                ResultCode::HIGH_ESTIMATE_INVALID_THOUSAND_SEPARATOR
            ]
        );
        return $intersected;
    }

    /**
     * Get itemFullNum errors
     * @return int[]
     */
    protected function getItemFullNumErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::ITEM_FULL_NUM_PARSE_ERROR,
                ResultCode::ITEM_NUM_REQUIRED,
                ResultCode::ITEM_NUM_ALREADY_EXIST,
                ResultCode::ITEM_NUM_HIGHER_MAX_AVAILABLE_VALUE,
                ResultCode::ITEM_NUM_INVALID_FORMAT,
                ResultCode::ITEM_NUM_EXT_INVALID_FORMAT,
                ResultCode::ITEM_NUM_EXT_INVALID_LENGTH,
            ]
        );
        return $intersected;
    }

    /**
     * Get increments errors
     * @return int[]
     */
    protected function getIncrementsErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::INCREMENTS_AMOUNT_EXIST,
                ResultCode::INCREMENTS_INVALID_AMOUNT,
                ResultCode::INCREMENTS_INVALID_FORMAT,
                ResultCode::INCREMENTS_INVALID_RANGE,
                ResultCode::INCREMENTS_REQUIRED
            ]
        );
        return $intersected;
    }

    /**
     * Get itemNum errors
     * @return int[]
     */
    protected function getItemNumErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::ITEM_NUM_REQUIRED,
                ResultCode::ITEM_NUM_ALREADY_EXIST,
                ResultCode::ITEM_NUM_HIGHER_MAX_AVAILABLE_VALUE,
                ResultCode::ITEM_NUM_INVALID_FORMAT
            ]
        );
        return $intersected;
    }

    /**
     * Get itemNumExt errors
     * @return int[]
     */
    protected function getItemNumExtErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::ITEM_NUM_EXT_INVALID_FORMAT,
                ResultCode::ITEM_NUM_EXT_INVALID_LENGTH,
            ]
        );
        return $intersected;
    }

    /**
     * Get lowEstimate errors
     * @return int[]
     */
    protected function getLowEstimateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::LOW_ESTIMATE_REQUIRED,
                ResultCode::LOW_ESTIMATE_INVALID_FORMAT,
                ResultCode::LOW_ESTIMATE_INVALID_THOUSAND_SEPARATOR
            ]
        );
        return $intersected;
    }

    /**
     * Get name errors
     * @return int[]
     */
    protected function getNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::NAME_REQUIRED,
                ResultCode::NAME_IN_BLACKLIST
            ]
        );
        return $intersected;
    }

    /**
     * Get replacementPrice errors
     * @return int[]
     */
    protected function getReplacementPriceErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::REPLACEMENT_PRISE_REQUIRED,
                ResultCode::REPLACEMENT_PRISE_INVALID_FORMAT,
                ResultCode::REPLACEMENT_PRISE_INVALID_THOUSAND_SEPARATOR
            ]
        );
        return $intersected;
    }

    /**
     * Get reservePrice errors
     * @return int[]
     */
    protected function getReservePriceErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::RESERVE_PRISE_REQUIRED,
                ResultCode::RESERVE_PRISE_INVALID_FORMAT,
                ResultCode::RESERVE_PRISE_INVALID_THOUSAND_SEPARATOR
            ]
        );
        return $intersected;
    }

    /**
     * Get salesTax errors
     * @return int[]
     *
     */
    protected function getSalesTaxErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::SALES_TAX_REQUIRED,
                ResultCode::SALES_TAX_INVALID_FORMAT,
                ResultCode::SALES_TAX_INVALID_THOUSAND_SEPARATOR
            ]
        );
        return $intersected;
    }

    /**
     * Get startingBid errors
     * @return int[]
     */
    protected function getStartingBidErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::STARTING_BID_REQUIRED,
                ResultCode::STARTING_BID_INVALID_FORMAT,
                ResultCode::STARTING_BID_INVALID_THOUSAND_SEPARATOR
            ]
        );
        return $intersected;
    }

    /**
     * Get warranty errors
     * @return int[]
     */
    protected function getWarrantyErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::WARRANTY_REQUIRED]);
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
            $accountId = $inputDto->accountId ?? $configDto->serviceAccountId;
            $isExist = $this->createConsignorCommissionFeeExistenceChecker()->existByIdAndAccountId((int)$consignorCommissionId, $accountId);
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
            $accountId = $inputDto->accountId ?? $configDto->serviceAccountId;
            $isExist = $this->createConsignorCommissionFeeExistenceChecker()->existByIdAndAccountId((int)$consignorSoldFeeId, $accountId);
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
            $accountId = $inputDto->accountId ?? $configDto->serviceAccountId;
            $isExist = $this->createConsignorCommissionFeeExistenceChecker()->existByIdAndAccountId((int)$consignorUnsoldFeeId, $accountId);
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
            $accountId = $inputDto->accountId ?? $configDto->serviceAccountId;
            $isExist = $this->createConsignorCommissionFeeExistenceChecker()->existByIdAndAccountId((int)$consignorCommissionId, $accountId);
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
            $configDto->auctionId
            && $inputDto->id
        ) {
            /**
             * Skip validation for the existing lot item that is managed in auction context,
             * because the same validation must be performed in the Auction Lot Entity-maker.
             */
            return;
        }

        if (
            $inputDto->id
            && !isset($inputDto->{$idField})
            && !isset($inputDto->{$rangesField})
        ) {
            /**
             * Skip validation for the existing lot item, when fields are absent in input.
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

    protected function checkRequiredWithLotFieldConfigLocation(int $error): void
    {
        if (!$this->isRequiredByLotFieldConfig('location')) {
            return;
        }

        $inputDto = $this->getInputDto();
        if (
            $inputDto->id
            && !isset($inputDto->location)
            && !isset($inputDto->specificLocation)
        ) {
            return;
        }

        if (
            $inputDto->location
            || ($inputDto->specificLocation
                && array_filter((array)$inputDto->specificLocation))
        ) {
            return;
        }

        $this->addError($error);
    }

    protected function checkVisibilityWithLotFieldConfig(string $field, int $error): void
    {
        if (!isset($this->getInputDto()->{$field})) {
            return;
        }

        $this->addErrorIfFail($error, $this->isVisibleByLotFieldConfig($field));
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
     * Regular check of requirement and if field is required in lot_field_config table
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkRequiredZeroAllowedWithLotFieldConfig(string $field, int $error): void
    {
        if (!$this->isRequiredByLotFieldConfig($field)) {
            return;
        }
        $this->checkRequiredZeroAllowed($field, $error);
    }

    protected function validateTaxStates(): void
    {
        foreach ((array)$this->getInputDto()->taxStates as $state) {
            if (!AddressRenderer::new()->normalizeState($this->getInputDto()->taxDefaultCountry, $state)) {
                $this->addError(ResultCode::TAX_STATE_UNKNOWN, 'Tax state unknown: ' . $state);
                break;
            }
        }
    }

    // ---------------------------------------------------------------------------------------------

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
    public function getWinningBidderErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::WINNING_BIDDER_REQUIRED,
                ResultCode::WINNING_BIDDER_ID_REQUIRED,
                ResultCode::WINNING_BIDDER_NOT_REGISTERED_IN_AUCTION_SOLD,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getAuctionSoldErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::AUCTION_SOLD_ID_REQUIRED,
                ResultCode::AUCTION_SOLD_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getBuyersPremiumsErrors(): array
    {
        $intersected = array_intersect($this->errors, [
            ResultCode::BP_REQUIRED,
            ResultCode::BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER,
        ]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getTaxDefaultCountryErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::TAX_DEFAULT_COUNTRY_REQUIRED]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getLocationErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::LOCATION_REQUIRED]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getSeoMetaDescriptionErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::SEO_META_DESCRIPTION_REQUIRED]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getSeoMetaKeywordsErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::SEO_META_KEYWORDS_REQUIRED]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getSeoMetaTitleErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::SEO_META_TITLE_REQUIRED]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getFacebookOpenGraphDescriptionErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::FB_OG_DESCRIPTION_REQUIRED]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getFacebookOpenGraphImageUrlErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::FB_OG_IMAGE_URL_REQUIRED]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getFacebookOpenGraphTitleErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::FB_OG_TITLE_REQUIRED]);
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
     * Get quantity digits errors
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
     * Get buyNowSelectQuantity errors
     * @return int[]
     */
    protected function getBuyNowSelectQuantityEnabledErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::BUY_NOW_SELECT_QUANTITY_NOT_ALLOWED]);
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

    /**
     * Support logging of found errors or success
     */
    protected function log(): void
    {
        $inputDto = $this->getInputDto();
        if (empty($this->errors)) {
            log_trace('Lot item validation done' . composeSuffix(['li' => $inputDto->id]));
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
                'li' => $inputDto->id,
                'errors' => array_merge(array_values($foundNamesWithCodes), $notFoundCodes),
            ];
            log_debug('Lot item validation failed' . composeSuffix($logData));
        }
    }
}
